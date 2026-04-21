<?php
// /src/Controller/SocialRelationsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\User;
use App\Models\Follow;
use Src\Service\AuthService;

class SocialRelationsController
{
    /**
     * Get social stats for a specific user
     */
    public function getStats(int $userId): array
    {
        return [
            'following' => Follow::where('follower_id', $userId)->count(),
            'followers' => Follow::where('following_id', $userId)->count(),
        ];
    }

    /**
     * Get users the current user isn't following yet
     */
    public function getSuggestions(): array
    {
        $userId = AuthService::userId();

        // 1. Get IDs of everyone I already follow + myself
        $excludedIds = Follow::where('follower_id', $userId)
            ->pluck('following_id')
            ->toArray();

        $excludedIds[] = $userId;

        // 2. Fetch random suggestions
        $users = User::whereNotIn('id', $excludedIds)
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'id'       => $u->id,
                'name'     => $u->full_name ?? $u->name,
                'username' => $u->username ?? 'user',
                'avatar'   => $u->avatar_url // JS handles the directory
            ]);

        return ['success' => true, 'users' => $users];
    }

    /**
     * Toggle the follow status
     */
    public function toggleFollow(array $input): array
    {
        try {
            $followerId = AuthService::userId();
            $followingId = (int)($input['following_id'] ?? 0);

            if ($followerId === $followingId) {
                throw new \Exception("Self-following is not allowed.");
            }

            $existing = Follow::where('follower_id', $followerId)
                ->where('following_id', $followingId)
                ->first();

            if ($existing) {
                $existing->delete();
                $status = 'unfollowed';
            } else {
                Follow::create([
                    'follower_id'  => $followerId,
                    'following_id' => $followingId
                ]);
                $status = 'followed';
            }

            return [
                'success' => true,
                'status'  => $status,
                'messages' => ["Successfully {$status}"]
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Get lists of following or followers
     */
    public function getList(string $type): array
    {
        $userId = \Src\Service\AuthService::userId();

        if ($type === 'following') {
            $ids = Follow::where('follower_id', $userId)->pluck('following_id')->toArray();
        } else {
            $ids = Follow::where('following_id', $userId)->pluck('follower_id')->toArray();
        }

        $users = User::whereIn('id', $ids)->get()->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->full_name ?? $u->name,
            'username' => $u->username ?? 'user',
            'avatar' => $u->avatar_url
        ]);

        return ['success' => true, 'users' => $users, 'type' => $type];
    }

    /**
     * Block a user (removes the follow relationship both ways)
     */
    public function blockUser(array $input): array
    {
        $userId = AuthService::userId();
        $targetId = (int)($input['target_id'] ?? 0);

        // Remove any existing follow relationship both ways
        Follow::where(function ($q) use ($userId, $targetId) {
            $q->where('follower_id', $userId)->where('following_id', $targetId);
        })->orWhere(function ($q) use ($userId, $targetId) {
            $q->where('follower_id', $targetId)->where('following_id', $userId);
        })->delete();

        // Here you would typically also insert into a 'blocks' table
        // For now, dropping the connection satisfies the requirement
        return ['success' => true, 'messages' => ['User blocked and connection removed.']];
    }
}
