<?php
// /resources/views/pages/my-listings.php

use Src\Service\AuthService;
use Src\Controller\ListingsController;

if (AuthService::isLoggedIn()) {

    // 1. Manually boot the controller logic
    $listingController = new ListingsController();
    $listingController->index();

    // 2. Access the $GLOBALS set in the controller
    $listingCards = $GLOBALS['listingCards'] ?? '';
    $totalCount = $GLOBALS['totalCount'] ?? 0;
?>

    <div id="my-listings-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-20 transition-colors duration-300">
        <div class="max-w-7xl mx-auto pt-12 px-4 sm:px-6 lg:px-8">

            <div data-aos="fade-down" data-aos-duration="800"
                class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-900 p-8 lg:p-12 shadow-xl border border-gray-200 dark:border-gray-800 mb-10">

                <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary-400/10 rounded-full blur-3xl"></div>

                <div class="relative">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl font-black text-secondary-900 dark:text-white mb-4 tracking-tight">
                                Manage Your Marketplace Listings
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 max-w-2xl font-medium leading-relaxed">
                                List your property or service on Gonachi. Reach thousands of potential tenants and clients by showcasing what you have to offer.
                            </p>
                        </div>

                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="<?= $baseUrl ?>listings" data-partial
                                class="inline-flex items-center gap-2 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-2xl text-[10px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-300 hover:text-primary-500 hover:border-primary-500/30 hover:bg-white dark:hover:bg-gray-800 transition-all group shadow-sm active:scale-95">
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                </svg>
                                Go Back
                            </a>

                            <button id="create-new-listing-btn" class="px-8 py-4 bg-primary-400 hover:bg-primary-500 text-white font-black rounded-2xl shadow-lg shadow-primary-400/20 transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-3 create-listing-trigger">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Post a Listing
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200"
                class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-6">

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="p-2 bg-secondary-400 rounded-lg text-white shadow-lg shadow-secondary-400/20 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-secondary-900 dark:text-white uppercase tracking-tight leading-none">My Listings</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                                <span id="listings-counter-number" class="text-primary-400"><?= $totalCount ?></span> Active Listings
                            </p>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" id="listing-search-input"
                            placeholder="Search by title, city or category..."
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900 text-sm font-bold text-secondary-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-primary-400/10 focus:border-primary-400 transition-all duration-200 outline-none">

                        <div id="listing-search-loader" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden">
                            <div class="w-4 h-4 border-2 border-primary-400 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>

                <div id="my-listings-container">
                    <?php if (empty($listingCards)): ?>
                        <div id="empty-listings-state" class="p-20 text-center" data-aos="zoom-in">
                            <div class="mb-4 flex justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <button class="text-primary-400 font-black hover:underline text-sm create-listing-trigger uppercase tracking-widest">
                                Post your first marketplace listing
                            </button>
                        </div>
                    <?php endif; ?>

                    <div id="listings-grid" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($listingCards) ? 'hidden' : '' ?>">
                        <?= $listingCards ?>
                    </div>

                    <div id="listings-load-more-sentinel" class="h-20 w-full flex justify-center items-center">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary-400 hidden" role="status"></div>
                    </div>

                    <div id="no-listings-found-state" class="hidden py-10 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800 mb-6">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-secondary-900 dark:text-white mb-2">No matches found</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                            We couldn't find any listings matching "<span id="listing-search-term-display" class="font-bold text-primary-400"></span>".
                        </p>
                        <button type="button" onclick="document.getElementById('listing-search-input').value = ''; document.getElementById('listing-search-input').dispatchEvent(new Event('input'));"
                            class="mt-6 text-sm font-bold text-primary-400 hover:text-primary-500 uppercase tracking-widest">
                            Clear Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

} else {
    include __DIR__ . '/auth-required.php';
}
