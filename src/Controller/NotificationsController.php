<?php
// /src/Controller/NotificationsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Notification;
use App\Models\QuotationResponse;
use App\Models\ListingResponse;
use App\Models\MentorRequest;
use App\Traits\RecentActivityLogger;
use App\Utils\IdEncoder;
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
            Notification::TYPE_MENTOR => [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
                'color' => 'primary',
                'label' => 'Handshake'
            ],
            Notification::TYPE_QUOTATION => [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'color' => 'blue',
                'label' => 'Quotation'
            ],
            Notification::TYPE_LISTING => [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
                'color' => 'emerald', // Unique color for listings
                'label' => 'Listing'
            ],
            Notification::TYPE_ADVERT => [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />',
                'color' => 'red',
                'label' => 'Advert'
            ],
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
                'adverts'    => Notification::TYPE_ADVERT,
                'quotations' => Notification::TYPE_QUOTATION,
                'listings'   => Notification::TYPE_LISTING,
                'mentors'    => Notification::TYPE_MENTOR,
                'system'     => Notification::TYPE_SYSTEM,
            ];
            if (isset($typeMap[$filter])) {
                $query->where('type', $typeMap[$filter]);
            }
        }

        $notifications = $query->get();

        foreach ($notifications as $note) {
            if (!$note->target_id) continue;

            // 1. MENTOR CONTEXT 🤝
            // (Mentor Name, User Type, City - Region, Country)
            if ($note->type === Notification::TYPE_MENTOR || str_contains($note->subject, 'Mentor')) {
                $request = MentorRequest::with(['mentor.targetUserType', 'mentor.region', 'mentor.country', 'mentor.user'])->find($note->target_id);
                if ($request && $request->mentor) {
                    $mentor = $request->mentor;
                    $mUser = $mentor->user;

                    $note->context_title = trim(($mUser->first_name ?? '') . ' ' . ($mUser->last_name ?? '')) ?: 'Expert';
                    $note->target_user_type = $mentor->targetUserType->user_type ?? 'Expert';
                    $note->context_title .= " (as {$note->target_user_type} Mentor)";

                    $city = !empty($mentor->city) ? $mentor->city : 'Remote';
                    $region = $mentor->region->region ?? 'Unknown Region';
                    $country = $mentor->country->country ?? 'Unknown Country';
                    $note->context_info = "{$city} - {$region}, {$country}";
                }
            }

            // 2. QUOTATION CONTEXT 💰
            if ($note->type === Notification::TYPE_QUOTATION || str_contains($note->subject, 'Quotation') || str_contains($note->subject, 'Bid')) {
                NotificationService::quotationContext($note);
            }

            // 3. LISTING CONTEXT 🏠
            // (Listing Title, City - Region, Country)
            if ($note->type === Notification::TYPE_LISTING || str_contains($note->subject, 'Listing') || str_contains($note->subject, 'Inquiry')) {
                NotificationService::listingContext($note);
            }

            // 4. ADVERT CONTEXT 📢
            if ($note->type === Notification::TYPE_ADVERT || str_contains($note->subject, 'Advert')) {
                NotificationService::advertContext($note);
            }
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
