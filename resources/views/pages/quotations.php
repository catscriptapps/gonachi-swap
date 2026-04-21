<?php
// /resources/views/pages/quotations.php

use Src\Controller\QuotationsController;

// Page Icon: Document with a pricing/check-list feel
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';

// 1. Boot controller with $all = true to handle BOTH all and mine
$quoteController = new QuotationsController();
$quoteController->index(true);

// 2. Access the $GLOBALS set in the controller
$quotationCards = $GLOBALS['quotationCards'] ?? '';
$totalCount = $GLOBALS['totalCount'] ?? 0;
?>

<div id="quotations-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300 overflow-hidden">

    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-[0.2em] mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                        </span>
                        Bidding Engine Active
                    </div>

                    <h1 class="text-3xl lg:text-5xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-5">
                        Smart <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Contractor Quotes</span>
                    </h1>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium max-w-md">
                        Fill out your request, upload media, and receive competitive bids from verified contractors instantly.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <?php
                        $btnClass = "inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 gap-2 text-sm no-underline border-none";
                        ?>

                        <?php if ($isLoggedIn): ?>
                            <a href="javascript:" id="request-quotation-btn" class="<?= $btnClass ?> !bg-primary-600 hover:!bg-primary-500 !text-white">
                                Request a Quotation
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <a href="<?= $baseUrl ?>my-quotations" data-partial class="<?= $btnClass ?> !bg-secondary-800 dark:!bg-white/5 !text-white">
                                View My Requests
                            </a>
                        <?php else: ?>
                            <a href="<?= $baseUrl ?>login" data-login-button class="<?= $btnClass ?> !bg-primary-600 hover:!bg-primary-500 !text-white">
                                Request a Quotation
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <a href="javascript:" class="group register-btn <?= $btnClass ?> !bg-secondary-800 dark:!bg-white/10 !text-white inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl transition-all hover:scale-[1.02] active:scale-95">
                                <span>Register NOW</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <line x1="19" y1="8" x2="19" y2="14" />
                                    <line x1="16" y1="11" x2="22" y2="11" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 grid grid-rows-2 gap-6">
                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group" data-aos="fade-left">
                    <div class="absolute top-0 right-0 p-6">
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase tracking-widest border border-primary-500/20">Network Status</div>
                    </div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Active Contractors</span>
                    <div class="text-6xl font-black text-white mt-2 tracking-tighter">1,200<span class="text-primary-500">+</span></div>
                    <div class="w-full h-2 bg-white/5 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-primary-500 w-[70%] animate-shimmer" style="background-size: 200% 100%;"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[9px] font-black uppercase tracking-widest leading-none">Avg Response</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-2">2.4<span class="text-xs ml-1">hrs</span></div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[9px] font-black uppercase tracking-widest leading-none">Jobs Done</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-2">8.9k</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($isLoggedIn): ?>
            <div data-aos="fade-up" data-aos-delay="200" class="mt-12 bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-6">

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="p-2 bg-secondary-400 rounded-lg text-white shadow-lg shadow-secondary-400/20 border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-secondary-900 dark:text-white uppercase tracking-tight leading-none">Browse All Quotations</h2>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">
                                <span id="quotes-counter-number" class="text-primary-400"><?= $totalCount ?></span> Active Requests
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
                            <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">No quotation requests available at the moment.</p>
                        </div>
                    <?php endif; ?>

                    <div id="quotes-grid" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($quotationCards) ? 'hidden' : '' ?>">
                        <?= $quotationCards ?>
                    </div>

                    <div id="quotes-load-more-sentinel" class="h-20 w-full flex justify-center items-center">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-primary-400 hidden" role="status"></div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
                <?php
                $steps = [
                    ['Describe Need', 'Detail your project in our smart form.', 'primary'],
                    ['Upload Media', 'Add pics/videos for better accuracy.', 'secondary'],
                    ['Receive Bids', 'Get competitive pricing in real-time.', 'primary'],
                    ['Hire & Track', 'Choose the best fit and track progress.', 'secondary']
                ];
                foreach ($steps as $i => $s): ?>
                    <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-start gap-4 hover:shadow-md transition-all group">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $s[2] ?>-500/10 flex items-center justify-center text-<?= $s[2] ?>-500 group-hover:bg-<?= $s[2] ?>-500 group-hover:text-white transition-all">
                            <span class="text-sm font-black"><?= $i + 1 ?></span>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $s[0] ?></span>
                            <span class="text-[11px] text-gray-400 font-medium leading-tight"><?= $s[1] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif ?>

    </div>
</div>


<style>
    /* Standardized Keyframes */
    #quotations-page .animate-shimmer {
        animation: quote-shimmer 3s linear infinite;
    }

    #quotations-page .animate-slide-r {
        animation: quote-slide-r 1.5s ease-in-out infinite;
    }

    @keyframes quote-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    @keyframes quote-slide-r {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(5px);
        }
    }
</style>