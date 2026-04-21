<?php
// /resources/views/components/mentors/data-card.php

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

// Skills Chips
$skills = is_array($item['skills']) ? $item['skills'] : json_decode($item['skills'] ?? '[]', true);

// Gonachi Professional Data Attributes 💎
$mentorDataAttrs = [
    'encoded-id'       => $item['encoded_id'] ?? '',
    'id'               => $item['id'] ?? '',
    'owner-id'         => $owner['id'] ?? '',
    'headline'         => $item['headline'] ?? '',
    'bio'              => $item['bio'] ?? '',
    'target-type-id'   => $item['target_user_type_id'] ?? '',
    'target-user-type' => $item['target_user_type']['user_type'] ?? 'Expert',
    'country-id'       => $item['country_id'] ?? '',
    'region-id'        => $item['region_id'] ?? '',
    'city'             => $item['city'] ?? '',
    'experience-years' => $item['years_experience'] ?? 0,
    'youtube-url'      => $item['youtube_url'] ?? '',
    'website-url'      => $item['website_url'] ?? '',
    'country-name'     => $item['country_name'] ?? 'N/A', // 💎 Added
    'region-name'      => $item['region_name'] ?? 'N/A',  // 💎 Added
    'skills-json'      => json_encode($skills),

    // Owner Data 
    'owner-name'           => $ownerFullName,
    'owner-avatar'         => $avatarUrl,
    'owner-initial'        => $initial,
    'owner-region'         => $item['owner_region'] ?? 'Unknown Region',
    'owner-country'        => $item['owner_country'] ?? 'Unknown Country',
    'user-types'           => $item['user_types_json'] ?? '["Client"]',

    // Meta
    'is-active'       => $item['is_active'] ?? 1
];

$editClass = 'edit-mentor-btn';
$deleteClass = 'delete-mentor-btn';
?>

<div id="mentor-card-<?= $item['id'] ?? '0' ?>"
    data-encoded-id="<?= $item['encoded_id'] ?? '' ?>"
    class="mentor-card-wrapper bg-white dark:bg-gray-950 rounded-[2rem] shadow-md hover:shadow-xl border border-gray-100 dark:border-secondary-900 transition-all duration-300 group flex flex-col h-full font-sans relative"
    data-aos="fade-up">

    <div class="px-6 pt-6 flex justify-between items-start">
        <div class="flex-shrink-0">
            <span class="inline-flex items-center rounded-full bg-primary-50 dark:bg-primary-900/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-primary-400 dark:text-primary-300 border border-primary-100 dark:border-primary-800/30">
                <?= htmlspecialchars($item['target_user_type']['user_type'] ?? 'Expert') ?>
            </span>
        </div>

        <?php if ($isOwner): ?>
            <div class="absolute top-6 right-4 hidden lg:block z-10">
                <?php
                $isMobile = false;
                $dataAttrs = $mentorDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 flex-grow view-mentor-trigger cursor-pointer"
        <?php foreach ($mentorDataAttrs as $key => $val): ?>
        data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>"
        <?php endforeach; ?>>

        <div class="flex items-center gap-4 mb-4">
            <div class="relative">
                <?php if ($hasAvatar): ?>
                    <img src="<?= $avatarUrl ?>" alt="<?= $ownerFullName ?>" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                <?php else: ?>
                    <div class="w-14 h-14 rounded-2xl bg-secondary-100 dark:bg-white/5 flex items-center justify-center text-lg font-black text-secondary-400">
                        <?= $initial ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h3 class="text-xl font-black text-secondary-900 dark:text-white group-hover:text-primary-400 transition-colors line-clamp-1">
                    <?= htmlspecialchars($ownerFullName) ?>
                </h3>
                <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">
                    <?= (int)($item['years_experience'] ?? 0) ?>+ Years Experience
                </span>
            </div>
        </div>

        <div class="mb-2">
            <span class="text-[10px] font-black text-secondary-400 uppercase tracking-widest block">
                <?= htmlspecialchars($item['headline'] ?? 'Professional Mentor') ?>
            </span>
            <?php if (!empty($item['city'])): ?>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tight">
                    <i class="bi bi-geo-alt-fill text-primary-400 mr-0.5"></i> <?= htmlspecialchars($item['city']) ?>
                </span>
            <?php endif; ?>
        </div>

        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3 mb-6">
            <?= htmlspecialchars($item['bio'] ?? 'No bio provided.') ?>
        </p>

        <div class="flex flex-wrap gap-2 mb-4 border-t border-gray-50 dark:border-secondary-800/50 pt-4">
            <?php foreach (array_slice($skills, 0, 3) as $skill): ?>
                <span class="px-2 py-0.5 rounded-md bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 text-[9px] font-bold text-gray-500 uppercase tracking-tight">
                    #<?= htmlspecialchars(trim($skill)) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <?php if ($isOwner): ?>
            <div class="mt-6 lg:hidden border-t border-gray-100 dark:border-secondary-800 pt-4">
                <?php
                $isMobile = true;
                $dataAttrs = $mentorDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php';
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="p-6 mt-auto border-t border-gray-50 dark:border-secondary-800 bg-gray-50/30 dark:bg-secondary-900/20 rounded-b-[2rem]">
        <?php if ($isOwner): ?>
            <button type="button"
                disabled
                class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-gray-300 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-black text-sm rounded-xl cursor-not-allowed">
                Your Profile
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>
        <?php else: ?>
            <button type="button"
                class="connect-mentor-trigger w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-primary-400 hover:bg-primary-500 text-white font-black text-sm rounded-xl transition-all shadow-lg shadow-primary-400/20 active:scale-95"
                <?php foreach ($mentorDataAttrs as $key => $val): ?> data-<?= $key ?>="<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>" <?php endforeach; ?>>
                Connect with Mentor
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </button>
        <?php endif; ?>
    </div>
</div>