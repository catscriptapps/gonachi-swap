<?php
// /resources/views/pages/adverts.php

use Src\Service\AuthService;
use Src\Controller\AdvertsController;

// 1. Boot controller for the public feed
$adController = new AdvertsController();
$adController->index(true); // Fetches all active targeted ads

$advertCards = $GLOBALS['advertCards'] ?? '';
$totalAdsCount = $GLOBALS['totalAdsCount'] ?? 0;

$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>';
?>

<div id="ads-platform-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300 overflow-hidden">

    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-600 dark:text-orange-400 text-[9px] font-black uppercase tracking-[0.2em] mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                        </span>
                        Ads Engine v2.0 Live
                    </div>

                    <h1 class="text-3xl lg:text-5xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.9] mb-5">
                        Unleash Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Brand Power</span>
                    </h1>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium max-w-md">
                        Connect with landlords, tenants, and pros to showcase your brand on the largest real estate social hub.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <a href="<?= $isLoggedIn ? $baseUrl . 'my-adverts' : $baseUrl . 'login' ?>"
                            <?= !$isLoggedIn ? 'data-login-button' : 'data-partial' ?>
                            class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 !bg-primary-600 hover:!bg-primary-500 !text-white font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 gap-2 text-sm no-underline border-none">
                            Get Started
                            <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>

                        <?php if (AuthService::isAdmin()): ?>
                            <a href="<?= $baseUrl ?>adverts-admin" data-partial
                                class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 !bg-secondary-600 hover:!bg-secondary-500 !text-white font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 gap-2 text-sm no-underline border-none">
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                Adverts Admin
                            </a>
                        <?php endif ?>

                        <?php if (!$isLoggedIn): ?>
                            <a href="javascript:"
                                class="register-btn inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 !bg-secondary-600 hover:!bg-secondary-500 !text-white font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 gap-2 text-sm no-underline border-none">
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                Register Now
                            </a>
                        <?php endif ?>

                        <div class="flex items-center justify-center w-full sm:w-auto px-5 py-4 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 text-[10px] font-black border border-gray-200 dark:border-white/10">
                            <span class="uppercase tracking-widest">Starting At</span>
                            <span class="text-primary-500 text-xl ml-2 font-black">$0</span>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 grid grid-rows-2 gap-6">
                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group" data-aos="fade-left">
                    <div class="absolute top-0 right-0 p-6">
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase tracking-widest border border-primary-500/20">Live Metrics</div>
                    </div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Platform Reach</span>
                    <div class="text-6xl font-black text-white mt-2 tracking-tighter">5,000<span class="text-primary-500">+</span></div>
                    <div class="w-full h-2 bg-white/5 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-500 to-secondary-500 w-[85%] animate-shimmer" style="background-size: 200% 100%;"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[9px] font-black uppercase tracking-widest leading-none">Impressions</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-2">12.4k</div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[9px] font-black uppercase tracking-widest leading-none">Avg. CTR</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-2">4.8%</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($isLoggedIn): ?>
            <div data-aos="fade-up" data-aos-delay="200"
                class="mt-12 bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="p-2 bg-secondary-400 rounded-lg text-white shadow-lg border border-gray-300 dark:border-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-navy-900 dark:text-white uppercase tracking-tight leading-none">Public Advert Feed</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                                <span id="ads-counter-number" class="text-primary-600 dark:text-primary-400"><?= $totalAdsCount ?></span> Available Offers
                            </p>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-orange-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" id="ad-search-input" placeholder="Search by brand or keyword..."
                            class="block w-full pl-11 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900 text-sm font-bold text-navy-900 dark:text-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">

                        <div id="search-loader" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden">
                            <div class="w-4 h-4 border-2 border-orange-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>

                <div id="my-ads-container">
                    <div id="ads-grid" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($advertCards) ? 'hidden' : '' ?>">
                        <?= $advertCards ?>
                    </div>

                    <div id="ads-load-more-sentinel" class="h-20 w-full flex justify-center items-center">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-orange-600 hidden" role="status"></div>
                    </div>

                    <div id="empty-ads-state" class="<?= !empty($advertCards) ? 'hidden' : '' ?> py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800 mb-6">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-navy-900 dark:text-white mb-2">No Adverts Found</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Try adjusting your search or check back later.</p>
                    </div>
                </div>

            </div>

        <?php else: ?>

            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
                <?php
                $benefits = [
                    ['Secure Payments', 'Bank-grade encryption for all.', 'secondary'],
                    ['24/7 Approval', 'Rapid moderation team support.', 'primary'],
                    ['Targeted Reach', '5k+ active real estate pros.', 'secondary'],
                    ['Live Feed Ads', 'Native community integration.', 'primary']
                ];
                foreach ($benefits as $i => $b): ?>
                    <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-md transition-all group">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $b[2] ?>-500/10 flex items-center justify-center text-<?= $b[2] ?>-500 group-hover:bg-<?= $b[2] ?>-500 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $b[0] ?></span>
                            <span class="text-[11px] text-gray-400 font-medium leading-tight"><?= $b[1] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif ?>
    </div>
</div>


<style>
    #ads-platform-page .animate-float {
        animation: ads-float 6s ease-in-out infinite;
    }

    #ads-platform-page .animate-shimmer {
        animation: ads-shimmer 60s linear infinite;
    }

    #ads-platform-page .animate-slide-r {
        animation: ads-slide-r 1.5s ease-in-out infinite;
    }

    @keyframes ads-float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(5deg);
        }
    }

    @keyframes ads-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>