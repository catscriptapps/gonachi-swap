<?php
// /resources/views/pages/adverts-admin.php

declare(strict_types=1);

$adminController = new \Src\Controller\AdvertsAdminController();
$adminController->index();

$advertRows = $GLOBALS['advertRows'] ?? '';
?>

<div id="adverts-administration" class="space-y-6 max-w-full overflow-x-hidden">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white font-sans tracking-tight">Adverts Administration</h1>
            <p class="mt-2 text-base text-gray-600 dark:text-gray-400 max-w-2xl leading-relaxed">
                Management hub for platform-wide promotions. Review pending submissions, monitor active campaigns, and maintain quality control over all Gonachi advertisements from this central directory.
            </p>
        </div>

        <div class="mt-4 md:mt-0 flex flex-row gap-3 items-center">
            <div class="relative flex-1 md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="adverts-search"
                    class="block w-full rounded-2xl border-2 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 py-2.5 pl-10 pr-3 text-sm focus:border-primary-500 focus:ring-primary-500 text-gray-900 dark:text-white transition-all font-sans"
                    placeholder="Search by title or owner...">
            </div>

            <a href="<?= $baseUrl ?>adverts" data-partial
                class="shrink-0 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-secondary-900 dark:text-gray-300 px-4 py-2.5 text-sm font-bold transition-all active:scale-95">
                <svg class="w-5 h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 7l-5 5m0 0l5 5m-5-5h12" />
                </svg>
                <span class="hidden xs:inline md:inline">Go Back</span>
            </a>
        </div>
    </div>

    <div class="relative mt-8">
        <div class="flex flex-wrap gap-1 px-4 mb-[-2px] relative z-10">
            <?php
            $tabs = [
                ['id' => 'all', 'label' => 'All Adverts'],
                ['id' => 'pending', 'label' => 'Pending Review'],
                ['id' => 'active', 'label' => 'Active'],
                ['id' => 'inactive', 'label' => 'Inactive'],
                ['id' => 'rejected', 'label' => 'Rejected']
            ];
            foreach ($tabs as $index => $tab): ?>
                <button type="button"
                    data-status="<?= $tab['id'] ?>"
                    class="advert-tab px-6 py-3 text-sm font-bold rounded-t-2xl transition-all duration-200 border-x-2 border-t-2 <?= $index === 0 ? 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 text-primary-600' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 hover:bg-gray-200' ?>">
                    <?= $tab['label'] ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="bg-white dark:bg-gray-900 shadow-xl border-2 border-gray-200 dark:border-gray-800 rounded-2xl rounded-tl-none overflow-hidden relative z-0">
            <div class="w-full overflow-x-auto">
                <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-full lg:w-[35%]">Advert Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[20%] hidden lg:table-cell">Owner</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%] hidden sm:table-cell">Package</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%] hidden xl:table-cell">Created</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%] hidden md:table-cell">Status</th>
                        </tr>
                    </thead>
                    <tbody id="adverts-tbody" class="divide-y divide-gray-200 dark:divide-gray-800">
                        <?php if (empty($advertRows)): ?>
                            <tr id="adverts-empty-state">
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-40">
                                        <svg class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-xl font-bold font-sans">No adverts found in this category</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?= $advertRows ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="ad-admin-load-more-sentinel" class="flex justify-center p-8">
                <div class="spinner-border hidden text-primary"></div>
            </div>

            <?php $footerCountName = 'adverts';
            include __DIR__ . '/../components/ui/footer-count.php'; ?>
        </div>
    </div>
</div>