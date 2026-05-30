<?php
// /src/Controller/ListingResponsesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\ListingResponse;
use App\Models\Notification;
use App\Models\User;
use App\Utils\IdEncoder;
use Illuminate\Database\Capsule\Manager as Capsule;

class ListingResponsesController
{
    public function acceptInquiry(array $data): array
    {
        return $this->processHandshake($data, ListingResponse::STATUS_ACCEPTED);
    }

    public function declineInquiry(array $data): array
    {
        return $this->processHandshake($data, ListingResponse::STATUS_DECLINED);
    }

    private function processHandshake(array $data, string $newStatus): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) return ['success' => false, 'message' => 'Login required.', 'status' => 401];

        try {
            $notificationId = (int)($data['notification_id'] ?? 0);
            $ownerMessage = $data['message'] ?? '';

            $originalNotification = Notification::find($notificationId);
            if (!$originalNotification) return ['success' => false, 'message' => 'Notification not found.', 'status' => 404];

            $response = ListingResponse::with(['sender', 'listing.user'])->find($originalNotification->target_id);
            if (!$response) return ['success' => false, 'message' => 'Inquiry record not found.', 'status' => 404];

            if ((int)$originalNotification->receiver_id !== (int)$currentUserId) {
                return ['success' => false, 'message' => 'Unauthorized action.', 'status' => 403];
            }

            $verb = ($newStatus === ListingResponse::STATUS_ACCEPTED) ? 'accepted' : 'declined';

            Capsule::transaction(function () use ($response, $originalNotification, $newStatus, $ownerMessage, $verb) {
                $response->update(['status' => $newStatus]);
                $originalNotification->update(['is_read' => 1, 'target_status' => $newStatus]);

                $sender = User::with(['region', 'country'])->find($response->sender_id);
                $owner = User::with(['region', 'country'])->find($originalNotification->receiver_id);

                $loc = function ($u) {
                    return "{$u->full_name} - {$u->city}, " . ($u->region->region ?? 'Unknown');
                };

                // Notify Owner
                NotificationsController::create([
                    'receiver_id' => $owner->id,
                    'sender_id' => $sender->id,
                    'type' => Notification::TYPE_SYSTEM,
                    'target_id' => $response->id,
                    'target_status' => $newStatus,
                    'subject' => "Listing Inquiry " . ucfirst($verb),
                    'notification_message' => "You {$verb} inquiry from " . $loc($sender),
                ]);

                // Notify Inquirer
                NotificationsController::create([
                    'receiver_id' => $sender->id,
                    'sender_id' => $owner->id,
                    'type' => Notification::TYPE_SYSTEM,
                    'target_id' => $response->id,
                    'target_status' => $newStatus,
                    'subject' => "Inquiry " . ucfirst($verb),
                    'notification_message' => $loc($owner) . " {$verb} your inquiry. " . ($ownerMessage ? "Message: \"{$ownerMessage}\"" : ""),
                ]);
            });

            return ['success' => true, 'message' => "Inquiry {$verb} successfully."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'status' => 500];
        }
    }

    public function sendInquiry(array $data): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) return ['success' => false, 'message' => 'Login required.', 'status' => 401];

        try {
            $rawId = $data['listing_id'] ?? '';
            $message = trim($data['message'] ?? '');
            $receiverId = (int)($data['receiver_id'] ?? 0);

            // 💎 Use your IdEncoder to handle the encoded string
            // If it's already numeric (fallback), cast it; otherwise, decode it.
            $listingId = is_numeric($rawId) ? (int)$rawId : IdEncoder::decode((string)$rawId);

            // 💎 Split validation for clarity
            if (!$listingId) {
                return ['success' => false, 'message' => 'Invalid Listing reference.', 'status' => 400];
            }

            if (empty($message)) {
                return ['success' => false, 'message' => 'Message is required.', 'status' => 400];
            }

            if ((int)$currentUserId === (int)$receiverId) {
                return ['success' => false, 'message' => 'You cannot inquire about your own listing.', 'status' => 400];
            }

            $existing = ListingResponse::where('sender_id', $currentUserId)
                ->where('listing_id', $listingId)
                ->whereIn('status', [ListingResponse::STATUS_PENDING, ListingResponse::STATUS_ACCEPTED])
                ->first();

            if ($existing) {
                return ['success' => false, 'message' => 'Active inquiry already exists.', 'status' => 400];
            }

            $result = Capsule::transaction(function () use ($currentUserId, $listingId, $message, $receiverId) {
                $res = ListingResponse::create([
                    'sender_id' => $currentUserId,
                    'listing_id' => $listingId,
                    'status' => ListingResponse::STATUS_PENDING,
                    'message' => $message,
                ]);

                NotificationsController::create([
                    'receiver_id' => $receiverId,
                    'sender_id' => $currentUserId,
                    'type' => Notification::TYPE_LISTING,
                    'target_id' => $res->id,
                    'subject' => 'New Listing Inquiry',
                    'notification_message' => $message,
                ]);
                return $res;
            });

            return ['success' => true, 'message' => 'Inquiry sent!', 'data' => $result];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'status' => 500];
        }
    }
}
