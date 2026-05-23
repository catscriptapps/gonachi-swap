<?php
// /src/Controller/ListingsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Listing;
use App\Models\ListingCategory;
use App\Models\Country;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;

class ListingsController
{
    use RecentActivityLogger;

    /**
     * Deactivates a listing - Gonachi Style 💎
     */
    public function deactivate(string|int $id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $listing = Listing::find($rawId);

            if (!$listing) {
                return ['success' => false, 'messages' => ['Failed to locate listing.']];
            }

            // Ownership Check
            $currentUserId = (int)($_SESSION['user_id'] ?? 0);
            if ((int)$listing->orig_user_id !== $currentUserId) {
                return ['success' => false, 'messages' => ['Unauthorized action.']];
            }

            $listing->status_id = 2; // Deactivated

            if ($listing->save()) {
                static::logActivity("Ended listing: {$listing->listing_title}", 'Listings');
                return [
                    'success' => true,
                    'messages' => ['Listing ended successfully.'],
                    'cardHtml' => self::renderCard($listing->fresh(['user', 'category', 'pictures']))
                ];
            }

            return ['success' => false, 'messages' => ['Could not update listing status.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Reactivates a listing - Gonachi Style 💎
     */
    public function reactivate(string|int $id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $listing = Listing::find($rawId);

            if (!$listing) {
                return ['success' => false, 'messages' => ['Failed to locate listing.']];
            }

            // Ownership Check
            $currentUserId = (int)($_SESSION['user_id'] ?? 0);
            if ((int)$listing->orig_user_id !== $currentUserId) {
                return ['success' => false, 'messages' => ['Unauthorized action.']];
            }

            $listing->status_id = 1; // Posted/Active

            if ($listing->save()) {
                static::logActivity("Reactivated listing: {$listing->listing_title}", 'Listings');
                return [
                    'success' => true,
                    'messages' => ['Listing is live again!'],
                    'cardHtml' => self::renderCard($listing->fresh(['user', 'category', 'pictures']))
                ];
            }

            return ['success' => false, 'messages' => ['Could not update listing status.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Delete Listing (Triggers physical file removal via Model events)
     */
    public function delete(string|int $id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $listing = Listing::find($rawId);

            if ($listing) {
                $title = $listing->listing_title;
                if ($listing->delete()) {
                    static::logActivity("Deleted listing: {$title}", 'Listings');
                    return ['success' => true, 'messages' => ['Listing deleted successfully.']];
                }
            }
            return ['success' => false, 'messages' => ['Failed to locate listing for deletion.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Increment View Count - Gonachi Style 💎
     */
    public static function incrementView(string $encodedId): ?int
    {
        try {
            // 1. Resolve ID
            $id = IdEncoder::decode($encodedId);

            // 2. Find model
            $listing = Listing::find($id);
            if (!$listing) return null;

            // 3. Ownership Shield: Don't count the owner's own views
            $currentUserId = (int)($_SESSION['user_id'] ?? 0);
            if ((int)$listing->orig_user_id === $currentUserId) {
                return (int)$listing->views;
            }

            // 4. Atomic Increment using the correct field 'views'
            $listing->increment('views');

            // 5. Return fresh count for the UI
            return (int)$listing->views;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Prepare data for the Listings Page
     */
    public function index(bool $all = false): void
    {
        $query = $_GET['q'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12; // Adjusted for grid layouts
        $offset = ($page - 1) * $perPage;
        $userId = (int)($_SESSION['user_id'] ?? 0);

        $builder = Listing::with([
            'user.country',
            'user.region',
            'category',
            'pictures'
        ]);

        if (!$all) {
            $builder->where('orig_user_id', $userId);
        }

        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';
                $q->where('listing_title', 'LIKE', $term)
                    ->orWhere('city', 'LIKE', $term)
                    ->orWhere('listing_description', 'LIKE', $term);
            });
        }

        $totalFiltered = $builder->count();
        $listings = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $html = '';
        foreach ($listings as $listing) {
            $html .= self::renderCard($listing);
        }

        // AJAX response for search/pagination
        if (isset($_GET['page']) || isset($_GET['q'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data'    => array_map(fn($l) => ['cardHtml' => self::renderCard($l)], $listings->all()),
                'meta'    => [
                    'total'   => $totalFiltered,
                    'loaded'  => $listings->count(),
                    'hasMore' => ($offset + $listings->count()) < $totalFiltered
                ]
            ]);
            exit;
        }

        // Standard Page Load globals
        $GLOBALS['listingCategories'] = ListingCategory::orderBy('category_name', 'asc')->get()->toArray();
        $GLOBALS['countries']         = Country::orderBy('country', 'asc')->get()->toArray();
        $GLOBALS['listingCards']      = $html;
        $GLOBALS['title']             = $all ? "Browse Swaps" : "My Listings";
        $GLOBALS['totalCount']        = $totalFiltered;
    }

    /**
     * Handle Create or Update
     */
    public function save(array $data): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            $isNew = empty($encodedId);
            $id = !$isNew ? IdEncoder::decode($encodedId) : null;

            $listing = $id ? Listing::find($id) : new Listing();
            if (!$listing) throw new \Exception("Listing not found.");

            $listing->orig_user_id        = (int)($_SESSION['user_id'] ?? 0);
            $listing->listing_title       = trim($data['listing_title'] ?? '');
            $listing->listing_description = trim($data['listing_description'] ?? '');
            $listing->category_id         = (int)($data['category_id'] ?? 0);
            $listing->type_id             = (int)($data['type_id'] ?? 1);
            $listing->condition_id        = (int)($data['condition_id'] ?? 3);
            $listing->city                = trim($data['city'] ?? '');
            $listing->country_id          = (int)($data['country_id'] ?? 1);
            $listing->region_id           = (int)($data['region_id'] ?? 0);
            $listing->price               = $data['price'] ?? 0.00;
            $listing->trade_pref          = trim($data['trade_pref'] ?? '');
            $listing->contact_phone       = $data['contact_phone'] ?? null;
            $listing->youtube_url         = trim($data['youtube_url'] ?? '');

            if ($isNew) {
                $listing->status_id = 1; // Active
                $listing->views = 0;
            }

            if (empty($listing->listing_title)) throw new \Exception("Title is required.");

            $listing->save();

            $actionLabel = $isNew ? "Posted new listing" : "Updated listing";
            static::logActivity("{$actionLabel}: {$listing->listing_title}", 'Listings');

            return [
                'success'  => true,
                'cardHtml' => self::renderCard($listing->fresh(['user', 'category', 'pictures'])),
                'messages' => ['Listing saved successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render individual Listing Card
     */
    public static function renderCard(Listing $listing): string
    {
        $item = $listing->toArray();
        $item['encoded_id'] = IdEncoder::encode((int)$listing->listing_id);

        // Formatted dates for the UI
        $item['created_at_formatted'] = $listing->created_at ? $listing->created_at->format('M d, Y') : 'N/A';
        $item['updated_at_formatted'] = $listing->updated_at ? $listing->updated_at->format('M d, Y') : $item['created_at_formatted'];

        // Resolve labels using relationships
        $item['category_label']  = $listing->category->category_name ?? 'General';
        $item['category_icon']   = $listing->category->category_icon ?? 'tag';
        $item['type_label']      = $listing->type->type_name ?? 'Swap';
        $item['condition_label'] = $listing->condition->condition_name ?? 'Used';
        $item['country_name']    = $listing->country->country ?? '';
        $item['region_name']     = $listing->region->region ?? '';

        // 💎 Fetch the first picture for the Listing
        $firstPic = $listing->pictures()->orderBy('pos_index', 'asc')->first();
        $item['thumbnail'] = $firstPic ? $firstPic->pic_name : null;

        // Mirroring UsersController: Set the global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // Pass the owner object directly
        $owner = $listing->user;
        $item['user'] = $owner ? $owner->toArray() : null;

        // Owner Geography
        $item['owner_country']  = $owner->country->country ?? 'N/A';
        $item['owner_region']   = $owner->region->region ?? 'N/A';
        $item['owner_location'] = ($item['owner_region'] !== 'N/A' ? $item['owner_region'] : 'Unknown Region') . ', ' . ($item['owner_country'] !== 'N/A' ? $item['owner_country'] : 'Unknown Country');

        // Roles Mapping
        $item['user_types_json'] = getUserRoles($owner);

        // View Path - Using dashes in file names as per your convention
        $path = __DIR__ . '/../../resources/views/components/listings/data-card.php';

        ob_start();
        try {
            $assetBase = getAssetBase();
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<div class='p-4 text-red-500'>Render Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }
}
