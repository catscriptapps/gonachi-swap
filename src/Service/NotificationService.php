<?php
// /src/Service/NotificationService.php

declare(strict_types=1);

namespace Src\Service;

use App\Models\Advert;
use App\Models\ListingResponse;
use App\Models\QuotationResponse;
use App\Utils\IdEncoder;


/**
 * Class NotificationService
 * Centralized service fro the notifications controller.
 */
class NotificationService
{
    /* --- ADVERT CONTEXT HYDRATION 📢 ---
     * Enriches a notification with all context data for an advert-related notification.
     */
    public static function advertContext($note)
    {
        // 1. Eager load specific relations for performance
        $ad = Advert::with([
            'cta',
            'package',
            'owner.country',
            'owner.region'
        ])->find($note->target_id);

        if ($ad) {
            $owner = $ad->owner;
            $ownerFullName = trim(($owner->first_name ?? '') . ' ' . ($owner->last_name ?? ''));
            $initial = !empty($owner->first_name) ? strtoupper(substr($owner->first_name, 0, 1)) : '?';

            // Preview logic for the notification list
            $note->context_title = $ad->title;
            $packageLabel = $ad->package->package_name ?? 'Standard';
            $note->context_info = "{$packageLabel} Package • " . ucfirst($ad->status);

            // Asset Base for Avatars
            $assetBase = getAssetBase();
            $avatarUrl = ($owner && $owner->avatar) ? $assetBase . $owner->avatar : null;

            // 2. Full Attribute Hydration (Mirrors your JS expected attributes)
            $note->advert_attrs = [
                'encoded-id'                 => \App\Utils\IdEncoder::encode((int)$ad->advert_id),
                'title'                      => $ad->title,
                'description'                => $ad->description,
                'call-to-action-id'          => $ad->call_to_action_id,
                'call-to-action'             => $ad->cta->call_to_action ?? 'Learn More',
                'keywords'                   => $ad->keywords,
                'landing-page-url'           => $ad->landing_page_url,
                'selected-countries'         => json_encode($ad->selected_countries ?? []),
                'country-names'              => json_encode(getAdvertCountryNames($ad)),
                'selected-user-types'        => json_encode($ad->selected_user_types ?? []),
                'user-type-names'            => json_encode(getAdvertUserTypeNames($ad)),
                'advert-package'             => ($ad->package->package_name ?? 'Free') . ' Package',
                'advert-package-description'  => $ad->package->package_description ?? '',
                'advert-package-icon'         => $ad->package->package_icon ?? '',
                'status'                     => $ad->status,
                'joined'                     => $ad->created_at ? $ad->created_at->format('M d, Y') : 'N/A',
                'updated'                    => $ad->updated_at ? $ad->updated_at->format('M d, Y') : 'N/A',

                // Owner Data
                'owner-name'                 => $ownerFullName,
                'owner-avatar'               => $avatarUrl,
                'owner-initial'              => $initial,
                'owner-region'               => $owner->region->region ?? 'N/A',
                'owner-country'              => $owner->country->country ?? 'N/A',
                'owner-id'                   => (int)$ad->orig_user_id,
                'user-types'                 => getUserRoles($owner),
            ];
        }
    }

    /* --- LISTING CONTEXT HYDRATION 🏠 ---
     * Enriches a notification with all context data for a listing-related notification.
     */
    public static function listingContext($note)
    {
        // 1. Eager load only legitimate BelongsTo/HasMany relationships
        $lRes = ListingResponse::with([
            'listing.region',
            'listing.country',
            'listing.unitType',
            'listing.houseType',
            'listing.category',
            'listing.categoryType',
            'listing.bedroom',
            'listing.bathroom',
            'listing.agreementType',
            'listing.user.country',
            'listing.user.region'
        ])->find($note->target_id);

        if ($lRes && $lRes->listing) {
            $listing = $lRes->listing;

            // --- CRITICAL EXISTING LOGIC (Preview Text) ---
            $note->context_title = $listing->listing_title;
            $city = !empty($listing->city) ? $listing->city : 'Local';
            $region = $listing->region->region ?? 'Unknown Region';
            $country = $listing->country->country ?? 'Unknown Country';
            $note->context_info = "{$city} - {$region}, {$country}";

            // --- FULL ATTRIBUTE HYDRATION (For Modal Details) ---
            if ($note->target_status !== 'pending') {
                $owner = $listing->user;
                $ownerFullName = trim(($owner->first_name ?? '') . ' ' . ($owner->last_name ?? ''));
                $initial = !empty($owner->first_name) ? strtoupper(substr($owner->first_name, 0, 1)) : '?';

                $assetBase = getAssetBase();
                $avatarUrl = ($owner && $owner->avatar) ? $assetBase . $owner->avatar : null;

                // Handle Amenities via the Model Helper
                $amenityModels = $listing->getAmenityModels();
                $amenityIds = $listing->amenities ?? []; // This is the casted array from the DB

                $note->listing_attrs = [
                    'encoded-id'          => IdEncoder::encode((int)$listing->listing_id),
                    'listing-title'       => $listing->listing_title,
                    'listing-description' => $listing->listing_description,
                    'city'                => $listing->city,
                    'address'             => $listing->address,

                    // Geography
                    'country-id'          => $listing->country_id,
                    'country-name'        => $listing->country->country ?? '',
                    'region-id'           => $listing->region_id,
                    'region-name'         => $listing->region->region ?? '',

                    // Classification
                    'category-id'         => $listing->category_id,
                    'category-name'       => $listing->category->category ?? 'General',
                    'category-type-id'    => $listing->category_type_id,
                    'category-type-name'  => $listing->categoryType->category_type ?? '',

                    // Property Specs
                    'unit-type-id'        => $listing->unit_type_id,
                    'unit-type-name'      => $listing->unitType->unit_type ?? '',
                    'house-type-id'       => $listing->house_type_id,
                    'house-type-name'     => $listing->houseType->house_type ?? '',
                    'bedroom-id'          => $listing->bedroom_id,
                    'bedroom-label'       => $listing->bedroom->bedroom ?? '0',
                    'bathroom-id'         => $listing->bathroom_id,
                    'bathroom-label'      => $listing->bathroom->bathroom ?? '0',
                    'property-size'       => $listing->property_size ?? '',

                    'is-ac'               => (int)($listing->is_ac ?? 0),
                    'is-furnished'        => (int)($listing->is_furnished ?? 0),
                    'parking'             => (int)($listing->parking ?? 0),
                    'pets-allowed'        => (int)($listing->pets_allowed ?? 0),

                    // Financials & Logistics
                    'price'               => $listing->price ?? '0',
                    'agreement-type-id'   => $listing->agreement_type_id,
                    'agreement-type-name' => $listing->agreementType->agreement_type ?? 'N/A',
                    'move-in-date'        => $listing->move_in_date ?? '',

                    // Amenities (Using the Model Helper logic)
                    'amenities'           => json_encode($amenityIds),
                    'amenities-collection' => json_encode($amenityModels),

                    // Logistics & Contact
                    'contact-phone'       => $listing->contact_phone ?? '',
                    'youtube-url'         => $listing->youtube_url ?? '',

                    // Owner Data
                    'owner-name'          => $ownerFullName,
                    'owner-avatar'        => $avatarUrl,
                    'owner-initial'       => $initial,
                    'owner-region'        => $owner->region->region ?? 'N/A',
                    'owner-country'       => $owner->country->country ?? 'N/A',
                    'owner-id'            => (int)$listing->orig_user_id,
                    'user-types'          => getUserRoles($owner),

                    // Meta
                    'status-id'           => $listing->status_id ?? 1,
                    'created-at'          => $listing->created_at ? $listing->created_at->format('M d, Y') : 'N/A',
                    'updated-at'          => $listing->updated_at ? $listing->updated_at->format('M d, Y') : ($listing->created_at ? $listing->created_at->format('M d, Y') : 'N/A')
                ];
            }
        }
    }

    /* --- QUOTATION CONTEXT HYDRATION 💎 ---
     * This method is responsible for enriching a notification with all the necessary context data for a quotation-related notification.
     * It performs eager loading of all related models to minimize database queries and attaches a structured array of attributes to the notification object.
     */
    public static function quotationContext($note)
    {
        // Eager load ALL relationships required for the data-card attributes
        $qRes = QuotationResponse::with([
            'quotation.region',
            'quotation.country',
            'quotation.skilledTrade',
            'quotation.contractorType',
            'quotation.unitType',
            'quotation.houseType',
            'quotation.quotationType',
            'quotation.destination',
            'quotation.owner.country',
            'quotation.owner.region'
        ])->find($note->target_id);

        if ($qRes && $qRes->quotation) {
            $quote = $qRes->quotation;
            $note->context_title = $quote->quotation_title;

            $city = !empty($quote->city) ? $quote->city : 'Local';
            $region = $quote->region->region ?? 'Unknown Region';
            $country = $quote->country->country ?? 'Unknown Country';
            $note->context_info = "{$city} - {$region}, {$country}";

            // --- NEW: PHASE 1 HYDRATION 💎 ---
            // If status is not pending, we attach the full attribute set to the note object
            if ($note->target_status !== 'pending') {
                $owner = $quote->owner;
                $ownerFullName = trim(($owner->first_name ?? '') . ' ' . ($owner->last_name ?? ''));
                $initial = !empty($owner->first_name) ? strtoupper(substr($owner->first_name, 0, 1)) : '?';

                // Generate avatar URL (mimicking your standard helper logic)
                $assetBase = getAssetBase();
                $avatarUrl = ($owner && $owner->avatar) ? $assetBase . $owner->avatar : null;

                // Map attributes EXACTLY as expected by your JS Data Attributes list
                $note->quote_attrs = [
                    'encoded-id'           => IdEncoder::encode((int)$quote->quotation_id),
                    'title'                => $quote->quotation_title,
                    'description'          => $quote->description_of_work_to_be_done,
                    'city'                 => $quote->city,
                    'country-id'           => $quote->country_id,
                    'country-name'         => $quote->country->country ?? '',
                    'region-id'            => $quote->region_id,
                    'region-name'          => $quote->region->region ?? '',
                    'contractor-type-id'   => $quote->contractor_type_id,
                    'contractor-type-name' => $quote->contractorType->contractor_type ?? 'Any Contractor',
                    'skilled-trade-id'     => $quote->skilled_trade_id,
                    'skilled-trade-name'   => $quote->skilledTrade->skilled_trade ?? 'General',
                    'unit-type-id'         => $quote->unit_type_id,
                    'unit-type-name'       => $quote->unitType->unit_type ?? '',
                    'house-type-id'        => $quote->house_type_id,
                    'house-type-name'      => $quote->houseType->house_type ?? '',
                    'quotation-type-id'    => $quote->quotation_type_id,
                    'quotation-type-name'  => $quote->quotationType->quotation_type ?? '',
                    'quotation-dest-id'    => $quote->quotation_dest_id,
                    'quotation-dest-name'  => $quote->destination->quotation_dest ?? '',
                    'budget'               => $quote->quotation_budget,
                    'start-date'           => $quote->start_date,
                    'finish-date'          => $quote->finish_date,
                    'start-time'           => $quote->start_time,
                    'finish-time'          => $quote->finish_time,
                    'contact-phone'        => $quote->contact_phone,
                    'youtube-url'          => $quote->youtube_url,
                    'notify'               => $quote->notify ?? 'no',
                    'owner-name'           => $ownerFullName,
                    'owner-avatar'         => $avatarUrl,
                    'owner-initial'        => $initial,
                    'owner-region'         => $owner->region->region ?? 'N/A',
                    'owner-country'        => $owner->country->country ?? 'N/A',
                    'owner-id'             => (int)$quote->orig_user_id,
                    'user-types'           => getUserRoles($owner),
                    'status-id'            => $quote->status_id ?? 1,
                    'created-at'           => $quote->created_at ? $quote->created_at->format('M d, Y') : 'N/A',
                    'updated-at'           => $quote->updated_at ? $quote->updated_at->format('M d, Y') : 'N/A'
                ];
            }
        }
    }
}
