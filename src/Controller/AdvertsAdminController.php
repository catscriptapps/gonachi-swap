<?php
// /src/Controller/AdvertsAdminController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Advert;
use App\Models\Notification; // Added
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;
use Illuminate\Database\Capsule\Manager as Capsule; // Added for transactions

class AdvertsAdminController
{
    use RecentActivityLogger;

    /**
     * Prepare data for the Adverts Admin List Page
     * Supports Status Tabs, Search, and Infinite Scroll
     */
    public function index(): void
    {
        $query  = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? 'all';
        $page   = (int)($_GET['page'] ?? 1);
        $perPage = 100; // Matching Users default
        $offset = ($page - 1) * $perPage;

        // Eager load owner (User), CTA, and Package for the table row
        $builder = Advert::with(['owner', 'cta', 'package']);

        // 1. Filter by Folder Tab Status
        if ($status !== 'all') {
            $builder->where('status', $status);
        }

        // 2. Search Logic (Title, Description, or Owner Name)
        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = "%{$query}%";
                $q->where('title', 'LIKE', $term)
                    ->orWhere('description', 'LIKE', $term)
                    ->orWhereHas('owner', function ($userQuery) use ($term) {
                        $userQuery->where('first_name', 'LIKE', $term)
                            ->orWhere('last_name', 'LIKE', $term);
                    });
            });
        }

        $totalFiltered = $builder->count();

        $adverts = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // AJAX response (Supports search, scroll, AND tab switching)
        if (isset($_GET['q']) || isset($_GET['page']) || isset($_GET['status'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => array_map(fn($ad) => ['rowHtml' => self::renderRow($ad)], $adverts->all()),
                'meta' => [
                    'total'   => $totalFiltered,
                    'loaded'  => $adverts->count(),
                    'hasMore' => ($offset + $adverts->count()) < $totalFiltered
                ]
            ]);
            exit;
        }

        // Standard Page Load
        $html = '';
        foreach ($adverts as $ad) {
            $html .= self::renderRow($ad);
        }

        $GLOBALS['advertRows'] = $html;
        $GLOBALS['title'] = "Adverts Admin";
        $GLOBALS['totalAdvertsCount'] = $totalFiltered;
    }

    /**
     * Render individual Advert Table Row HTML for Admin
     * Logic EXACTLY mirrored from AdvertsController::renderCard
     */
    public static function renderRow(\App\Models\Advert $ad): string
    {
        $item = $ad->toArray();
        $item['cta_text'] = $ad->cta->call_to_action ?? 'Learn More';
        $item['encoded_id'] = \App\Utils\IdEncoder::encode((int)$ad->advert_id);
        $item['advert_package'] = ($ad->package->package_name ?? '') . ' Package';
        $item['advert_package_description'] = $ad->package->package_description ?? '';
        $item['advert_package_icon'] = $ad->package->package_icon ?? '';
        $item['country_names'] = getAdvertCountryNames($ad);
        $item['user_type_names'] = getAdvertUserTypeNames($ad);

        // Add formatted dates
        $item['created_at_formatted'] = $ad->created_at ? $ad->created_at->format('M d, Y') : 'N/A';
        $item['updated_at_formatted'] = $ad->updated_at ? $ad->updated_at->format('M d, Y') : 'N/A';

        // Set Global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // Owner Logic
        $owner = $ad->owner;
        $item['owner'] = $owner ? $owner->toArray() : null;
        $item['user_types_json'] = getUserRoles($owner); // Using your helper function

        // Geography
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';

        // Load the Admin Table Row view instead of the Card view
        $path = __DIR__ . '/../../resources/views/components/adverts/data-row.php';

        ob_start();
        try {
            // Explicitly defining variables for the include scope
            $assetBase = getAssetBase();
            $rowItem = $item; // Consistent naming with your Users row
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<tr><td colspan='6' class='p-4 text-red-500'>Row Render Error: " . $e->getMessage() . "</td></tr>";
        }
        return ob_get_clean();
    }

    /**
     * Admin action to update advert status (Approve/Reject/Deactivate)
     */
    public function updateStatus(array $data): array
    {
        try {
            // 1. Ensure we have a valid admin session
            $currentUserId = $_SESSION['user_id'] ?? null;
            if (!$currentUserId) throw new \Exception("Login required.");

            // Support both 'id' (from JS) and 'encoded_id'
            $rawId     = $data['id'] ?? null;
            $encodedId = $data['encoded_id'] ?? null;
            $newStatus = $data['status'] ?? null;

            if ((!$rawId && !$encodedId) || !$newStatus) {
                throw new \Exception("Missing required data (ID or Status).");
            }

            // Determine the actual database ID
            $adId = is_numeric($rawId) ? (int)$rawId : \App\Utils\IdEncoder::decode($rawId);

            // Eager load owner to ensure we have the receiver_id
            $ad = Advert::with('owner')->find($adId);

            if (!$ad) {
                throw new \Exception("Advert not found (ID: {$adId}).");
            }

            $oldStatus = $ad->status;

            // Execute status change and notification inside a transaction 💎
            Capsule::transaction(function () use ($ad, $newStatus, $oldStatus, $currentUserId) {
                $ad->status = $newStatus;
                $ad->save();

                $subject = "Advert Status Update";
                $messageToOwner = "Your advert '{$ad->title}' status has been updated from " . ucfirst($oldStatus) . " to " . ucfirst($newStatus) . ".";
                $messageToAdmin = "You updated the status of '{$ad->title}' to " . ucfirst($newStatus) . ".";

                // 1. Notify Advert Owner 
                NotificationsController::create([
                    'receiver_id'          => $ad->orig_user_id, // Target owner
                    'sender_id'            => $currentUserId,    // Admin performing action
                    'type'                 => Notification::TYPE_ADVERT, // Corrected Type 💎
                    'target_id'            => $ad->advert_id,
                    'target_status'        => $newStatus,
                    'subject'              => $subject,
                    'notification_message' => $messageToOwner,
                    'is_read'              => 0
                ]);

                // 2. Notify Admin (Self)
                NotificationsController::create([
                    'receiver_id'          => $currentUserId,    // Admin receiving log
                    'sender_id'            => $currentUserId,
                    'type'                 => Notification::TYPE_ADVERT, // Corrected Type 💎
                    'target_id'            => $ad->advert_id,
                    'target_status'        => $newStatus,
                    'subject'              => $subject,
                    'notification_message' => $messageToAdmin,
                    'is_read'              => 0
                ]);

                static::logActivity(
                    "Updated advert status: '{$ad->title}' from {$oldStatus} to {$newStatus}",
                    'Adverts Admin'
                );
            });

            return [
                'success'  => true,
                'id'       => $adId,
                'messages' => ["Advert status updated to " . ucfirst($newStatus)],
                'rowHtml'  => self::renderRow($ad),
                'status'   => $newStatus
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }
}
