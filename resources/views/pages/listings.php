<?php
// /resources/views/pages/listings.php

use Src\Controller\ListingsController;

// 1. Boot controller for ALL listings
$listingController = new ListingsController();
$listingController->index(true); // Passing true for all listings

$listingCards = $GLOBALS['listingCards'] ?? '';
$totalCount = $GLOBALS['totalCount'] ?? 0;

$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
?>

<div id="listings-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300 overflow-hidden">

    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-[0.2em] mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                        </span>
                        Marketplace v3.0 Live
                    </div>

                    <h1 class="text-3xl lg:text-5xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.9] mb-5">
                        Premier <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Listings</span>
                    </h1>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium max-w-md">
                        Explore trending rentals, exclusive sales, and distinct professional services tailored to your location.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <?php if ($isLoggedIn): ?>
                            <a href="<?= $baseUrl ?>my-listings" data-partial class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                                View My Listings
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </a>
                            <a href="javascript:" id="post-new-listing-btn" class="w-full sm:w-auto px-8 py-4 bg-secondary-900 dark:bg-white/5 text-white dark:text-gray-300 font-black rounded-xl border border-transparent dark:border-white/10 transition-all hover:bg-secondary-800 flex items-center justify-center text-sm">
                                Post New
                            </a>

                        <?php else: ?>
                            <a href="<?= $baseUrl ?>login" data-login-button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                                View My Listings
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </a>
                            <a href="javascript:" class="register-btn w-full sm:w-auto px-8 py-4 bg-secondary-900 dark:bg-white/5 text-white dark:text-gray-300 font-black rounded-xl border border-transparent dark:border-white/10 transition-all hover:bg-secondary-800 flex items-center justify-center text-sm">
                                Register NOW
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="ml-1 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <line x1="19" y1="8" x2="19" y2="14" />
                                    <line x1="16" y1="11" x2="22" y2="11" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="absolute -bottom-12 -right-12 opacity-5 dark:opacity-10 transform -rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-72 h-72"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="flex-1 rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group" data-aos="fade-left">
                    <div class="absolute top-0 right-0 p-6">
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase tracking-widest border border-primary-500/20">Market View</div>
                    </div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Available Properties</span>
                    <div class="text-6xl font-black text-white mt-2 tracking-tighter">4,300<span class="text-primary-500">+</span></div>
                    <div class="w-full h-2 bg-white/5 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-500 to-secondary-500 w-[88%] animate-shimmer" style="background-size: 200% 100%;"></div>
                    </div>
                    <p class="mt-6 text-xs text-gray-400 italic">"Real-time liquidity across 12+ premium locations."</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[9px] font-black uppercase tracking-widest leading-none">Avg. Price</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-1">$2.4k</div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[9px] font-black uppercase tracking-widest leading-none">Locations</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-1">12+</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($isLoggedIn): ?>
            <div data-aos="fade-up" data-aos-delay="200"
                class="mt-12 bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-6">

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="p-2 bg-secondary-400 rounded-lg text-white shadow-lg shadow-primary-400/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-secondary-900 dark:text-white uppercase tracking-tight leading-none">Browse All Listings</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                                <span id="listings-counter-number" class="text-primary-400"><?= $totalCount ?></span> Available in Marketplace
                            </p>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="listing-search-input"
                            placeholder="Search marketplace..."
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900 text-sm font-bold focus:ring-4 focus:ring-primary-400/10 focus:border-primary-400 transition-all outline-none">
                    </div>
                </div>

                <div id="all-listings-container">
                    <?php if (empty($listingCards)): ?>
                        <div id="empty-listings-state" class="p-20 text-center">
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No listings available at the moment.</p>
                        </div>
                    <?php endif; ?>

                    <div id="listings-grid" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($listingCards) ? 'hidden' : '' ?>">
                        <?= $listingCards ?>
                    </div>

                    <div id="listings-load-more-sentinel" class="h-20 w-full flex justify-center items-center">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary-400 hidden"></div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
                <?php
                $features = [
                    ['Geo-Tagging', 'Filtered by your location.', 'primary'],
                    ['Multi-Category', 'Rentals, sales, and more.', 'secondary'],
                    ['Instant Verify', 'Trusted landlord status.', 'primary'],
                    ['Direct Chat', 'Connect via social hub.', 'secondary']
                ];
                foreach ($features as $i => $f): ?>
                    <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-md transition-all group">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $f[2] ?>-500/10 flex items-center justify-center text-<?= $f[2] ?>-500 group-hover:bg-<?= $f[2] ?>-500 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $f[0] ?></span>
                            <span class="text-[11px] text-gray-400 font-medium leading-tight"><?= $f[1] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif ?>

    </div>
</div>

<style>
    #listings-page .animate-shimmer {
        animation: list-shimmer 60s linear infinite;
    }

    #listings-page .animate-slide-r {
        animation: list-slide-r 1.5s ease-in-out infinite;
    }

    @keyframes list-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    @keyframes list-slide-r {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(5px);
        }
    }
</style>