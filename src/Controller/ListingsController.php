<?php
// /src/Controller/ListingsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Listing;
use App\Models\ListingCategory;
use App\Models\UnitType;
use App\Models\HouseType;
use App\Models\Bedroom;
use App\Models\Bathroom;
use App\Models\BasementType;
use App\Models\AgreementType;
use App\Models\AvailableParking;
use App\Models\AmenityCategory;
use App\Models\Country;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;

class ListingsController
{
    use RecentActivityLogger;

    /**
     * Deactivates a listing - Gonachi Style 💎
     */
    public function deactivate($id): array
    {
        try {
            // 1. Resolve the ID
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            // 2. Locate the listing
            $listing = Listing::find($rawId);
            if (!$listing) {
                return ['success' => false, 'messages' => ['Failed to locate listing.']];
            }

            // 3. Ownership Check (Matching your save() logic)
            $currentUserId = (int)($_SESSION['user_id'] ?? 0);
            if ((int)$listing->orig_user_id !== $currentUserId) {
                return ['success' => false, 'messages' => ['Unauthorized action.']];
            }

            // 4. Update status (Assuming 2 is the 'Deactivated/Ended' status)
            $listing->status_id = 2;

            if ($listing->save()) {
                // Log the activity using your RecentActivityLogger trait
                static::logActivity("Ended listing: {$listing->listing_title}", 'Listings');

                return [
                    'success' => true,
                    'messages' => ['Listing ended successfully.'],
                    // We return the fresh card HTML so the UI updates automatically
                    'cardHtml' => self::renderCard($listing->fresh(['user', 'category', 'categoryType', 'pictures']))
                ];
            }

            return ['success' => false, 'messages' => ['Could not update listing status.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    public function reactivate($id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? \App\Utils\IdEncoder::decode($id) : (int)$id;
            $listing = Listing::find($rawId);

            if (!$listing || (int)$listing->orig_user_id !== (int)($_SESSION['user_id'] ?? 0)) {
                return ['success' => false, 'messages' => ['Unauthorized or not found.']];
            }

            $listing->status_id = 1; // Back to Active 💎
            if ($listing->save()) {
                static::logActivity("Reactivated listing: {$listing->listing_title}", 'Listings');
                return [
                    'success' => true,
                    'cardHtml' => self::renderCard($listing->fresh(['user', 'category', 'categoryType', 'pictures']))
                ];
            }
            return ['success' => false, 'messages' => ['Failed to reactivate.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Handle Delete - Cleaned for Physical File Removal
     */
    public function delete($id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $listing = Listing::find($rawId);

            if ($listing) {
                $title = $listing->listing_title;

                /**
                 * REMOVED: $listing->pictures()->delete();
                 * * Simply calling delete() on the listing model now triggers the 
                 * cascading chain: Listing -> ListingPic -> unlink(physical_file)
                 */
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
     * @param bool $all If true, fetches all listings. If false, fetches only user's listings.
     */
    public function index(bool $all = false): void
    {
        $query = $_GET['q'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $userId = (int)($_SESSION['user_id'] ?? 0);

        $builder = Listing::with([
            'user.country',
            'user.region',
            'category',
            'categoryType',
            'pictures'
        ]);

        // Only filter by User ID if we aren't requesting "All"
        if (!$all) {
            $builder->where('orig_user_id', $userId);
        }

        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';
                $q->where('listing_title', 'LIKE', $term)
                    ->orWhere('city', 'LIKE', $term)
                    ->orWhere('listing_description', 'LIKE', $term);

                $q->orWhereHas('category', function ($rel) use ($term) {
                    $rel->where('category', 'LIKE', $term);
                });
            });
        }

        $totalFiltered = $builder->count();
        $listings = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // AJAX response (keep this logic for infinite scroll)
        if (isset($_GET['page']) || isset($_GET['q'])) {
            header('Content-Type: application/json');
            $html = '';
            foreach ($listings as $listing) {
                $html .= self::renderCard($listing);
            }
            echo json_encode([
                'success' => true,
                'html'    => $html,
                'total'   => $totalFiltered,
                'hasMore' => ($offset + $listings->count()) < $totalFiltered
            ]);
            exit;
        }

        // Standard Page Load
        $html = '';
        foreach ($listings as $listing) {
            $html .= self::renderCard($listing);
        }

        // Fetch lookup data for the "Post a Listing" Modal
        $GLOBALS['listingCategories'] = ListingCategory::orderBy('category', 'asc')->get()->toArray();
        $GLOBALS['unitTypes']         = UnitType::orderBy('unit_type', 'asc')->get()->toArray();
        $GLOBALS['houseTypes']        = HouseType::orderBy('house_type', 'asc')->get()->toArray();
        $GLOBALS['bedrooms']          = Bedroom::all()->toArray();
        $GLOBALS['bathrooms']         = Bathroom::all()->toArray();
        $GLOBALS['basementTypes']     = BasementType::all()->toArray();
        $GLOBALS['agreementTypes']    = AgreementType::all()->toArray();
        $GLOBALS['parkingOptions']    = AvailableParking::all()->toArray();
        $GLOBALS['amenityGroups']     = AmenityCategory::with('amenities')->get()->toArray();
        $GLOBALS['countries']         = Country::orderBy('country', 'asc')->get()->toArray();

        $GLOBALS['listingCards']      = $html;
        $GLOBALS['title']             = "My Listings";
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

            $listing->orig_user_id        = (int)($_SESSION['user_id'] ?? 1);
            $listing->listing_title       = trim($data['listing_title'] ?? '');
            $listing->listing_description = trim($data['listing_description'] ?? '');
            $listing->category_id         = (int)($data['category_id'] ?? 0);
            $listing->category_type_id    = (int)($data['category_type_id'] ?? 0);
            $listing->unit_type_id        = (int)($data['unit_type_id'] ?? 0);
            $listing->house_type_id       = (int)($data['house_type_id'] ?? null);
            $listing->bedroom_id          = (int)($data['bedroom_id'] ?? 0);
            $listing->bathroom_id         = (int)($data['bathroom_id'] ?? 0);
            $listing->city                = trim($data['city'] ?? '');
            $listing->address             = trim($data['address'] ?? '');
            $listing->country_id          = (int)($data['country_id'] ?? 1);
            $listing->region_id           = (int)($data['region_id'] ?? 0);
            $listing->price               = $data['price'] ?? null;
            $listing->property_size       = $data['property_size'] ?? null;
            $listing->agreement_type_id   = (int)($data['agreement_type_id'] ?? 0);
            $listing->is_ac               = (int)($data['is_ac'] ?? null);
            $listing->is_furnished        = (int)($data['is_furnished'] ?? null);
            $listing->parking             = (int)($data['parking'] ?? null);
            $listing->pets_allowed        = (int)($data['pets_allowed'] ?? null);
            $listing->move_in_date        = $data['move_in_date'] ?? null;
            $listing->youtube_url         = $data['youtube_url'] ?? null;
            $listing->contact_phone       = $data['contact_phone'] ?? null;

            // Handle Amenities JSON Array
            // Assumes incoming data is an array of IDs
            $listing->amenities           = is_array($data['amenities'] ?? null) ? $data['amenities'] : [];

            if ($isNew) {
                $listing->status_id = 1; // Default to Active for new listings
                $listing->views = 0;
            } else {
                // If updating, check if status was passed, else preserve current
                if (isset($data['status_id'])) {
                    $listing->status_id = (int)$data['status_id'];
                }
            }

            if (empty($listing->listing_title)) throw new \Exception("Listing title is required.");

            $listing->save();

            $actionLabel = $isNew ? "Posted new listing" : "Updated listing";
            static::logActivity("{$actionLabel}: {$listing->listing_title}", 'Listings');

            return [
                'success'  => true,
                'cardHtml' => self::renderCard($listing->fresh(['user', 'category', 'categoryType', 'pictures'])),
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

        // Resolve labels using relationships for the data-card attributes
        $item['country_name']     = $listing->country->country ?? '';
        $item['region_name']      = $listing->region->region ?? '';
        $item['unit_label']       = $listing->unitType->unit_type ?? '';
        $item['house_label']      = $listing->houseType->house_type ?? '';

        // --- FILLING REMAINING BLANKS FROM MODEL ---
        $item['category_label']   = $listing->category->category ?? 'General';
        $item['category_type']    = $listing->categoryType->category_type ?? ''; // Added this!
        $item['bedroom_label']    = $listing->bedroom->bedroom ?? '0';
        $item['bathroom_label']   = $listing->bathroom->bathroom ?? '0';
        $item['agreement_label']  = $listing->agreementType->agreement_type ?? 'N/A';

        // 💎 Fetch the first picture using the correct column 'pos_index'
        $firstPic = $listing->pictures()->orderBy('pos_index', 'asc')->first();

        // Use 'pic_name' as defined in your ListingPic model
        $item['thumbnail'] = $firstPic ? $firstPic->pic_name : null;

        // Pass the actual Amenity models (for the logic check in the view)
        $item['amenities_data']   = $listing->getAmenityModels();

        // Mirroring UsersController: Set the global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // Pass the owner object directly so the view can handle the avatar logic
        $owner = $listing->user; // Mapping to the user() relationship in your model
        $item['owner'] = $owner ? $owner->toArray() : null;

        // Geography
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';
        $item['owner_location'] = ($item['owner_region'] ?? 'Unknown Region') . ', ' . ($item['owner_country'] ?? 'Unknown Country');

        // Roles Mapping
        $item['user_types_json'] = getUserRoles($owner);

        $path = __DIR__ . '/../../resources/views/components/listings/data-card.php';

        ob_start();
        try {
            // Passing variables explicitly to prevent Scope issues
            $assetBase = getAssetBase();
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<div class='p-4 text-red-500'>Render Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }
}
