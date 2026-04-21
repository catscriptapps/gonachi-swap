<?php
// /resources/views/components/quotations/data-card.php

/** @var array $item */
/** @var string $assetBase */

$owner = $item['owner'] ?? null;

// 💎 Ownership Logic for Gonachi 
$currentUserId = \Src\Service\AuthService::userId();
$isOwner = $owner && (int)$owner['id'] === (int)$currentUserId;

// Avatar Logic (Exact copy from users data-row)
$hasAvatar = !empty($owner['avatar_url']);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $owner['avatar_url']) : '';

$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown User';
$initial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// 💎 Picture Logic
$hasThumbnail = !empty($item['thumbnail']);
$thumbnailUrl = $hasThumbnail ? $assetBase . 'images/uploads/quotations/' . $item['thumbnail'] : null;

// Prepare data attributes for JS
$quoteDataAttrs = [
    'encoded-id'           => $item['encoded_id'] ?? '',
    'title'                => $item['quotation_title'] ?? '',
    'description'          => $item['description_of_work_to_be_done'] ?? '',
    'city'                 => $item['city'] ?? '',

    // Geography (Project Location)
    'country-id'          => $item['country_id'] ?? '',
    'country-name'        => $item['country_name'] ?? '',
    'region-id'           => $item['region_id'] ?? '',
    'region-name'         => $item['region_name'] ?? '',

    // Classification
    'contractor-type-id'   => $item['contractor_type_id'] ?? '',
    'contractor-type-name' => $item['contractor_label'] ?? '',
    'skilled-trade-id'     => $item['skilled_trade_id'] ?? '',
    'skilled-trade-name'   => $item['trade_label'] ?? '',

    // Property Details
    'unit-type-id'         => $item['unit_type_id'] ?? '',
    'unit-type-name'       => $item['unit_label'] ?? '',
    'house-type-id'        => $item['house_type_id'] ?? '',
    'house-type-name'      => $item['house_label'] ?? '',

    // Quotation Specifics
    'quotation-type-id'    => $item['quotation_type_id'] ?? '',
    'quotation-type-name'  => $item['type_label'] ?? '',
    'quotation-dest-id'    => $item['quotation_dest_id'] ?? '',
    'quotation-dest-name'  => $item['dest_label'] ?? '',

    // Logistics & Contact
    'budget'               => $item['quotation_budget'] ?? '',
    'start-date'           => $item['start_date'] ?? '',
    'finish-date'          => $item['finish_date'] ?? '',
    'start-time'           => $item['start_time'] ?? '',
    'finish-time'          => $item['finish_time'] ?? '',
    'contact-phone'        => $item['contact_phone'] ?? '',
    'youtube-url'          => $item['youtube_url'] ?? '',
    'notify'               => $item['notify'] ?? 'no',

    // Owner Data 
    'owner-name'           => $ownerFullName,
    'owner-avatar'         => $avatarUrl,
    'owner-initial'        => $initial,
    'owner-region'         => $item['owner_region'] ?? 'Unknown Region',
    'owner-country'        => $item['owner_country'] ?? 'Unknown Country',
    'owner-id'             => (int)$item['orig_user_id'],
    'user-types'           => $item['user_types_json'] ?? '["Client"]',

    // Meta
    'views-count'          => $item['views'] ?? 0,
    'status-id'            => $item['status_id'] ?? 1,
    'created-at'           => $item['created_at_formatted'] ?? 'N/A',
    'updated-at'           => $item['updated_at_formatted'] ?? ($item['created_at_formatted'] ?? 'N/A')
];

$editClass = 'edit-quote-btn';
$deleteClass = 'delete-quote-btn';

// Status Badge Logic
$statusBadge = match ((int)($item['status_id'] ?? 0)) {
    1       => '<span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-green-600 dark:text-green-400 border border-green-100 dark:border-green-800/30">Active</span>',
    2       => '<span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">Archived</span>',
    default => '<span class="inline-flex items-center rounded-full bg-primary-50 dark:bg-primary-900/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-primary-400 dark:text-primary-300 border border-primary-100 dark:border-primary-800/30">Draft</span>'
};

$tradeLabel = $item['trade_label'] ?? 'General Labor';
$locationLabel = (!empty($item['city']) ? $item['city'] . ', ' : '') . ($item['region_name'] ?? '');
$ownerLocation = $item['owner_location'];
$statusId = (int)($item['status_id'] ?? 0);

// Format Date Range
$dateRange = (!empty($item['start_date']) ? $item['start_date'] : 'TBD') . ' - ' . (!empty($item['finish_date']) ? $item['finish_date'] : 'TBD');

// Format Time Range
$sTime = !empty($item['start_time']) ? date("g:i A", strtotime($item['start_time'])) : 'N/A';
$fTime = !empty($item['finish_time']) ? date("g:i A", strtotime($item['finish_time'])) : 'N/A';
$timeRange = $sTime . ' - ' . $fTime;
?>

<div id="quote-card-<?= $item['quotation_id'] ?? '0' ?>"
    data-encoded-id="<?= $item['encoded_id'] ?? '' ?>"
    class="quotation-card-wrapper bg-white dark:bg-gray-950 rounded-[2rem] shadow-md hover:shadow-xl border border-gray-100 dark:border-secondary-900 transition-all duration-300 group flex flex-col h-full font-sans relative"
    data-aos="fade-up">

    <div class="px-6 pt-6 flex justify-between items-start">
        <?php
        $viewsCountId = 'quotation-views-count-' . ($item['quotation_id'] ?? '0');
        include __DIR__ . '/../ui/status-badge-and-views-count.php';
        ?>

        <?php if ($isOwner): ?>
            <div class="absolute top-6 right-4 hidden lg:block z-10">
                <?php
                $isMobile = false;
                $dataAttrs = $quoteDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 flex-grow view-quote-trigger cursor-pointer"
        <?php foreach ($quoteDataAttrs as $key => $val): ?>
        data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>"
        <?php endforeach; ?>>

        <?php include __DIR__ . '/../ui/card-owner.php'; ?>

        <div class="mb-2">
            <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest"><?= htmlspecialchars($tradeLabel) ?></span>
        </div>

        <h3 class="text-xl font-black text-secondary-900 dark:text-white mb-2 group-hover:text-primary-400 transition-colors line-clamp-1">
            <?= htmlspecialchars($item['quotation_title'] ?? 'Untitled Project') ?>
        </h3>

        <?php if ($hasThumbnail): ?>
            <div class="mb-4 overflow-hidden rounded-2xl border border-gray-100 dark:border-secondary-800/50">
                <img src="<?= $thumbnailUrl ?>" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
        <?php endif; ?>

        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-6">
            <?= htmlspecialchars($item['description_of_work_to_be_done'] ?? 'No description provided.') ?>
        </p>

        <?php if (!$hasThumbnail): ?>
            <div class="grid grid-cols-2 gap-y-3 gap-x-2 border-t border-gray-50 dark:border-secondary-800/50 pt-4">
                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight">
                    <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate"><?= htmlspecialchars($locationLabel ?: 'Remote / TBD') ?></span>
                </div>

                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight">
                    <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="truncate"><?= htmlspecialchars($item['house_label'] ?? 'Residential') ?></span>
                </div>

                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight">
                    <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM17 11a5 5 0 11-10 0 5 5 0 0110 0z" />
                    </svg>
                    <span>Budget: <?= !empty($item['quotation_budget']) ? htmlspecialchars((string)$item['quotation_budget']) : 'Open' ?></span>
                </div>

                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-tight">
                    <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="truncate"><?= htmlspecialchars($item['unit_label'] ?? 'Standard') ?></span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-2 border-t border-gray-50 dark:border-secondary-800/50 pt-4">
                <div class="flex items-center gap-2 text-[10px] font-black text-secondary-900 dark:text-gray-300 uppercase tracking-widest">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Timeline:<br><span class="text-secondary-400 dark:text-secondary-300"><?= htmlspecialchars($dateRange) ?></span></span>
                </div>

                <div class="flex items-center gap-2 text-[10px] font-black text-secondary-900 dark:text-gray-300 uppercase tracking-widest">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Working Hours:<br><span class="text-secondary-400 dark:text-secondary-300"><?= htmlspecialchars($timeRange) ?></span></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($isOwner): ?>
            <div class="mt-6 lg:hidden border-t border-gray-100 dark:border-secondary-800 pt-4">
                <?php
                $isMobile = true;
                $dataAttrs = $quoteDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 mt-auto border-t border-gray-50 dark:border-secondary-800 bg-gray-50/30 dark:bg-secondary-900/20 rounded-b-[2rem]">
        <?php if ($isOwner): ?>
            <?php
            $isArchived = ((int)$statusId === 2);
            $triggerClass = $isArchived ? 'reactivate-quotation-trigger' : 'deactivate-quotation-trigger';
            $btnStyles = $isArchived
                ? 'bg-green-50 dark:bg-green-900/10 text-green-600 dark:text-green-400 border-green-100 dark:border-green-900/30 hover:bg-green-600 hover:text-white'
                : 'bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 border-red-100 dark:border-red-900/30 hover:bg-red-600 hover:text-white';
            ?>
            <button type="button"
                data-encoded-id="<?= $item['encoded_id'] ?? '' ?>"
                class="<?= $triggerClass ?> w-full inline-flex justify-center items-center gap-2 px-4 py-3 <?= $btnStyles ?> border transition-all font-black text-sm rounded-xl active:scale-95"
                <?php foreach ($quoteDataAttrs ?? [] as $key => $val): ?> data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>" <?php endforeach; ?>>

                <?= $isArchived ? 'Reactivate Quotation' : 'Deactivate Quotation' ?>

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
                class="connect-quotation-trigger w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-primary-400 hover:bg-primary-500 text-white font-black text-sm rounded-xl transition-all shadow-lg shadow-primary-400/20 active:scale-95"
                <?php foreach ($quoteDataAttrs as $key => $val): ?> data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>" <?php endforeach; ?>>
                Connect with Owner
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </button>
        <?php endif; ?>
    </div>
</div>