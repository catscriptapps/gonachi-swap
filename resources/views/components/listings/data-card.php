<?php
// /resources/views/components/listings/data-card.php

/** @var array $item */
/** @var string $assetBase */

$owner = $item['user'] ?? null;

// 💎 Ownership Logic
$currentUserId = \Src\Service\AuthService::userId();
$isOwner = $owner && (int)($owner['user_id'] ?? 0) === (int)$currentUserId;

// Avatar & Identity
$hasAvatar = !empty($owner['avatar_url']);
$avatarUrl = $hasAvatar ? htmlspecialchars($assetBase . 'images/uploads/avatars/' . $owner['avatar_url']) : '';
$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown Swapper';
$initial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// Category & Status
$categoryId = (int)($item['category_id'] ?? 1);
$statusId = (int)($item['status_id'] ?? 1);
$categoryHeading = $item['category_label'] ?? 'General';

// Prepare data attributes for JS (Gonachi Swap Style)
$listingDataAttrs = [
    'encoded-id'          => $item['encoded_id'] ?? '',
    'listing-title'       => $item['listing_title'] ?? '',
    'listing-description' => $item['listing_description'] ?? '',
    'city'                => $item['city'] ?? '',
    'country-id'          => $item['country_id'] ?? '',
    'country-name'        => $item['country_name'] ?? '',
    'category-id'         => $item['category_id'] ?? '',
    'category-name'       => $item['category_label'] ?? '',
    'price'               => $item['price'] ?? 'Trade',
    'contact-phone'       => $item['contact_phone'] ?? '',
    'owner-name'          => $ownerFullName,
    'owner-id'            => (int)($item['orig_user_id'] ?? 0),
    'status-id'           => $statusId,
    'created-at'          => $item['created_at_formatted'] ?? 'Just now',
];

// Thumbnail Logic
$thumbnailUrl = !empty($item['thumbnail']) 
    ? $assetBase . 'images/uploads/listings/' . $item['thumbnail'] 
    : $assetBase . 'images/placeholder-listing.webp';

// Status Badge Logic 💎
$statusBadge = match ($statusId) {
    1       => '<span class="px-4 py-1.5 bg-primary-500 text-secondary-950 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-xl">Active</span>',
    2       => '<span class="px-4 py-1.5 bg-gray-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-xl">Archived</span>',
    default => '<span class="px-4 py-1.5 bg-secondary-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-xl">Draft</span>'
};
?>

<div id="listing-card-<?= $item['listing_id'] ?? '0' ?>"
    class="group relative bg-white dark:bg-secondary-950 rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-white/5 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full font-sans"
    data-aos="fade-up"
    <?php foreach ($listingDataAttrs as $key => $val): ?> data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>" <?php endforeach; ?>>

    <!-- Image Section -->
    <div class="aspect-[4/3] overflow-hidden relative view-listing-trigger cursor-pointer">
        <img src="<?= $thumbnailUrl ?>" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90 group-hover:opacity-100" 
             alt="<?= htmlspecialchars($item['listing_title'] ?? 'Listing') ?>">

        <div class="absolute top-5 left-5 flex flex-col gap-2">
            <span class="px-4 py-1.5 bg-secondary-950/80 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-lg">
                <?= htmlspecialchars($item['city'] ?? 'Location TBD') ?>
            </span>
            <?= $statusBadge ?>
        </div>
        
        <?php if ($isOwner): ?>
            <div class="absolute top-5 right-5">
                <button class="edit-listing-btn p-2 bg-white/90 backdrop-blur-md rounded-full text-secondary-900 hover:bg-primary-500 hover:text-secondary-950 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content Section -->
    <div class="p-8 flex flex-col flex-grow">
        <div class="flex justify-between items-start mb-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest"><?= htmlspecialchars($categoryHeading) ?></span>
                </div>
                <h3 class="text-2xl font-black text-secondary-900 dark:text-white tracking-tight leading-none group-hover:text-primary-500 transition-colors line-clamp-1">
                    <?= htmlspecialchars($item['listing_title'] ?? 'Untitled') ?>
                </h3>
            </div>
            <span class="text-sm font-black text-secondary-900 dark:text-primary-500 whitespace-nowrap">
                <?= htmlspecialchars($item['price'] ?? 'Trade') ?>
            </span>
        </div>

        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-2 mb-6">
            <?= htmlspecialchars($item['listing_description'] ?? 'No description provided.') ?>
        </p>

        <!-- Meta Info -->
        <div class="flex items-center gap-4 mb-8 text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-white/5 pt-6">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-[11px] font-bold uppercase"><?= $item['created_at_formatted'] ?? '2h ago' ?></span>
            </div>
            <div class="w-1 h-1 rounded-full bg-gray-300"></div>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-[11px] font-bold uppercase"><?= $item['views'] ?? 0 ?> Views</span>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-auto">
            <?php if ($isOwner): ?>
                <?php $isArchived = ($statusId === 2); ?>
                <button type="button"
                    class="<?= $isArchived ? 'reactivate-listing-trigger' : 'deactivate-listing-trigger' ?> w-full py-4 bg-gray-100 dark:bg-white/5 <?= $isArchived ? 'hover:bg-green-500' : 'hover:bg-red-500' ?> text-secondary-900 dark:text-white hover:text-white font-black uppercase tracking-widest text-xs rounded-2xl transition-all">
                    <?= $isArchived ? 'Reactivate Swap' : 'End Listing' ?>
                </button>
            <?php else: ?>
                <button type="button"
                    class="connect-listing-trigger block w-full text-center py-4 bg-gray-100 dark:bg-white/5 hover:bg-primary-500 text-secondary-900 dark:text-white hover:text-secondary-950 font-black uppercase tracking-widest text-xs rounded-2xl transition-all">
                    View Details
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>