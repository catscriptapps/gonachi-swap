<?php
// /resources/views/pages/my-quotations.php

use Src\Service\AuthService;
use Src\Controller\QuotationsController;

if (AuthService::isLoggedIn()) {

    // 1. Manually boot the controller logic
    $quoteController = new QuotationsController();
    $quoteController->index();

    // 2. Access the $GLOBALS set in the controller
    $quotationCards = $GLOBALS['quotationCards'] ?? '';
    $totalCount = $GLOBALS['totalCount'] ?? 0;
?>

    <div id="my-quotes-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-20 transition-colors duration-300">
        <div class="max-w-7xl mx-auto pt-12 px-4 sm:px-6 lg:px-8">

            <div data-aos="fade-down" data-aos-duration="800"
                class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-900 p-8 lg:p-12 shadow-xl border border-gray-200 dark:border-gray-800 mb-10">
                <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary-400/10 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl font-black text-secondary-900 dark:text-white mb-4 tracking-tight">
                            Find the Right Professional for your Project
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 max-w-2xl font-medium leading-relaxed">
                            Post a quotation request on Gonachi and let skilled contractors and professionals come to you with their best offers.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0">
                        <a href="<?= $baseUrl ?>quotations" data-partial
                            class="inline-flex items-center gap-2 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-2xl text-[10px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-300 hover:text-primary-500 hover:border-primary-500/30 hover:bg-white dark:hover:bg-gray-800 transition-all group shadow-sm active:scale-95">
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Go Back
                        </a>

                        <button id="create-new-quote-btn" class="px-8 py-4 bg-primary-400 hover:bg-primary-500 text-white font-black rounded-2xl shadow-lg shadow-primary-400/20 transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-3 create-quote-trigger">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Post a Quote
                        </button>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200"
                class="bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-6">

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="p-2 bg-secondary-400 rounded-lg text-white shadow-lg shadow-secondary-400/20 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-secondary-900 dark:text-white uppercase tracking-tight leading-none">My Quotations</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                                <span id="quotes-counter-number" class="text-primary-400"><?= $totalCount ?></span> Quotation Requests
                            </p>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" id="quote-search-input"
                            placeholder="Search by title, city or trade..."
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900 text-sm font-bold text-secondary-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-primary-400/10 focus:border-primary-400 transition-all duration-200 outline-none">

                        <div id="quote-search-loader" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden">
                            <div class="w-4 h-4 border-2 border-primary-400 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>

                <div id="my-quotes-container">
                    <?php if (empty($quotationCards)): ?>
                        <div id="empty-quotes-state" class="p-20 text-center" data-aos="zoom-in">
                            <div class="mb-4 flex justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <button class="text-primary-400 font-black hover:underline text-sm create-quote-trigger uppercase tracking-widest">
                                Post your first quotation request
                            </button>
                        </div>
                    <?php endif; ?>

                    <div id="quotes-grid" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($quotationCards) ? 'hidden' : '' ?>">
                        <?= $quotationCards ?>
                    </div>

                    <div id="quotes-load-more-sentinel" class="h-20 w-full flex justify-center items-center">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary-400 hidden" role="status"></div>
                    </div>

                    <div id="no-quotes-found-state" class="hidden py-10 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800 mb-6">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-secondary-900 dark:text-white mb-2">No matches found</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                            We couldn't find any quotations matching "<span id="quote-search-term-display" class="font-bold text-primary-400"></span>".
                        </p>
                        <button type="button" onclick="document.getElementById('quote-search-input').value = ''; document.getElementById('quote-search-input').dispatchEvent(new Event('input'));"
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
    if (!AuthService::isLoggedIn()) include __DIR__ . '/auth-required.php';
}
