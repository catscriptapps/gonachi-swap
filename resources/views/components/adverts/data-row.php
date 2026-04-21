<?php
// /resources/views/components/adverts/data-row.php

/** @var array $item */
/** @var string $assetBase */

// Mirroring the Users data-row logic for asset resolution
$owner = $item['owner'] ?? null;

// Avatar Logic
$hasAvatar = !empty($owner['avatar_url']);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $owner['avatar_url']) : '';

// Declared for use in /resources/views/components/ui/card-owner.php
$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown User';
$initial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// Prepare data attributes for JS
$adDataAttrs = [
    'encoded-id'                    => $item['encoded_id'] ?? '',
    'title'                         => $item['title'] ?? '',
    'description'                   => $item['description'] ?? '',
    'call-to-action-id'             => $item['call_to_action_id'] ?? '',
    'call-to-action'                => $item['cta']['call_to_action'] ?? 'Learn More',
    'keywords'                      => $item['keywords'] ?? '',
    'landing-page-url'              => $item['landing_page_url'] ?? '',
    'selected-countries'            => json_encode($item['selected_countries'] ?? []),
    'country-names'                 => json_encode($item['country_names'] ?? []),
    'selected-user-types'           => json_encode($item['selected_user_types'] ?? []),
    'user-type-names'               => json_encode($item['user_type_names'] ?? []),
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
    'owner-country'                 => $item['owner_country'] ?? 'Canada',
    'user-types'                    => $item['user_types_json'] ?? '["Client"]',
];

// Status Badge Logic
$statusBadge = getStatusBadgeHtml($item['status']);

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

// Location for card-owner.php
$ownerLocation = ($item['owner_region'] ?? 'Unknown Region') . ', ' . ($item['owner_country'] ?? 'Unknown Country');
?>

<tr id="ad-row-<?= $item['advert_id'] ?? '0' ?>"
    class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group border-b border-gray-100 dark:border-gray-800 font-sans overflow-x-hidden">

    <td class="px-4 py-6 md:px-6">
        <div class="flex items-start min-w-0 gap-4 md:gap-5">
            <div class="h-12 w-12 md:h-14 md:w-14 flex-shrink-0 rounded-2xl bg-orange-600 flex items-center justify-center text-white shadow-lg shadow-orange-600/20 group-hover:scale-105 transition-transform duration-200">
                <span class="text-xl md:text-2xl font-black uppercase">
                    <?= strtoupper(substr($item['title'] ?? 'U', 0, 1)) ?>
                </span>
            </div>

            <div class="flex-1 min-w-0 view-ad-trigger cursor-pointer"
                <?php foreach ($adDataAttrs as $key => $val): ?> data-<?= $key ?>='<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>' <?php endforeach; ?>>

                <div class="flex flex-wrap gap-3 mb-2">
                    <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-secondary-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <?= $renderAudienceCount($item['selected_countries'], 'Countries') ?>
                    </div>

                    <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-secondary-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <?= $renderAudienceCount($item['selected_user_types'], 'User Types') ?>
                    </div>

                    <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <?= number_format((int)($item['views'] ?? 0)) ?> Views
                    </div>
                </div>

                <h3 class="text-base md:text-lg font-black text-navy-900 dark:text-white group-hover:text-orange-600 transition-colors line-clamp-1 leading-tight mb-1">
                    <?= htmlspecialchars($item['title'] ?? 'Untitled Advert') ?>
                </h3>

                <p class="text-gray-500 dark:text-gray-400 text-xs leading-relaxed line-clamp-2 mb-3">
                    <?= htmlspecialchars($item['description'] ?? 'No description provided.') ?>
                </p>

                <div class="flex items-center gap-3 lg:hidden pt-3 border-t border-gray-100 dark:border-gray-800/50">
                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden flex-shrink-0 border-2 border-white dark:border-gray-800 shadow-sm">
                        <?php if ($hasAvatar): ?>
                            <img src="<?= $avatarUrl ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <span class="flex items-center justify-center h-full w-full text-[10px] font-black text-gray-500 uppercase"><?= $initial ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col min-w-0">
                        <span class="text-[10px] font-black text-secondary-900 dark:text-white uppercase tracking-tight truncate">
                            By: <?= htmlspecialchars($ownerFullName) ?>
                        </span>

                        <div class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase tracking-widest truncate">
                            <svg class="w-2.5 h-2.5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <?= htmlspecialchars($ownerLocation) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-4 hidden lg:table-cell">
        <?php include __DIR__ . '/../ui/card-owner.php'; ?>
    </td>

    <td class="px-6 py-4 hidden sm:table-cell">
        <span class="text-[10px] font-black text-secondary-900 dark:text-gray-300 uppercase tracking-widest bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-md">
            <?= htmlspecialchars($item['advert_package'] ?? '') ?>
        </span>
    </td>

    <td class="px-6 py-4 hidden xl:table-cell">
        <div class="flex flex-col">
            <span class="text-[11px] font-bold text-secondary-900 dark:text-white uppercase tracking-tight text-nowrap">
                <?= htmlspecialchars($item['created_at_formatted'] ?? 'N/A') ?>
            </span>
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Added</span>
        </div>
    </td>

    <td class="px-6 py-4 hidden md:table-cell text-center status-cell">
        <?= $statusBadge ?>
    </td>
</tr>