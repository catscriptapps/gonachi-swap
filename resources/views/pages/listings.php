<?php
// /resources/views/pages/listings.php

declare(strict_types=1);

use Src\Controller\ListingsController;
use App\Models\ListingCategory;
use Src\Service\AuthService;

/** @var string $assetBase */
/** @var string $baseUrl */
/** @var array $allCategories */

if (AuthService::isLoggedIn()) {

    // 1. Fetch Categories using Eloquent
    $allCategories = ListingCategory::where('status_id', 1)->get();

    // 2. Boot controller for dynamic listings
    $listingController = new ListingsController();
    $listingController->index(true);

    $listingCards = $GLOBALS['listingCards'] ?? '';
    $totalCount   = $GLOBALS['totalCount'] ?? 0;
?>

    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 font-sans" x-data="{ showFilters: false }">

        <header class="mb-12" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/10 text-primary-500 text-[10px] font-black uppercase tracking-widest mb-4 border border-primary-500/20">
                Marketplace Live
            </div>
            <h1 class="text-4xl lg:text-6xl font-black text-secondary-900 dark:text-white leading-none tracking-tighter">
                Current <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-orange-600">Listings</span>
            </h1>
            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                <span id="listings-counter-number" class="text-primary-400"><?= $totalCount ?></span> Active Listings
            </p>
        </header>

        <div class="w-full">
            <main>

                <!-- Action Bar: Search + Responsive Buttons -->
                <div class="flex flex-col md:flex-row gap-4 mb-10" data-aos="fade-up">
                    <!-- Search Input -->
                    <div class="relative flex-grow group">
                        <input type="text" id="listing-search-input" placeholder="Search swaps..."
                            class="w-full pl-14 pr-12 py-5 bg-white dark:bg-secondary-950 border-2 border-gray-100 dark:border-white/5 rounded-[2rem] text-secondary-900 dark:text-white font-bold focus:border-primary-500 outline-none transition-all shadow-lg shadow-gray-200/50 dark:shadow-none">
                        <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <div id="listing-search-loader" class="absolute right-6 top-1/2 -translate-y-1/2 hidden">
                            <div class="animate-spin w-5 h-5 border-2 border-primary-500 border-t-transparent rounded-full"></div>
                        </div>
                    </div>

                    <!-- Action Buttons Container -->
                    <div class="flex gap-3 h-[64px]">
                        <!-- Toggle Categories Button -->
                        <button @click="showFilters = !showFilters"
                            :class="showFilters ? 'border-primary-500 bg-primary-50 dark:bg-primary-950/20' : ''"
                            class="flex-1 md:flex-none px-6 bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white border-2 border-gray-100 dark:border-white/5 rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest hover:border-primary-500 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Categories
                        </button>

                        <a href="<?= $baseUrl ?>my-listings" data-partial class="flex-1 md:flex-none px-6 bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white border-2 border-gray-100 dark:border-white/5 rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest hover:border-primary-500 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            My Items
                        </a>
                        <button id="create-new-listing-btn" class="flex-1 md:flex-none px-6 bg-primary-500 text-secondary-950 rounded-[1.5rem] font-black text-[11px] uppercase tracking-widest hover:bg-secondary-950 hover:text-white transition-all shadow-lg shadow-primary-500/20 flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Post New
                        </button>
                    </div>
                </div>

                <!-- Expanded Filter Panel -->
                <div x-show="showFilters" x-collapse x-cloak class="mb-10" data-aos="fade-down">
                    <div class="bg-white dark:bg-secondary-950 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-secondary-900 dark:text-white mb-8 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Categories
                        </h3>

                        <div class="flex flex-wrap gap-x-12 gap-y-6">
                            <label class="group flex items-center gap-4 cursor-pointer">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" id="filter-all" checked class="category-checkbox peer appearance-none w-6 h-6 border-2 border-gray-100 dark:border-white/10 rounded-lg checked:bg-primary-500 checked:border-primary-500 transition-all">
                                    <svg class="absolute w-4 h-4 text-secondary-950 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-secondary-900 dark:text-gray-300 group-hover:text-primary-500 transition-colors">Show All Items</span>
                            </label>

                            <?php foreach ($allCategories as $cat): ?>
                                <label class="group flex items-center gap-4 cursor-pointer">
                                    <div class="relative flex items-center justify-center">
                                        <input type="checkbox" name="category[]" value="<?= $cat->category_id ?>" class="category-checkbox peer appearance-none w-6 h-6 border-2 border-gray-100 dark:border-white/10 rounded-lg checked:bg-primary-500 checked:border-primary-500 transition-all">
                                        <svg class="absolute w-4 h-4 text-secondary-950 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-500 dark:text-gray-400 group-hover:text-primary-500 transition-colors">
                                        <?= htmlspecialchars($cat->category_name) ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div id="all-listings-container">
                    <?php if (empty($listingCards)): ?>
                        <div id="empty-listings-state" class="p-32 text-center bg-white dark:bg-secondary-950 rounded-[3rem] border border-dashed border-gray-200 dark:border-white/10">
                            <p class="text-gray-400 font-black uppercase tracking-widest text-sm">No items found matching your filters.</p>
                        </div>
                    <?php endif; ?>

                    <!-- No Results Found State -->
                    <div id="no-listings-found-state" class="hidden p-32 text-center bg-white dark:bg-secondary-950 rounded-[3rem] border border-dashed border-gray-200 dark:border-white/10">
                        <p class="text-gray-400 font-black uppercase tracking-widest text-sm mb-2">No items found matching "<span id="listing-search-term-display" class="text-primary-500 lowercase"></span>".</p>
                        <p class="text-xs text-gray-500 font-bold">Try adjusting your search keywords or categories.</p>
                    </div>

                    <div id="listings-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 <?= empty($listingCards) ? 'hidden' : '' ?>">
                        <?= $listingCards ?>
                    </div>

                    <div id="listings-load-more-sentinel" class="h-24 w-full flex justify-center items-center mt-12">
                        <div class="animate-spin inline-block w-8 h-8 border-4 border-primary-500 border-t-transparent rounded-full hidden"></div>
                    </div>
                </div>
            </main>
        </div>
    </div><?php
        } else {
            include __DIR__ . '/../components/listings/guest-landing.php';
        }
