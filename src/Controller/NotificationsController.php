<?php
// /src/Controller/NotificationsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Notification;
use App\Models\MentorRequest;
use App\Traits\RecentActivityLogger;
use Src\Service\AuthService;
use Src\Service\NotificationService;
use Illuminate\Database\Eloquent\Collection;

class NotificationsController
{
    use RecentActivityLogger;

    /**
     * Create a new notification 🔔
     */
    public static function create(array $data): Notification
    {
        return Notification::create([
            'receiver_id'          => $data['receiver_id'],
            'sender_id'            => $data['sender_id'] ?? null,
            'type'                 => $data['type'] ?? Notification::TYPE_SYSTEM,
            'target_id'            => $data['target_id'] ?? null,
            'target_status'        => $data['target_status'] ?? 'pending',
            'subject'              => $data['subject'] ?? 'New Notification',
            'notification_message' => $data['notification_message'] ?? $data['message'] ?? '',
            'is_read'              => false
        ]);
    }

    /**
     * Get the icon and metadata based on Model Type Constant
     */
    public static function getMetadata(string $type): array
    {
        return match ($type) {
            Notification::TYPE_SYSTEM => [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />',
                'color' => 'secondary',
                'label' => 'System'
            ],
            default => [
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'color' => 'gray',
                'label' => 'Alert'
            ],
        };
    }

    /**
     * Get unread count for the currently logged-in user
     */
    public static function getUnreadCount(): int
    {
        $userId = AuthService::userId();
        if (!$userId) return 0;

        return (int) Notification::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Fetch latest notifications for a user with optional filtering
     * Bridge the Gap 💎
     */
    public static function getLatest(int $limit = 20): Collection
    {
        $userId = AuthService::userId();
        if (!$userId) return new Collection();

        $filter = $_GET['filter'] ?? 'all';

        $query = Notification::where('receiver_id', $userId)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($filter !== 'all') {
            $typeMap = [
                'system'     => Notification::TYPE_SYSTEM,
            ];
            if (isset($typeMap[$filter])) {
                $query->where('type', $typeMap[$filter]);
            }
        }

        $notifications = $query->get();

        foreach ($notifications as $note) {
            if (!$note->target_id) continue;
        }

        return $notifications;
    }

    public static function markAllAsRead(): bool
    {
        $userId = AuthService::userId();
        if (!$userId) return false;

        return (bool) Notification::where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function delete(int $id): array
    {
        try {
            $notification = Notification::find($id);
            if (!$notification) return ['success' => false, 'messages' => ['Not found.']];
            $notification->delete();
            return ['success' => true, 'messages' => ['Deleted.']];
        } catch (\Exception $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    public static function getUnreadBreakdown(): array
    {
        $userId = AuthService::userId();
        if (!$userId) return [];

        return Notification::where('receiver_id', $userId)
            ->where('is_read', false)
            ->groupBy('type')
            ->selectRaw('type, COUNT(*) as count')
            ->pluck('count', 'type')
            ->toArray();
    }
}
