<?php
// /resources/views/pages/ratings.php

// Page Icon: Analytics/Bar Chart
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0h6m2 0h2a2 2 0 002-2v-4a2 2 0 00-2-2h-2a2 2 0 00-2 2v4a2 2 0 002 2z" /></svg>';
?>

<div id="ratings-analytics-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Global Analytics Online
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-6">
                        The Gold Standard <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">of Global Trust</span>
                    </h1>

                    <p class="max-w-md text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium">
                        Browse verified ratings for service providers and contractors. Build a more transparent real estate world.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Rate Someone
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Browse by Location
                        </button>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform -rotate-6 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">

                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 shadow-2xl relative overflow-hidden" data-aos="fade-left">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block">Reliability Index</span>
                            <div class="text-2xl font-black text-white">98.2%</div>
                        </div>
                        <div class="h-10 w-10 rounded-xl bg-primary-500/20 flex items-center justify-center text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-end gap-2.5 h-24 mb-6">
                        <div class="flex-1 bg-primary-500/20 rounded-t-lg h-[40%] animate-grow-up"></div>
                        <div class="flex-1 bg-primary-500/40 rounded-t-lg h-[65%] animate-grow-up" style="animation-delay: 0.2s"></div>
                        <div class="flex-1 bg-primary-500/60 rounded-t-lg h-[85%] animate-grow-up" style="animation-delay: 0.4s"></div>
                        <div class="flex-1 bg-primary-500 rounded-t-lg h-[55%] animate-grow-up" style="animation-delay: 0.6s"></div>
                        <div class="flex-1 bg-secondary-400 rounded-t-lg h-[95%] animate-grow-up" style="animation-delay: 0.8s"></div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-[10px] font-black text-gray-300 uppercase tracking-tighter">Live Network Growth</span>
                        </div>
                        <span class="text-xs font-black text-emerald-400">+12%</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 flex-1">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm">
                        <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest">Active Ratings</span>
                        <div class="text-3xl font-black text-secondary-900 dark:text-white mt-1">42.8k</div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm">
                        <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest">Categories</span>
                        <div class="text-3xl font-black text-secondary-900 dark:text-white mt-1">18+</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $metrics = [
                ['Smart Filtering', 'Filter by group & trade.', 'primary'],
                ['Geo Search', 'Local provider discovery.', 'secondary'],
                ['Peer Verified', 'Real engagement only.', 'primary'],
                ['Reputation Score', 'Proprietary trust algorithm.', 'secondary']
            ];
            foreach ($metrics as $m): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $m[2] ?>-500/10 flex items-center justify-center text-<?= $m[2] ?>-500 group-hover:bg-<?= $m[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $m[0] ?></span>
                        <span class="text-[10px] text-gray-400 font-medium leading-none"><?= $m[1] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<style>
    #ratings-analytics-page .animate-grow-up {
        animation: grow-up 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transform-origin: bottom;
        transform: scaleY(0);
    }

    @keyframes grow-up {
        to {
            transform: scaleY(1);
        }
    }
</style>