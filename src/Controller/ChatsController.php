<?php
// /src/Controller/ChatController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\User;
use App\Utils\IdEncoder;
use Src\Service\AuthService;
use Carbon\Carbon;

class ChatsController
{
    /**
     * Handle Delete for a specific message (including file cleanup) 💎
     */
    public function delete($id): array
    {
        try {
            $currentUserId = AuthService::userId();
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            $message = ConversationMessage::where('id', $rawId)
                ->where('sender_id', $currentUserId)
                ->first();

            if ($message) {
                $conversationId = $message->conversation_id;

                // 🍊 PHYSICAL FILE CLEANUP
                if ($message->message_type === 'image' && !empty($message->attachment_url)) {
                    $this->deletePhysicalFile($message->attachment_url);
                }

                if ($message->delete()) {
                    // Update conversation timestamp (Your existing logic)
                    $latestRemaining = ConversationMessage::where('conversation_id', $conversationId)
                        ->latest()
                        ->first();

                    if ($latestRemaining) {
                        Conversation::where('id', $conversationId)
                            ->update(['last_message_at' => $latestRemaining->created_at]);
                    }

                    return ['success' => true, 'messages' => ['Message and file deleted.']];
                }
            }

            return ['success' => false, 'messages' => ['Unauthorized or not found.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Helper to turn a URL into a path and delete it 🎯
     * Aligned with /server/api/chat-media-upload.php logic
     */
    private function deletePhysicalFile(string $url): void
    {
        // 1. Get the filename from the URL (e.g., "65f123.jpg")
        // Your API uses basename() which is the safest way to extract the end of the URL
        $filename = basename(parse_url($url, PHP_URL_PATH));

        if (!$filename) {
            return;
        }

        /**
         * 2. Construct the Absolute Path 💎
         * We use realpath(__DIR__) to start from /src/Controller/
         * Then go up twice to reach the root, then down into public/images/...
         */
        $basePath = realpath(__DIR__ . '/../../public/images/uploads/chats/');

        if (!$basePath) {
            error_log("Chat Delete Error: Upload directory not found.");
            return;
        }

        $filePath = $basePath . DIRECTORY_SEPARATOR . $filename;

        // 3. The Nuke
        if (file_exists($filePath) && is_file($filePath)) {
            if (!unlink($filePath)) {
                error_log("Failed to unlink chat media: " . $filePath);
            }
        } else {
            error_log("Chat media not found for deletion: " . $filePath);
        }
    }

    /**
     * Get unread count for the currently logged-in user
     */
    public static function getUnreadCount(): int
    {
        $userId = AuthService::userId();
        if (!$userId) return 0;

        return (int) ConversationMessage::where('is_read', false)
            ->where('sender_id', '!=', $userId)
            ->whereHas('conversation', function ($query) use ($userId) {
                $query->where('user_one_id', $userId)
                    ->orWhere('user_two_id', $userId);
            })
            ->count();
    }

    /**
     * Display the Inbox / Conversation List
     */
    public function index(): void
    {
        $currentUserId = AuthService::userId();

        // 1. Fetch conversations involving the current user, ordered by latest activity
        $conversations = Conversation::with(['userOne', 'userTwo', 'lastMessage'])
            ->where('user_one_id', $currentUserId)
            ->orWhere('user_two_id', $currentUserId)
            ->orderBy('last_message_at', 'desc')
            ->get();

        $GLOBALS['totalCount'] = $conversations->count();

        $html = '';
        foreach ($conversations as $convo) {
            $html .= self::renderInboxItem($convo, $currentUserId);
        }

        // Standard response for AJAX
        if (isset($_GET['ajax'])) {
            json_response(['success' => true, 'html' => $html]);
            exit;
        }

        // Set Globals for the page load
        $GLOBALS['inboxHtml'] = $html;
        $GLOBALS['title'] = "Messages";
    }

    /**
     * Show a specific conversation thread (Modal-Optimized) 💎
     */
    public function show(string $encodedTargetUserId): void
    {
        try {
            $currentUserId = AuthService::userId();
            $targetUserId = IdEncoder::decode($encodedTargetUserId);
            $targetUser = User::find($targetUserId);

            if (!$targetUser) {
                json_response(['success' => false, 'messages' => ['User not found']], 404);
                return;
            }

            // 🍊 Get Polling Params
            $lastId = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
            $isRefresh = isset($_GET['refresh']);

            $u1 = min($currentUserId, $targetUserId);
            $u2 = max($currentUserId, $targetUserId);

            $conversation = Conversation::firstOrCreate(
                ['user_one_id' => $u1, 'user_two_id' => $u2],
                ['last_message_at' => \Carbon\Carbon::now()]
            );

            // 🍊 THE FIX: Only fetch messages LARGER than the lastId
            $query = $conversation->messages()->with('sender');

            if ($lastId > 0) {
                $query->where('id', '>', $lastId);
            }

            $messages = $query->orderBy('created_at', 'asc')->get();

            // Mark incoming as read
            $conversation->messages()
                ->where('sender_id', $targetUserId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $messagesHtml = '';
            $newLastId = $lastId;

            foreach ($messages as $msg) {
                $messagesHtml .= self::renderMessageBubble($msg, $currentUserId);
                $newLastId = $msg->id; // Track the absolute latest ID
            }

            $response = [
                'success' => true,
                'html_messages' => $messagesHtml,
                'last_id' => $newLastId // Send this back so JS knows where we left off
            ];

            // 🍊 SYNC DELETIONS: When refreshing, send back all currently active IDs
            // This allows the front-end to remove bubbles that were deleted by the other user.
            if ($isRefresh) {
                $activeIds = $conversation->messages()
                    ->orderBy('created_at', 'desc')
                    ->limit(50) // Limit to recent to keep payload small
                    ->pluck('id')
                    ->toArray();

                $response['active_ids'] = array_map(function ($id) {
                    return IdEncoder::encode((int)$id);
                }, $activeIds);
            }

            // Only include header on first load, not polling refreshes
            if (!$isRefresh) {
                $response['html_header'] = self::renderModalHeader($targetUser);
                $response['conversation_id'] = IdEncoder::encode((int)$conversation->id);
            }

            json_response($response);
        } catch (\Throwable $e) {
            error_log("Chat Show Error: " . $e->getMessage());
            json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
        }
    }

    /**
     * Helper to render the user info for the top of the modal
     */
    public static function renderModalHeader(User $user): string
    {
        // 1. Resolve Identity
        $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        if (empty($displayName)) $displayName = $user->name ?? 'Unknown User';

        $initial = strtoupper(substr($user->first_name ?? $displayName ?? 'U', 0, 1));

        // 2. Resolve Geography (Objects to String)
        $city    = $user->city ?? '';
        $region  = $user->region->region ?? ''; // Relationship access
        $country = $user->country->country ?? ''; // Relationship access

        // Format: City - Region, Country
        $location = 'Unknown Location';
        if ($city || $region || $country) {
            $regionCountry = implode(', ', array_filter([$region, $country]));
            $location = $city ? "{$city} - {$regionCountry}" : $regionCountry;
        }

        // 3. Resolve Assets
        $assetBase = getAssetBase();
        $avatarUrl = !empty($user->avatar_url)
            ? $assetBase . 'images/uploads/avatars/' . $user->avatar_url
            : null;

        ob_start();
        include __DIR__ . '/../../resources/views/components/chats/detail-modal-header.php';
        return ob_get_clean();
    }

    /**
     * Send a new message
     */
    // /server/Controller/ChatsController.php

    public function sendMessage(array $data): array
    {
        try {
            $currentUserId = AuthService::userId();

            // 🍊 FORCE CHECK: Check $_POST directly if $data is missing the key
            $rawAttachment = $data['attachment_url'] ?? $_POST['attachment_url'] ?? null;

            // 🍊 PRODUCTION FIX: Strip the directory, save only the filename
            $cleanAttachment = $rawAttachment ? basename($rawAttachment) : null;

            $encodedTargetId = $data['to_user_id'] ?? $_POST['to_user_id'] ?? null;
            $targetUserId = IdEncoder::decode($encodedTargetId);

            if (empty($data['message_text']) && empty($cleanAttachment)) {
                throw new \Exception("Message content cannot be empty.");
            }

            $u1 = min($currentUserId, $targetUserId);
            $u2 = max($currentUserId, $targetUserId);

            $conversation = Conversation::firstOrCreate(
                ['user_one_id' => $u1, 'user_two_id' => $u2]
            );

            // Create the record
            $message = ConversationMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $currentUserId,
                'message_text'    => trim($data['message_text'] ?? $_POST['message_text'] ?? ''),
                'message_type'    => !empty($cleanAttachment) ? 'image' : 'text',
                'attachment_url'  => $cleanAttachment, // Saving only "file.jpg"
                'is_read'         => false
            ]);

            $message->refresh();

            $conversation->update(['last_message_at' => \Carbon\Carbon::now()]);

            return [
                'success' => true,
                'html'    => self::renderMessageBubble($message, $currentUserId),
                'messages' => ['Message sent.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render the Orange (Me) or Navy (Them) message bubble
     */
    public static function renderMessageBubble(ConversationMessage $msg, int $currentUserId): string
    {
        $isMe = ((int)$msg->sender_id === (int)$currentUserId);

        // Convert to array for the view
        $item = $msg->toArray();
        $item['is_me'] = $isMe;

        // 🍊 RE-INJECT CARBON OBJECT
        $item['created_at'] = $msg->created_at;

        // 🍊 RESOLVE ATTACHMENT PATH
        $assetBase = getAssetBase();
        if (substr($assetBase, -1) !== '/') $assetBase .= '/';

        if (!empty($msg->attachment_url)) {
            // We resolve the full path here so the view doesn't have to
            $item['attachment_url'] = $assetBase . 'images/uploads/chats/' . ltrim($msg->attachment_url, '/');
        } else {
            $item['attachment_url'] = null;
        }

        ob_start();
        include __DIR__ . '/../../resources/views/components/chats/bubble.php';
        return ob_get_clean();
    }

    /**
     * Render an individual row in the Inbox list
     */
    public static function renderInboxItem(Conversation $convo, int $currentUserId): string
    {
        // 1. Identify the "Other User"
        $otherUser = ($convo->user_one_id === $currentUserId) ? $convo->userTwo : $convo->userOne;

        // 2. Resolve Name
        $displayName = trim(($otherUser->first_name ?? '') . ' ' . ($otherUser->last_name ?? ''));
        if (empty($displayName)) {
            $displayName = $otherUser->name ?? 'Unknown User';
        }

        // 3. Resolve Location (City - Region, Country)
        $city    = $otherUser->city ?? '';
        $region  = $otherUser->region->region ?? '';
        $country = $otherUser->country->country ?? '';

        $location = 'Global Citizen';
        if ($city || $region || $country) {
            $regionCountry = implode(', ', array_filter([$region, $country]));
            $location = $city ? "{$city} - {$regionCountry}" : $regionCountry;
        }

        // 4. Passing variables explicitly to prevent Scope issues
        $assetBase = getAssetBase();
        $avatarUrl = !empty($otherUser->avatar_url)
            ? $assetBase . 'images/uploads/avatars/' . $otherUser->avatar_url
            : null;

        $initial = strtoupper(substr($otherUser->first_name ?? $displayName ?? 'U', 0, 1));

        $item = [
            'encoded_user_id' => IdEncoder::encode((int)$otherUser->id),
            'display_name'    => $displayName,
            'location'        => $location, // Added this
            'avatar_url'      => $avatarUrl,
            'initial'         => $initial,
            'last_snippet'    => $convo->lastMessage->message_text ?? 'No messages yet...',
            'time'            => $convo->last_message_at ? $convo->last_message_at->diffForHumans() : '',
            'unread_count'    => $convo->messages()
                ->where('sender_id', '!=', $currentUserId)
                ->where('is_read', false)
                ->count()
        ];

        ob_start();
        include __DIR__ . '/../../resources/views/components/chats/inbox-row.php';
        return ob_get_clean();
    }
}
