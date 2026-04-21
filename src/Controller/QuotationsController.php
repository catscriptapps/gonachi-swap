<?php
// /src/Controller/QuotationsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Quotation;
use App\Models\ContractorType;
use App\Models\SkilledTrade;
use App\Models\UnitType;
use App\Models\HouseType;
use App\Models\QuotationType;
use App\Models\QuotationDestination;
use App\Models\Country;
use App\Models\Region;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;

class QuotationsController
{
    use RecentActivityLogger;

    /**
     * Deactivates a quotation - Gonachi Style 💎
     */
    public function deactivate($id): array
    {
        try {
            // 1. Resolve the ID
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            // 2. Locate the quotation
            $quotation = Quotation::find($rawId);
            if (!$quotation) {
                return ['success' => false, 'messages' => ['Failed to locate quotation.']];
            }

            // 3. Ownership Check
            $currentUserId = (int)($_SESSION['user_id'] ?? 0);
            if ((int)$quotation->orig_user_id !== $currentUserId) {
                return ['success' => false, 'messages' => ['Unauthorized action.']];
            }

            // 4. Update status to Deactivated (2)
            $quotation->status_id = 2;

            if ($quotation->save()) {
                // Log the activity
                static::logActivity("Deactivated quotation: {$quotation->quotation_title}", 'Quotations');

                // Ensure we have the latest data and relationships for the card render
                $quote = $quotation->fresh([
                    'owner.country',
                    'owner.region',
                    'contractorType',
                    'skilledTrade',
                    'country',
                    'region',
                    'unitType',
                    'houseType',
                    'quotationType',
                    'destination',
                    'pictures'
                ]);

                return [
                    'success' => true,
                    'messages' => ['Quotation deactivated successfully.'],
                    // Return fresh HTML using the quotation-specific render method
                    'cardHtml' => self::renderCard($quote)
                ];
            }

            return ['success' => false, 'messages' => ['Could not update status.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Reactivates a quotation - Gonachi Style 💎
     */
    public function reactivate($id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $quotation = Quotation::find($rawId);

            if (!$quotation || (int)$quotation->orig_user_id !== (int)($_SESSION['user_id'] ?? 0)) {
                return ['success' => false, 'messages' => ['Unauthorized or not found.']];
            }

            $quotation->status_id = 1; // Back to Active 💎
            if ($quotation->save()) {
                static::logActivity("Reactivated quotation: {$quotation->quotation_title}", 'Quotations');

                // Ensure we have the latest data and relationships for the card render
                $quote = $quotation->fresh([
                    'owner.country',
                    'owner.region',
                    'contractorType',
                    'skilledTrade',
                    'country',
                    'region',
                    'unitType',
                    'houseType',
                    'quotationType',
                    'destination',
                    'pictures'
                ]);

                return [
                    'success' => true,
                    'messages' => ['Quotation reactivated successfully.'],
                    'cardHtml' => self::renderCard($quote)
                ];
            }
            return ['success' => false, 'messages' => ['Failed to reactivate.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Handle Delete
     */
    public function delete($id): array
    {
        try {
            // Decode if it's an encoded string, otherwise cast to int
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            // 1. Find the model instance
            $quote = Quotation::find($rawId);

            if ($quote) {
                $title = $quote->quotation_title;

                /** * 2. CRITICAL CHANGE: 
                 * Do NOT call $quote->pictures()->delete() here.
                 * * Simply calling $quote->delete() triggers the 'deleting' 
                 * hook we wrote in Quotation.php, which safely iterates 
                 * through the pictures and unlinks the files.
                 */
                if ($quote->delete()) {
                    static::logActivity("Deleted quotation: {$title}", 'Quotations');
                    return ['success' => true, 'messages' => ['Quotation deleted successfully.']];
                }
            }

            return ['success' => false, 'messages' => ['Failed to locate quotation for deletion.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    public static function incrementView(string $encodedId): ?int
    {
        try {
            $id = IdEncoder::decode($encodedId);
            $quote = Quotation::find($id);
            if (!$quote) return null;

            // Skip if owner
            if ((int)$quote->orig_user_id === (int)($_SESSION['user_id'] ?? 0)) {
                return (int)$quote->views;
            }

            $quote->increment('views');
            return (int)$quote->views;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Prepare data for the Quotations Page
     * @param bool $all If true, fetches all quotations. If false, fetches only current user's.
     */
    public function index(bool $all = false): void
    {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);

        $query = $_GET['q'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $userId = (int)($_SESSION['user_id'] ?? 0);

        // 1. Initialize Builder with Eager Loading
        $builder = Quotation::with([
            'owner.country',
            'owner.region',
            'country',
            'region',
            'contractorType',
            'skilledTrade',
            'unitType',
            'houseType',
            'quotationType',
            'destination',
            'pictures',
        ]);

        // 2. Toggle filter: Only filter by User ID if we aren't requesting "All"
        if (!$all) {
            $builder->where('orig_user_id', $userId);
        }

        // 3. Handle Search Query
        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';

                // 1. Search Local Columns
                $q->where('quotation_title', 'LIKE', $term)
                    ->orWhere('city', 'LIKE', $term)
                    ->orWhere('description_of_work_to_be_done', 'LIKE', $term);

                // 2. Search Contractor Type Relationship
                $q->orWhereHas('contractorType', function ($rel) use ($term) {
                    $rel->where('contractor_type', 'LIKE', $term);
                });

                // 3. Search Skilled Trade Relationship
                $q->orWhereHas('skilledTrade', function ($rel) use ($term) {
                    $rel->where('skilled_trade', 'LIKE', $term);
                });

                // 4. Search Unit Type Relationship
                $q->orWhereHas('unitType', function ($rel) use ($term) {
                    $rel->where('unit_type', 'LIKE', $term);
                });

                // 5. Search House Type Relationship
                $q->orWhereHas('houseType', function ($rel) use ($term) {
                    $rel->where('house_type', 'LIKE', $term);
                });

                // 6. Search Country & Region
                $q->orWhereHas('country', function ($rel) use ($term) {
                    $rel->where('country', 'LIKE', $term);
                });

                $q->orWhereHas('region', function ($rel) use ($term) {
                    $rel->where('region', 'LIKE', $term);
                });
            });
        }

        $totalFiltered = $builder->count();
        $quotes = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // 4. AJAX / Infinite Scroll Response
        if (isset($_GET['page']) || isset($_GET['q'])) {
            header('Content-Type: application/json');
            $html = '';
            foreach ($quotes as $quote) {
                $html .= self::renderCard($quote);
            }

            echo json_encode([
                'success' => true,
                'html'    => $html,
                'total'   => $totalFiltered,
                'hasMore' => ($offset + $quotes->count()) < $totalFiltered
            ]);
            exit;
        }

        // 5. Standard Page Load
        $html = '';
        foreach ($quotes as $quote) {
            $html .= self::renderCard($quote);
        }

        // Fetch lookup data for Modals
        $GLOBALS['contractorTypes'] = ContractorType::orderBy('contractor_type', 'asc')->get()->toArray();
        $GLOBALS['skilledTrades']   = SkilledTrade::orderBy('skilled_trade', 'asc')->get()->toArray();
        $GLOBALS['unitTypes']       = UnitType::orderBy('unit_type', 'asc')->get()->toArray();
        $GLOBALS['houseTypes']      = HouseType::orderBy('house_type', 'asc')->get()->toArray();
        $GLOBALS['quoteTypes']      = QuotationType::orderBy('quotation_type', 'asc')->get()->toArray();
        $GLOBALS['destinations']    = QuotationDestination::orderBy('quotation_dest', 'asc')->get()->toArray();
        $GLOBALS['countries']       = Country::orderBy('country', 'asc')->get()->toArray();

        $GLOBALS['quotationCards']  = $html;
        $GLOBALS['totalCount']      = $totalFiltered;

        // Context-aware title
        $GLOBALS['title']           = $all ? "All Quotations" : "My Quotations";
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
            $quote = $id ? Quotation::find($id) : new Quotation();

            if (!$quote) throw new \Exception("Quotation not found.");

            $quote->orig_user_id   = (int)($_SESSION['user_id'] ?? 1);
            $quote->quotation_title = trim($data['quotation_title'] ?? '');
            $quote->description_of_work_to_be_done = trim($data['description_of_work_to_be_done'] ?? '');

            // Mapping Foreign Keys
            $quote->contractor_type_id = (int)($data['contractor_type_id'] ?? 0);
            $quote->skilled_trade_id   = (int)($data['skilled_trade_id'] ?? 0);
            $quote->unit_type_id       = (int)($data['unit_type_id'] ?? 0);
            $quote->house_type_id      = ((int)$data['unit_type_id'] === 5) ? (int)($data['house_type_id'] ?? 0) : null;
            $quote->quotation_type_id  = (int)($data['quotation_type_id'] ?? 0);
            $quote->quotation_dest_id  = (int)($data['quotation_dest_id'] ?? 0);
            $quote->country_id         = (int)($data['country_id'] ?? 1);
            $quote->region_id          = (int)($data['region_id'] ?? 0);

            $quote->city             = trim($data['city'] ?? '');
            $quote->start_date       = !empty($data['start_date']) ? $data['start_date'] : null;
            $quote->finish_date      = !empty($data['finish_date']) ? $data['finish_date'] : null;
            $quote->start_time       = !empty($data['start_time']) ? $data['start_time'] : null;
            $quote->finish_time      = !empty($data['finish_time']) ? $data['finish_time'] : null;
            $quote->quotation_budget = $data['quotation_budget'] ?? null;
            $quote->youtube_url      = $data['youtube_url'] ?? null;
            $quote->contact_phone    = $data['contact_phone'] ?? null;
            $quote->notify           = $data['notify'] ?? 'no';

            if ($isNew) {
                $quote->status_id = 1;
                $quote->views = 0;
            }

            if (empty($quote->quotation_title)) throw new \Exception("Title is required.");

            $quote->save();

            // Ensure we have the latest data and relationships for the card render
            $quote = $quote->fresh([
                'owner.country',
                'owner.region',
                'contractorType',
                'skilledTrade',
                'country',
                'region',
                'unitType',
                'houseType',
                'quotationType',
                'destination',
                'pictures' // <--- ADD THIS 💎
            ]);

            $actionLabel = $isNew ? "Posted new quotation" : "Updated quotation";
            static::logActivity("{$actionLabel}: {$quote->quotation_title}", 'Quotations');

            return [
                'success'  => true,
                'cardHtml' => self::renderCard($quote),
                'messages' => ['Quotation saved successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render individual Quotation Card
     */
    public static function renderCard(Quotation $quote): string
    {
        $item = $quote->toArray();
        $item['encoded_id'] = IdEncoder::encode((int)$quote->quotation_id);

        // Formatted dates for the UI
        $item['created_at_formatted'] = $quote->created_at ? $quote->created_at->format('M d, Y') : 'N/A';

        // Resolve labels using relationships
        $item['trade_label']      = $quote->skilledTrade->skilled_trade ?? 'General';
        $item['contractor_label'] = $quote->contractorType->contractor_type ?? 'Any Contractor';
        $item['country_name']     = $quote->country->country ?? '';
        $item['region_name']      = $quote->region->region ?? '';
        $item['unit_label']       = $quote->unitType->unit_type ?? '';
        $item['house_label']      = $quote->houseType->house_type ?? '';
        $item['type_label']       = $quote->quotationType->quotation_type ?? '';
        $item['dest_label']       = $quote->destination->quotation_dest ?? '';

        // 💎 Fetch the first picture for the Advert
        $firstPic = $quote->pictures()->orderBy('pos_index', 'asc')->first();
        $item['thumbnail'] = $firstPic ? $firstPic->pic_name : null;

        // Mirroring UsersController: Set the global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // Pass the owner object directly
        $owner = $quote->owner;
        $item['owner'] = $owner ? $owner->toArray() : null;

        // Geography
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';
        $item['owner_location'] = ($item['owner_region'] ?? 'Unknown Region') . ', ' . ($item['owner_country'] ?? 'Unknown Country');

        // Roles Mapping
        $item['user_types_json'] = getUserRoles($owner);

        $path = __DIR__ . '/../../resources/views/components/quotations/data-card.php';

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
