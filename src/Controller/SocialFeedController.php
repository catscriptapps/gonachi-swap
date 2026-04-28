<?php
// /src/Controller/SocialFeedController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\Follow;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;
use Src\Service\AuthService;

class SocialFeedController
{
    use RecentActivityLogger;

    /**
     * Main Feed Index
     */
    public function index(): void
    {
        $currentUserId = (int)AuthService::userId();

        $followedIds = Follow::where('follower_id', $currentUserId)
            ->pluck('following_id')
            ->toArray();

        $relevantUserIds = array_merge($followedIds, [$currentUserId]);

        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->whereIn('orig_user_id', $relevantUserIds)
            ->latest()
            ->paginate(15);

        $html = '';

        foreach ($posts as $key => $post) {
            $html .= self::renderPostCard($post);
        }

        $GLOBALS['feedHtml'] = $html;
        $GLOBALS['title'] = "Social Feed";
    }

    /**
     * Handle saving a new post
     */
    public function save(array $input): array
    {
        $userId = \Src\Service\AuthService::userId();

        // 🍊 Fix: Strip pathing if it was accidentally sent from frontend
        $mediaUrl = $input['media_url'] ?? '';
        if (!empty($mediaUrl)) {
            $mediaUrl = basename($mediaUrl); // Turns "/images/uploads/posts/123.jpg" into "123.jpg"
        }

        $post = \App\Models\Post::create([
            'orig_user_id' => $userId,
            'content'      => $input['content'] ?? '',
            'media_url'    => $mediaUrl, // 🍊 Saved as clean filename
            'media_type'   => $input['media_type'] ?? 'none',
        ]);

        return [
            'success' => true,
            'html' => self::renderPostCard($post),
            'data' => $post
        ];
    }

    /**
     * Toggle a Like
     */
    public function toggleLike($postId): array
    {
        try {
            $userId = (int)AuthService::userId();
            $rawPostId = (is_string($postId) && !is_numeric($postId)) ? IdEncoder::decode($postId) : (int)$postId;

            $existing = PostLike::where('post_id', $rawPostId)
                ->where('orig_user_id', $userId)
                ->first();

            if ($existing) {
                $existing->delete();
                $action = 'unliked';
            } else {
                PostLike::create([
                    'post_id' => $rawPostId,
                    'orig_user_id' => $userId
                ]);
                $action = 'liked';
            }

            return [
                'success' => true,
                'action' => $action,
                'like_count' => PostLike::where('post_id', $rawPostId)->count()
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Fetch comments and stats for the View Post Modal
     */
    public function getDetails($postId): array
    {
        try {
            $rawId = (is_string($postId) && !is_numeric($postId)) ? IdEncoder::decode($postId) : (int)$postId;

            // 🍊 Database-level sorting (More efficient for long threads)
            $post = Post::with(['likes', 'comments' => function ($query) {
                $query->orderBy('created_at', 'asc')->with('user');
            }])->find($rawId);

            if (!$post) throw new \Exception("Post not found.");

            $commentsHtml = '';
            $currentUserId = (int)AuthService::userId();

            // No need for sortBy here anymore since the DB did the work!
            foreach ($post->comments as $comment) {
                $commentsHtml .= $this->renderCommentHtml($comment, $currentUserId);
            }

            return [
                'success' => true,
                'likes' => $post->likes->count(),
                'comments_count' => $post->comments->count(),
                'html' => $commentsHtml ?: '<p id="no-comments-msg" class="text-center text-gray-500 text-xs py-4">No comments yet.</p>',
                // 🍊 Add this toggle so JS knows if it's worth showing the button
                'has_many' => $post->comments->count() > 5
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Add a new comment via AJAX
     */
    public function addComment(array $data): array
    {
        try {
            $userId = (int)AuthService::userId();
            $postIdRaw = $data['post_id'] ?? 0;
            $rawPostId = (is_string($postIdRaw) && !is_numeric($postIdRaw)) ? IdEncoder::decode($postIdRaw) : (int)$postIdRaw;
            $commentText = $data['content'] ?? null;

            if (empty($commentText)) throw new \Exception("Comment cannot be empty.");

            $comment = PostComment::create([
                'post_id'      => $rawPostId,
                'orig_user_id' => $userId,
                'comment_text' => (string)$commentText
            ]);

            return [
                'success' => true,
                'commentHtml' => $this->renderCommentHtml($comment, $userId, true),
                'messages' => ['Comment added!']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Private helper to ensure consistent HTML and correct ID usage 🍊
     */
    private function renderCommentHtml($comment, $currentUserId, $isNew = false): string
    {
        $name = $comment->user->full_name ?? $comment->user->name ?? 'Unknown';
        $avatar = $comment->user->avatar_url ?? null;
        $initials = strtoupper(substr($name, 0, 1));
        $animateClass = $isNew ? 'animate-fade-in-down' : '';

        if ($avatar) {
            $AVATAR_DIR_PREFIX = getAssetBase() . 'images/uploads/avatars/';
            $avatar = htmlspecialchars($AVATAR_DIR_PREFIX . ltrim($avatar, '/'));
        }

        // Orange primary theme for new comments 🍊
        $bgClass = $isNew ? 'bg-primary-50 dark:bg-primary-900/20' : 'bg-gray-50 dark:bg-gray-800';

        // Check ownership for delete button
        $isOwner = ((int)$comment->orig_user_id === (int)$currentUserId);
        $deleteBtn = $isOwner ? "
        <button type='button' class='delete-comment-btn text-gray-400 hover:text-red-500 transition-colors p-1' 
                data-comment-id='{$comment->comment_id}'>
            <svg class='w-3.5 h-3.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />
            </svg>
        </button>" : "";

        return "
        <div class='flex space-x-3 mb-4 last:mb-0 comment-row {$animateClass}' data-id='{$comment->comment_id}'>
            <div class='shrink-0'>
                " . ($avatar
            ? "<img src='{$avatar}' class='h-8 w-8 rounded-full object-cover'>"
            : "<div class='h-8 w-8 rounded-full bg-primary-200 dark:bg-primary-900 flex items-center justify-center text-xs font-bold text-primary-700'>{$initials}</div>") . "
            </div>
            <div class='flex-1 {$bgClass} rounded-2xl px-4 py-2 shadow-sm relative group'>
                <div class='flex justify-between items-center'>
                    <span class='text-xs font-bold text-gray-900 dark:text-white'>" . htmlspecialchars($name) . "</span>
                    <div class='flex items-center space-x-2'>
                        <span class='text-[10px] text-gray-400'>" . ($isNew ? 'Just now' : $comment->created_at->diffForHumans()) . "</span>
                        {$deleteBtn}
                    </div>
                </div>
                <p class='text-sm text-gray-700 dark:text-gray-300 mt-1'>" .
            nl2br(htmlspecialchars((string)$comment->comment_text)) .
            "</p>
            </div>
        </div>";
    }

    /**
     * Delete a comment (Owner only)
     */
    public function deleteComment(array $data): array
    {
        try {
            $userId = (int)AuthService::userId();
            $commentId = $data['comment_id'] ?? null;

            if (!$commentId) throw new \Exception("Comment ID is missing.");

            $comment = PostComment::find($commentId);

            if (!$comment) throw new \Exception("Comment not found.");

            if ((int)$comment->orig_user_id !== $userId) {
                throw new \Exception("Unauthorized: You can only delete your own comments.");
            }

            $comment->delete();

            return [
                'success' => true,
                'messages' => ['Comment deleted successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render individual social feed post card HTML
     */
    public static function renderPostCard(Post $post): string
    {
        $currentUserId = (int)AuthService::userId();
        $assetBase = getAssetBase();

        $data = [
            'encoded_id'    => IdEncoder::encode((int)$post->post_id),
            'author'        => $post->user->full_name ?? 'Unknown User',
            'author_id'     => $post->orig_user_id,
            'author_avatar' => $post->user->avatar_url ?? null,
            'content'       => $post->content,
            'time_ago'      => $post->created_at->diffForHumans(),
            'like_count'    => $post->likes->count(),
            'comment_count' => $post->comments->count(),
            'is_liked'      => $post->likes->contains('orig_user_id', $currentUserId),
            'media_url'     => $post->media_url,
            'media_type'    => $post->media_type,
            'asset_base'    => $assetBase
        ];

        $path = __DIR__ . '/../../resources/views/components/social-feed/post-card.php';

        ob_start();
        try {
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<div class='p-4 bg-red-50 text-red-500'>Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }

    /**
     * Delete a post and its associated media 🍊
     */
    public function deletePost(array $data): array
    {
        try {
            $userId = (int)AuthService::userId();
            $postIdRaw = $data['post_id'] ?? null;

            if (!$postIdRaw) throw new \Exception("Post ID is missing.");

            $postId = (is_string($postIdRaw) && !is_numeric($postIdRaw))
                ? IdEncoder::decode($postIdRaw)
                : (int)$postIdRaw;

            $post = Post::find($postId);

            if (!$post) throw new \Exception("Post not found.");

            if ((int)$post->orig_user_id !== $userId) {
                throw new \Exception("Unauthorized.");
            }

            // 🍊 Smart Storage Cleanup
            if (!empty($post->media_url)) {
                // Determine folder based on type
                $folder = ($post->media_type === 'video') ? 'videos/' : 'images/uploads/posts/';
                $filePath = __DIR__ . '/../../public/' . $folder . ltrim($post->media_url, '/');

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $post->delete();

            return ['success' => true, 'messages' => ['Post removed.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }
}
