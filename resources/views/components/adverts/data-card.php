<?php
// /resources/views/components/adverts/data-card.php

/** @var array $item */
/** @var string $assetBase */

$owner = $item['owner'] ?? null;

// Avatar Logic (Exact copy from users data-row)
$hasAvatar = !empty($owner['avatar_url']);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $owner['avatar_url']) : '';

$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown User';
$initial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// 💎 Picture Logic
$hasThumbnail = !empty($item['thumbnail']);
$thumbnailUrl = $hasThumbnail ? $assetBase . 'images/uploads/adverts/' . $item['thumbnail'] : null;

// --- NEW OWNERSHIP CHECK ---
$isCardOwner = ((int)($item['orig_user_id'] ?? 0) === (int)($_SESSION['user_id'] ?? 0));

// Prepare data attributes for JS
$adDataAttrs = [
    'encoded-id'                    => $item['encoded_id'] ?? '',
    'title'                         => $item['title'] ?? '',
    'description'                   => $item['description'] ?? '',
    'call-to-action-id'             => $item['call_to_action_id'] ?? '',
    'call-to-action'                => $item['cta_text'] ?? 'Learn More',
    'keywords'                      => $item['keywords'] ?? '',
    'landing-page-url'              => $item['landing_page_url'] ?? '',
    'selected-countries'            => json_encode($item['selected_countries'] ?? []),
    'country-names'                 => json_encode($item['country_names'] ?? []), // Passing Names
    'selected-user-types'           => json_encode($item['selected_user_types'] ?? []),
    'user-type-names'               => json_encode($item['user_type_names'] ?? []), // Passing Names
    'advert-package'                => $item['advert_package'] ?? '',
    'advert-package-description'    => $item['advert_package_description'] ?? '',
    'advert-package-icon'           => $item['advert_package_icon'] ?? '',
    'status'                        => $item['status'] ?? 'pending',
    'joined'                        => $item['created_at_formatted'] ?? 'N/A',
    'updated'                       => $item['updated_at_formatted'] ?? 'N/A',
    'views-count'                   => $item['views'] ?? 0,

    // Owner Data 
    'owner-name'                    => $ownerFullName,
    'owner-avatar'                  => $avatarUrl,
    'owner-initial'                 => $initial,
    'owner-region'                  => $item['owner_region'] ?? 'Unknown Region',
    'owner-country'                 => $item['owner_country'] ?? 'Unknown Country',
    'owner-id'                      => (int)$item['orig_user_id'],
    'user-types'                    => $item['user_types_json'] ?? '["Client"]',
];

$editClass = 'edit-ad-btn';
$deleteClass = 'delete-ad-btn';

// Status Badge Logic
$statusBadge = getStatusBadgeHtml($item['status']);

/**
 * Clean Logic for Audience Counts
 */
$renderAudienceCount = function ($list, $label) {
    $list = is_array($list) ? $list : [];
    if (in_array('ALL', $list) || empty($list)) return 'All ' . $label;
    $count = count($list);
    if ($count === 1) {
        $singular = ($label === 'Countries') ? 'Country' : substr($label, 0, -1);
        return '1 ' . $singular;
    }
    return $count . ' ' . $label;
};

$ctaLabel = $item['cta_text'] ?? 'Learn More';

// URL Formatting
$rawUrl = $item['landing_page_url'] ?? '';
$formattedUrl = $rawUrl;
if (!empty($rawUrl) && !preg_match("~^(?:f|ht)tps?://~i", $rawUrl)) {
    $formattedUrl = "https://" . $rawUrl;
}

$ownerLocation = ($item['owner_region'] ?? 'Unknown Region') . ', ' . ($item['owner_country'] ?? 'Unknown Country');
?>

<div id="ad-card-<?= $item['advert_id'] ?? '0' ?>"
    data-encoded-id="<?= $item['encoded_id'] ?? '' ?>"
    class="ad-card-wrapper bg-white dark:bg-gray-900 rounded-[2rem] shadow-md hover:shadow-xl border border-gray-100 dark:border-gray-800 transition-all duration-300 group flex flex-col h-full font-sans relative"
    data-aos="fade-up">

    <div class="px-6 pt-6 flex justify-between items-start">
        <?php
        $viewsCountId = 'advert-views-count-' . ($item['advert_id'] ?? '0');
        include __DIR__ . '/../ui/status-badge-and-views-count.php';
        ?>

        <?php if ($isCardOwner): ?>
            <div class="absolute top-15 right-4 hidden lg:block z-10">
                <?php
                $isMobile = false;
                $dataAttrs = $adDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 flex-grow view-ad-trigger cursor-pointer"
        <?php foreach ($adDataAttrs as $key => $val): ?> data-<?= $key ?>='<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>' <?php endforeach; ?>>

        <?php include __DIR__ . '/../ui/card-owner.php'; ?>

        <h3 class="text-xl font-black text-navy-900 dark:text-white mt-2 mb-2 group-hover:text-orange-600 transition-colors line-clamp-1">
            <?= htmlspecialchars($item['title'] ?? 'Untitled Advert') ?>
        </h3>

        <?php if ($hasThumbnail): ?>
            <div class="mb-4 overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800/50">
                <img src="<?= $thumbnailUrl ?>" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
        <?php endif; ?>

        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-6">
            <?= htmlspecialchars($item['description'] ?? 'No description provided.') ?>
        </p>

        <div class="flex flex-wrap gap-4">
            <?php if (!$hasThumbnail): ?>
                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight">
                    <svg class="w-4 h-4 text-secondary-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                    <?= $renderAudienceCount($item['selected_countries'], 'Countries') ?>
                </div>

                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight">
                    <svg class="w-4 h-4 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <?= $renderAudienceCount($item['selected_user_types'], 'User Types') ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$hasThumbnail): ?>
            <div class="mt-3 flex items-center gap-2 border-t border-gray-50 dark:border-gray-800/50 pt-3">
                <div class="text-orange-600 dark:text-orange-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $item['advert_package_icon'] ?>"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-secondary-900 dark:text-gray-300 uppercase tracking-[0.15em]">
                    <?= htmlspecialchars($item['advert_package'] ?? '') ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if ($isCardOwner): ?>
            <div class="mt-6 lg:hidden border-t border-gray-100 dark:border-gray-800 pt-4">
                <?php
                $isMobile = true;
                $dataAttrs = $adDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 mt-auto border-t border-gray-50 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/20 rounded-b-[2rem]">
        <?php if (!empty($formattedUrl)): ?>
            <a href="<?= htmlspecialchars($formattedUrl) ?>"
                target="_blank"
                class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-black text-sm rounded-xl transition-all shadow-lg shadow-orange-600/10 active:scale-95">
                <?= htmlspecialchars($ctaLabel) ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        <?php else: ?>
            <button disabled class="w-full py-3 bg-gray-100 dark:bg-gray-800 text-gray-400 font-bold text-sm rounded-xl cursor-not-allowed uppercase tracking-widest">
                No Link
            </button>
        <?php endif; ?>
    </div>
</div>