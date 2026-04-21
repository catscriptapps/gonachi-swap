<?php
// /resources/views/pages/my-adverts.php

use Src\Service\AuthService;
use Src\Controller\AdvertsController;

if (AuthService::isLoggedIn()) {

    // 1. Manually boot the controller logic
    $adController = new AdvertsController();
    $adController->index(false); // Fetches only user's ads

    // 2. Now the $GLOBALS set in the controller are actually available
    $advertCards = $GLOBALS['advertCards'] ?? '';
    $totalAdsCount = $GLOBALS['totalAdsCount'] ?? 0;
?>

    <div id="my-ads-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-20 transition-colors duration-300">
        <div class="max-w-7xl mx-auto pt-12 px-4 sm:px-6 lg:px-8">

            <div data-aos="fade-down" data-aos-duration="800"
                class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-900 p-8 lg:p-12 shadow-xl border border-gray-200 dark:border-gray-800 mb-10">

                <div class="absolute -top-12 -right-12 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col xl:flex-row items-center justify-between gap-8">
                    <div class="flex-1 text-center xl:text-left">
                        <h1 class="text-3xl font-black text-secondary-900 dark:text-white mb-4 tracking-tight">
                            Advertise your Brand on Gonachi
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 max-w-2xl font-medium leading-relaxed">
                            The Advert platform on Gonachi offers a powerful and cost-effective way for your brand to reach your target audience.
                        </p>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">

                        <a href="<?= $baseUrl ?>adverts" data-partial class="w-full md:w-auto px-8 py-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-secondary-900 dark:text-gray-300 font-black rounded-2xl transition-all flex items-center justify-center gap-3 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Go Back
                        </a>

                        <button id="create-new-ad-btn" class="w-full md:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-black rounded-2xl shadow-lg shadow-teal-600/20 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 create-ad-trigger">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            New Advert
                        </button>

                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200"
                class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-6">

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="p-2 bg-navy-600 rounded-lg text-black dark:text-white shadow-lg shadow-navy-600/20 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-navy-900 dark:text-white uppercase tracking-tight leading-none">My Adverts</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                                <span id="ads-counter-number" class="text-primary-600 dark:text-primary-400">0</span> Adverts
                            </p>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-orange-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" id="ad-search-input"
                            placeholder="Search by title or keywords..."
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900 text-sm font-bold text-navy-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all duration-200 outline-none">

                        <div id="search-loader" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden">
                            <div class="w-4 h-4 border-2 border-orange-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>

                <div id="my-ads-container">
                    <?php if (empty($advertCards)): ?>
                        <div id="empty-ads-state" class="p-20 text-center" data-aos="zoom-in">
                            <div class="mb-4 flex justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <button class="text-orange-600 font-black hover:underline text-sm create-ad-trigger uppercase tracking-widest">
                                Create your first advert
                            </button>
                        </div>
                    <?php endif; ?>

                    <div id="ads-grid" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($advertCards) ? 'hidden' : '' ?>">
                        <?= $advertCards ?>
                    </div>

                    <div id="ads-load-more-sentinel" class="h-20 w-full flex justify-center items-center">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-orange-600 hidden" role="status"></div>
                    </div>

                    <div id="empty-ads-state" class="hidden py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800 mb-6">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-navy-900 dark:text-white mb-2">No matches found</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                            We couldn't find any adverts matching "<span id="search-term-display" class="font-bold text-orange-600"></span>".
                        </p>
                        <button type="button" onclick="document.getElementById('ad-search-input').value = ''; document.getElementById('ad-search-input').dispatchEvent(new Event('input'));"
                            class="mt-6 text-sm font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest">
                            Clear Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    if (!AuthService::isLoggedIn()) include __DIR__ . '/auth-required.php';
}
