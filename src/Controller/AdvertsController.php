<?php
// /src/Controller/AdvertsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Advert;
use App\Models\AdvertCallToAction;
use Src\Service\AuthService; // Added
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;

class AdvertsController
{
    use RecentActivityLogger;

    public static function incrementView(string $encodedId): ?int
    {
        try {
            $id = IdEncoder::decode($encodedId);
            $ad = Advert::find($id);
            if (!$ad) return null;

            // Skip if owner
            if ((int)$ad->orig_user_id === (int)($_SESSION['user_id'] ?? 0)) {
                return (int)$ad->views;
            }

            $ad->increment('views');
            return (int)$ad->views;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Prepare data for the Adverts Pages
     * @param bool $all If true, fetches targeted active ads for the public. If false, fetches only user's ads.
     */
    public function index(bool $all = false): void
    {
        $query = $_GET['q'] ?? '';
        $page  = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        $offset  = ($page - 1) * $perPage;

        // New parameters for Admin Infinite Scroll & Tabs
        $status = $_GET['status'] ?? 'all';
        $isAdminView = isset($_GET['admin_view']) && $_GET['admin_view'] === 'true';

        $user = AuthService::currentUser();

        // --- Safety Guard: User MUST be signed in ---
        if ($user) {

            // --- BRANCH A: ADMIN AUDIT VIEW ---
            if ($isAdminView && $user->is_admin) {
                $builder = Advert::with(['owner', 'cta', 'package']);

                // Filter by Tab Status
                if ($status !== 'all') {
                    $builder->where('status', $status);
                }

                // Admin Search: Title or Owner Name
                if (!empty($query)) {
                    $builder->where(function ($q) use ($query) {
                        $term = '%' . trim($query) . '%';
                        $q->where('title', 'LIKE', $term)
                            ->orWhereHas('owner', function ($sq) use ($term) {
                                $sq->where('first_name', 'LIKE', $term)
                                    ->orWhere('last_name', 'LIKE', $term);
                            });
                    });
                }

                $totalFiltered = $builder->count();
                $ads = $builder->orderBy('created_at', 'desc')
                    ->offset($offset)
                    ->limit($perPage)
                    ->get();

                // AJAX response for Admin Table
                if (isset($_GET['page']) || isset($_GET['q'])) {
                    header('Content-Type: application/json');
                    $html = '';
                    foreach ($ads as $ad) {
                        $html .= self::renderAdminRow($ad);
                    }
                    echo json_encode([
                        'success' => true,
                        'html'    => $html,
                        'total'   => $totalFiltered,
                        'hasMore' => ($offset + $ads->count()) < $totalFiltered
                    ]);
                    exit;
                }
            }
            // --- BRANCH B: STANDARD USER / PUBLIC VIEW ---
            else {
                // Extract targets from the User Model
                $currentUserId = (int)($user->id ?? 0);
                // 'country_id' is cast to int, we'll stringify it for comparison with the array
                $userCountryId = (string)$user->country_id;
                // 'user_type_ids' is automatically cast to an array by the Model
                $userTypeIds = is_array($user->user_type_ids) ? $user->user_type_ids : [];

                $builder = Advert::with(['owner', 'cta', 'package', 'pictures']);

                if (!$all) {
                    // "My Adverts" mode: Strict ownership
                    $builder->where('orig_user_id', $currentUserId);
                } else {
                    // "Browse Adverts" mode: Active status only at DB level
                    $builder->where('status', Advert::STATUS_ACTIVE);
                }

                // Apply Search Filter at DB level for performance
                if (!empty($query)) {
                    $builder->where(function ($q) use ($query) {
                        $term = '%' . trim($query) . '%';
                        $q->where('title', 'LIKE', $term)
                            ->orWhere('description', 'LIKE', $term)
                            ->orWhere('keywords', 'LIKE', $term);
                    });
                }

                // Fetch the collection
                $collection = $builder->orderBy('created_at', 'desc')->get();

                // If in "Browse" mode, apply the same targeting logic from SocialFeedController
                if ($all) {
                    $collection = $collection->filter(function ($ad) use ($userCountryId, $userTypeIds) {
                        // --- Geography Targeting ---
                        $countries = (array)($ad->selected_countries ?? []);
                        $countryPass = in_array('ALL', $countries) || in_array($userCountryId, $countries);
                        if (!$countryPass) return false;

                        // --- Audience Type Targeting ---
                        $targetTypes = (array)($ad->selected_user_types ?? []);
                        $typePass = in_array('ALL', $targetTypes) ||
                            !empty(array_intersect($userTypeIds, $targetTypes));

                        return $typePass;
                    });
                }

                $totalFiltered = $collection->count();

                // Manually slice the collection for pagination
                $ads = $collection->slice($offset, $perPage);
            }
        } else {
            // 1. Get current user ID (Don't show other people's ads!)
            $userId = (int)($_SESSION['user_id'] ?? 0);

            // 2. Load BOTH user and cta relationships
            $builder = Advert::where('orig_user_id', $userId)->with(['owner', 'cta']);

            if (!empty($query)) {
                $builder->where(function ($q) use ($query) {
                    $term = '%' . trim($query) . '%';
                    // Using whereRaw to bypass any Eloquent character escaping issues
                    $q->where('title', 'LIKE', $term)
                        ->orWhere('description', 'LIKE', $term)
                        ->orWhere('keywords', 'LIKE', $term);
                });
            }

            $totalFiltered = $builder->count();

            $ads = $builder->orderBy('created_at', 'desc')
                ->offset($offset)
                ->limit($perPage)
                ->get();
        }

        // AJAX response (for search or infinite scroll - Non-Admin)
        if (!$isAdminView && (isset($_GET['page']) || isset($_GET['q']))) {
            header('Content-Type: application/json');
            $html = '';
            foreach ($ads as $ad) {
                $html .= self::renderCard($ad);
            }
            echo json_encode([
                'success' => true,
                'html'    => $html,
                'total'   => $totalFiltered,
                'hasMore' => ($offset + $ads->count()) < $totalFiltered
            ]);
            exit;
        }

        // Standard Page Load
        $html = '';
        foreach ($ads as $ad) {
            $html .= $isAdminView ? self::renderAdminRow($ad) : self::renderCard($ad);
        }

        // Fetch lookup data for the "Post an Ad" Modal
        $ctas = \App\Models\AdvertCallToAction::orderBy('call_to_action', 'asc')->get();

        $GLOBALS['availableCtas'] = $ctas->toArray();
        $GLOBALS['advertCards']   = $html;
        $GLOBALS['totalAdsCount'] = $totalFiltered;
        $GLOBALS['title']         = $all ? "Browse Adverts" : "My Adverts";
    }

    /**
     * Handle Create or Update
     */
    public function save(array $data): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            $isNew = empty($encodedId);

            $adId = !$isNew ? IdEncoder::decode($encodedId) : null;
            $ad = $adId ? Advert::find($adId) : new Advert();

            if (!$ad) throw new \Exception("Advert not found.");

            $ad->orig_user_id       = (int)($_SESSION['user_id'] ?? 1);
            $ad->title              = trim($data['title'] ?? '');
            $ad->description        = trim($data['description'] ?? '');
            $ad->call_to_action_id  = !empty($data['call_to_action_id']) ? (int)$data['call_to_action_id'] : null;
            $ad->keywords           = $data['keywords'] ?? null;
            $ad->landing_page_url   = $data['landing_page_url'] ?? null;

            /**
             * TARGETING REPAIR:
             * JS sends arrays. Model casts them. 
             * No need to json_decode unless the JS sends them as strings.
             */
            $ad->selected_countries  = $data['selected_countries'] ?? [];
            $ad->selected_user_types = $data['selected_user_types'] ?? [];

            $ad->advert_package = (int)($data['advert_package'] ?? 0);

            if ($isNew) {
                $ad->status = 'pending';
                $ad->views = 0;
            }

            if (empty($ad->title)) throw new \Exception("The advert title is required.");

            $ad->save();

            $actionLabel = $isNew ? "Created new advert" : "Updated advert";
            static::logActivity("{$actionLabel}: {$ad->title}", 'Adverts');

            return [
                'success' => true,
                'data'    => $ad->toArray(),
                'cardHtml' => self::renderCard($ad),
                'messages' => ['Advert saved successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render individual Advert Card HTML
     */
    public static function renderCard(Advert $ad): string
    {
        $item = $ad->toArray();
        $item['cta_text'] = $ad->cta->call_to_action ?? 'Learn More';
        $item['encoded_id'] = IdEncoder::encode((int)$ad->advert_id);
        $item['advert_package'] = $ad->package->package_name . ' Package' ?? '';
        $item['advert_package_description'] = $ad->package->package_description ?? '';
        $item['advert_package_icon'] = $ad->package->package_icon ?? '';
        $item['country_names'] = getAdvertCountryNames($ad);
        $item['user_type_names'] = getAdvertUserTypeNames($ad);

        // Add formatted dates
        $item['created_at_formatted'] = $ad->created_at ? $ad->created_at->format('M d, Y') : 'N/A';
        $item['updated_at_formatted'] = $ad->updated_at ? $ad->updated_at->format('M d, Y') : 'N/A';

        // 💎 Fetch the first picture for the Advert
        $firstPic = $ad->pictures()->orderBy('pos_index', 'asc')->first();
        $item['thumbnail'] = $firstPic ? $firstPic->pic_name : null;

        // Mirroring UsersController: Set the global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // Pass the owner object directly so the view can handle the avatar logic
        $owner = $ad->owner;
        $item['owner'] = $owner ? $owner->toArray() : null;
        $item['user_types_json'] = getUserRoles($owner);

        // Geography
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';

        $path = __DIR__ . '/../../resources/views/components/adverts/data-card.php';

        ob_start();
        try {
            $assetBase = getAssetBase();
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<div class='p-4 border border-red-200 text-red-500'>Render Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }

    /**
     * Render a Table Row for the Admin Audit Trail
     */
    public static function renderAdminRow(Advert $ad): string
    {
        $item = $ad->toArray();
        $item['encoded_id'] = IdEncoder::encode((int)$ad->advert_id);
        $item['owner_name'] = ($ad->owner->first_name ?? '') . ' ' . ($ad->owner->last_name ?? '');
        $item['created_at_formatted'] = $ad->created_at ? $ad->created_at->format('M d, Y') : 'N/A';

        $path = __DIR__ . '/../../resources/views/components/adverts/admin-row.php';

        ob_start();
        try {
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<tr><td colspan='5'>Render Error: " . $e->getMessage() . "</td></tr>";
        }
        return ob_get_clean();
    }

    /**
     * Handle Delete - Corrected & Confirmed
     */
    public function delete($id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $ad = Advert::find($rawId);

            if ($ad) {
                $title = $ad->title;

                // This triggers Advert::booted() -> deleting event
                if ($ad->delete()) {
                    static::logActivity("Deleted advert: {$title}", 'Adverts');
                    return ['success' => true, 'messages' => ['Ad deleted successfully.']];
                }
            }
            return ['success' => false, 'messages' => ['Failed to delete advert.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }
}
