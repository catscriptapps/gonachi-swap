<?php
// /src/Service/NotificationService.php

declare(strict_types=1);

namespace Src\Service;

use App\Models\ListingResponse;
use App\Models\Notification;
use App\Utils\IdEncoder;


/**
 * Class NotificationService
 * Centralized service for the notifications controller.
 */
class NotificationService
{

    /**
     * Enriches a notification with all context data for a listing-related notification.
     *
     * @param Notification $note
     * @return void
     */
    public static function listingContext(Notification $note): void
    {
        // 1. Eager load only legitimate BelongsTo/HasMany relationships from the Listing model
        $lRes = ListingResponse::with([
            'listing.region',
            'listing.country',
            'listing.category',
            'listing.type',
            'listing.condition',
            'listing.pictures',
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

            // Hydrate the first image url if a picture is uploaded
            $firstPic = $listing->pictures->first();
            $note->context_image = $firstPic 
                ? getAssetBase() . 'images/uploads/listings/' . $firstPic->pic_name 
                : '';

            // --- FULL ATTRIBUTE HYDRATION (For Modal Details) ---
            if ($note->target_status !== 'pending') {
                $owner = $listing->user;
                $ownerFullName = trim(($owner->first_name ?? '') . ' ' . ($owner->last_name ?? ''));
                $initial = !empty($owner->first_name) ? strtoupper(substr($owner->first_name, 0, 1)) : '?';

                $assetBase = getAssetBase();
                $avatarUrl = ($owner && $owner->avatar_url) ? $assetBase . 'images/uploads/avatars/' . $owner->avatar_url : null;

                $note->listing_attrs = [
                    'encoded-id'          => IdEncoder::encode((int)$listing->listing_id),
                    'listing-title'       => $listing->listing_title,
                    'listing-description' => $listing->listing_description,
                    'city'                => $listing->city,

                    // Geography
                    'country-id'          => $listing->country_id,
                    'country-name'        => $listing->country->country ?? '',
                    'region-id'           => $listing->region_id,
                    'region-name'         => $listing->region->region ?? '',

                    // Classification
                    'category-id'         => $listing->category_id,
                    'category-name'       => $listing->category->category_name ?? 'General',
                    
                    // Transaction Type
                    'type-id'             => $listing->type_id,
                    'type-name'           => $listing->type->type_name ?? 'Swap',
                    
                    // Condition
                    'condition-id'        => $listing->condition_id,
                    'condition-name'      => $listing->condition->condition_name ?? 'Used',

                    // Financials & Shifting/Swapping Shorthands
                    'price'               => $listing->price ?? '0',
                    'trade-pref'          => $listing->trade_pref ?? '',

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
}
