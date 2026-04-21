<?php
// /resources/views/components/listings/data-card.php

/** @var array $item */
/** @var string $assetBase */

$owner = $item['owner'] ?? null;

// 💎 Ownership Logic
$currentUserId = \Src\Service\AuthService::userId();
$isOwner = $owner && (int)$owner['id'] === (int)$currentUserId;

// Avatar Logic
$hasAvatar = !empty($owner['avatar_url']);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $owner['avatar_url']) : '';

$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown User';
$initial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// Category-Based Logic
$categoryId = (int)($item['category_id'] ?? 1);
$statusId = (int)($item['status_id'] ?? 0);
$isService = ($categoryId === 2 || $categoryId === 3);

// Prepare data attributes for JS
$listingDataAttrs = [
    'encoded-id'          => $item['encoded_id'] ?? '',
    'listing-title'       => $item['listing_title'] ?? '',
    'listing-description' => $item['listing_description'] ?? '',
    'city'                => $item['city'] ?? '',
    'address'             => $item['address'] ?? '',

    // Geography
    'country-id'          => $item['country_id'] ?? '',
    'country-name'        => $item['country_name'] ?? '',
    'region-id'           => $item['region_id'] ?? '',
    'region-name'         => $item['region_name'] ?? '',

    // Classification
    'category-id'         => $item['category_id'] ?? '',
    'category-name'       => $item['category_label'] ?? '',
    'category-type-id'    => $item['category_type_id'] ?? '',
    'category-type-name'  => $item['category_type'] ?? '',

    // Property Specs
    'unit-type-id'        => $item['unit_type_id'] ?? '',
    'unit-type-name'      => $item['unit_label'] ?? '',
    'house-type-id'       => $item['house_type_id'] ?? '',
    'house-type-name'     => $item['house_label'] ?? '',
    'bedroom-id'          => $item['bedroom_id'] ?? '',
    'bedroom-label'       => $item['bedroom_label'] ?? '0',
    'bathroom-id'         => $item['bathroom_id'] ?? '',
    'bathroom-label'      => $item['bathroom_label'] ?? '0',
    'property-size'       => $item['property_size'] ?? '',

    'is-ac'               => (int)($item['is_ac'] ?? 0),
    'is-furnished'        => (int)($item['is_furnished'] ?? 0),
    'parking'             => (int)($item['parking'] ?? 0),
    'pets-allowed'        => (int)($item['pets_allowed'] ?? 0),

    // Financials & Logistics
    'price'               => $item['price'] ?? '0',
    'agreement-type-id'   => $item['agreement_type_id'] ?? '',
    'agreement-type-name' => $item['agreement_label'] ?? '',
    'move-in-date'        => $item['move_in_date'] ?? '',

    // Amenities
    'amenities'            => json_encode($item['amenities'] ?? []),
    'amenities-collection' => json_encode($item['amenities_data'] ?? []),

    // Logistics & Contact
    'contact-phone'       => $item['contact_phone'] ?? '',
    'youtube-url'         => $item['youtube_url'] ?? '',

    // Owner Data 
    'owner-name'          => $ownerFullName,
    'owner-avatar'        => $avatarUrl,
    'owner-initial'       => $initial,
    'owner-region'        => $item['owner_region'] ?? 'Unknown Region',
    'owner-country'       => $item['owner_country'] ?? 'Unknown Country',
    'owner-id'            => (int)$item['orig_user_id'],
    'user-types'          => $item['user_types_json'] ?? '["Client"]',

    // Meta
    'views-count'         => $item['views'] ?? 0,
    'status-id'           => $item['status_id'] ?? 1,
    'created-at'          => $item['created_at_formatted'] ?? 'N/A',
    'updated-at'          => $item['updated_at_formatted'] ?? ($item['created_at_formatted'] ?? 'N/A')
];

$editClass = 'edit-listing-btn';
$deleteClass = 'delete-listing-btn';

// Status Badge Logic
$statusBadge = match ((int)($item['status_id'] ?? 0)) {
    1       => '<span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-green-600 dark:text-green-400 border border-green-100 dark:border-green-800/30">Active</span>',
    2       => '<span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">Archived</span>',
    default => '<span class="inline-flex items-center rounded-full bg-primary-50 dark:bg-primary-900/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-primary-400 dark:text-primary-300 border border-primary-100 dark:border-primary-800/30">Draft</span>'
};

$categoryHeading = $item['category_label'] ?? 'General Listing';
$locationLabel = (!empty($item['city']) ? $item['city'] . ', ' : '') . ($item['region_name'] ?? '');
$ownerLocation = $item['owner_location'];

$priceDisplay = !empty($item['price']) ? $item['price'] : 'Contact for Price';
$moveInDisplay = !empty($item['move_in_date']) ? date("M j, Y", strtotime($item['move_in_date'])) : 'Available Now';

// Thumbnail Logic where applicable
$thumbnailUrl = !empty($item['thumbnail']) ? $assetBase . 'images/uploads/listings/' . $item['thumbnail'] : null;
?>

<div id="listing-card-<?= $item['listing_id'] ?? '0' ?>"
    data-encoded-id="<?= $item['encoded_id'] ?? '' ?>"
    class="listing-card-wrapper bg-white dark:bg-gray-950 rounded-[2rem] shadow-md hover:shadow-xl border border-gray-100 dark:border-secondary-900 transition-all duration-300 group flex flex-col h-full font-sans relative"
    data-aos="fade-up">

    <div class="px-6 pt-6 flex justify-between items-start">
        <?php
        $viewsCountId = 'listing-views-count-' . ($item['listing_id'] ?? '0');
        include __DIR__ . '/../ui/status-badge-and-views-count.php';
        ?>

        <?php if ($isOwner): ?>
            <div class="absolute top-6 right-4 hidden lg:block z-10">
                <?php
                $isMobile = false;
                $dataAttrs = $listingDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 flex-grow view-listing-trigger cursor-pointer"
        <?php foreach ($listingDataAttrs as $key => $val): ?>
        data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>"
        <?php endforeach; ?>>

        <?php include __DIR__ . '/../ui/card-owner.php'; ?>

        <div class="mb-2">
            <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest"><?= htmlspecialchars($categoryHeading) ?></span>
        </div>

        <h3 class="text-xl font-black text-secondary-900 dark:text-white mb-2 group-hover:text-primary-400 transition-colors line-clamp-1">
            <?= htmlspecialchars($item['listing_title'] ?? 'Untitled Listing') ?>
        </h3>

        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-6">
            <?= htmlspecialchars($item['listing_description'] ?? 'No description provided.') ?>
        </p>

        <div class="<?= ($isService) ? '' : 'grid grid-cols-2' ?> gap-y-3 gap-x-2 border-t border-gray-200 dark:border-secondary-800/50 pt-4">
            <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight min-w-0">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                    <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="<?= ($isService) ? '' : 'truncate' ?>"><?= htmlspecialchars($locationLabel ?: 'TBD') ?></span>
            </div>

            <?php if ($thumbnailUrl): ?>
                <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 dark:border-secondary-800/50 <?= ($isService) ? '' : 'col-span-2' ?>">
                    <img src="<?= $thumbnailUrl ?>"
                        alt="<?= htmlspecialchars($item['listing_title']) ?>"
                        class="w-full h-32 object-cover grayscale-[0.5] group-hover:grayscale-0 transition-all duration-500 scale-100 group-hover:scale-105">
                </div>
            <?php elseif ($isService): ?>
                <div class="mt-4 h-32 rounded-2xl bg-gray-50 dark:bg-secondary-900/40 border border-dashed border-gray-200 dark:border-secondary-800 flex items-center justify-center">
                    <span class="text-[10px] font-black text-gray-300 dark:text-secondary-700 uppercase tracking-widest">No Preview Available</span>
                </div>
            <?php endif; ?>

            <?php if (!$isService): ?>
                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight min-w-0">
                    <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                        <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="truncate"><?= htmlspecialchars($item['house_label'] ?? 'Residential') ?></span>
                </div>

                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight min-w-0">
                    <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                        <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <span class="truncate"><?= $priceDisplay ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($isOwner): ?>
            <div class="mt-6 lg:hidden border-t border-gray-100 dark:border-secondary-800 pt-4">
                <?php
                $isMobile = true;
                $dataAttrs = $listingDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 mt-auto border-t border-gray-200 dark:border-secondary-800 bg-gray-50/30 dark:bg-secondary-900/20 rounded-b-[2rem]">
        <?php if ($isOwner): ?>
            <?php
            // 💎 Determine the class and icon based on status
            $isArchived = ((int)$statusId === 2);
            $triggerClass = $isArchived ? 'reactivate-listing-trigger' : 'deactivate-listing-trigger';
            $btnStyles = $isArchived
                ? 'bg-green-50 dark:bg-green-900/10 text-green-600 dark:text-green-400 border-green-100 dark:border-green-900/30 hover:bg-green-100'
                : 'bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 border-red-100 dark:border-red-900/30 hover:bg-red-100';
            ?>
            <button type="button"
                class="<?= $triggerClass ?> w-full inline-flex justify-center items-center gap-2 px-4 py-3 <?= $btnStyles ?> border transition-all font-black text-sm rounded-xl"
                <?php foreach ($listingDataAttrs as $key => $val): ?> data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>" <?php endforeach; ?>>

                <?= $isArchived ? 'Reactivate Listing' : 'End Listing' ?>

                <?php if ($isArchived): ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                <?php else: ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                <?php endif; ?>
            </button>
        <?php else: ?>
            <button type="button"
                class="connect-listing-trigger w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-primary-400 hover:bg-primary-500 text-white font-black text-sm rounded-xl transition-all shadow-lg shadow-primary-400/20 active:scale-95"
                <?php foreach ($listingDataAttrs as $key => $val): ?> data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>" <?php endforeach; ?>>
                Contact Owner
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </button>
        <?php endif; ?>
    </div>
</div>