<?php
// /resources/views/components/social-feed/ad-card.php

/**
 * @var \App\Models\Advert $ad
 * @var string $assetBase
 * @var string $encoded_id
 * @var string $cta_text
 * @var array $adDataAttrs
 */

$firstPic = $ad->pictures->first();
$cleanAssetBase = rtrim($assetBase, '/') . '/';
$imagePath = $firstPic ? 'images/uploads/adverts/' . $firstPic->pic_name : null;
$fullImageUrl = $imagePath ? $cleanAssetBase . $imagePath : null;
?>

<div class="social-ad-card bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border-2 border-secondary-500/20 overflow-hidden mb-6 group transition-all hover:border-secondary-500/40" data-aos="fade-up">

    <div class="px-6 py-3 bg-secondary-50 dark:bg-secondary-500/5 flex items-center justify-between border-b border-secondary-100 dark:border-secondary-500/10">
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-secondary-600 dark:text-secondary-400">Sponsored Advert</span>
        </div>
        <i class="bi bi-megaphone-fill text-secondary-500 text-sm"></i>
    </div>

    <div class="p-6">
        <div class="relative h-56 rounded-2xl overflow-hidden mb-4 bg-gray-100 dark:bg-gray-800">
            <?php if ($fullImageUrl): ?>
                <img src="<?= $fullImageUrl ?>"
                    alt="<?= htmlspecialchars($ad->title) ?>"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <div class="hidden w-full h-full items-center justify-center bg-gray-200 dark:bg-gray-800 text-gray-400">
                    <i class="bi bi-image text-3xl"></i>
                </div>
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-secondary-600 to-secondary-900 p-8 text-center">
                    <span class="text-white font-black text-2xl uppercase tracking-tighter opacity-20 select-none">
                        <?= htmlspecialchars($ad->title) ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <h3 class="text-xl font-black text-secondary-900 dark:text-white leading-tight">
                <?= htmlspecialchars($ad->title) ?>
            </h3>
            <span class="text-[10px] font-bold text-secondary-600 dark:text-secondary-400 uppercase tracking-widest">
                <?= htmlspecialchars($ad->package->package_name ?? 'Featured') ?>
            </span>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-6 font-medium leading-relaxed">
            <?= htmlspecialchars($ad->description) ?>
        </p>

        <button
            data-action="view-advert"
            <?php foreach ($adDataAttrs as $key => $val): ?>
            data-<?= $key ?>='<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>'
            <?php endforeach; ?>
            class="view-ad-trigger w-full py-4 bg-secondary-600 hover:bg-secondary-900 text-white font-black uppercase text-xs tracking-widest rounded-xl shadow-lg shadow-secondary-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
            <?= htmlspecialchars($cta_text) ?>
            <i class="bi bi-arrow-right text-lg"></i>
        </button>
    </div>
</div>