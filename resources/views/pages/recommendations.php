<?php
// /resources/views/pages/recommendations.php

// Page Icon: Endorsement ribbon
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.757c1.246 0 2.25 1.004 2.25 2.25 0 .594-.232 1.164-.644 1.588l-7.02 7.153a1.5 1.5 0 01-2.11 0l-7.02-7.153A2.25 2.25 0 015.243 10H10V3a1 1 0 011-1h2a1 1 0 011 1v7z" /></svg>';
?>

<div id="recommendations-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Endorsement Engine Active
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-6">
                        Vouched for by <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">The Community</span>
                    </h1>

                    <p class="max-w-md text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium">
                        Find top-tier users recommended by their peers and groups across the global network.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Recommend Someone
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Find Recommended Users
                        </button>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform rotate-6 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">

                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 shadow-2xl relative overflow-hidden group" data-aos="fade-left">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block">Trust Connections</span>
                            <div class="text-5xl font-black text-white mt-1 tracking-tighter">8,290<span class="text-secondary-500">+</span></div>
                        </div>
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[9px] font-black uppercase border border-primary-500/30">Network Map</div>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-6 relative">
                        <div class="absolute top-1/2 left-0 w-full h-0.5 bg-white/5 -translate-y-1/2"></div>
                        <div class="relative z-10 w-10 h-10 rounded-xl bg-primary-500 shadow-lg flex items-center justify-center text-white scale-90 opacity-50">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </div>
                        <div class="relative z-10 w-14 h-14 rounded-2xl bg-secondary-500 shadow-2xl flex items-center justify-center text-white animate-pulse">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </div>
                        <div class="relative z-10 w-10 h-10 rounded-xl bg-primary-500 shadow-lg flex items-center justify-center text-white scale-90 opacity-50">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-2xl bg-white/5 border border-white/10">
                        <p class="text-[11px] text-gray-400 italic font-medium leading-relaxed">"Recommended by Landlords"</p>
                    </div>
                </div>

                <div class="flex-1 rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm">
                    <div class="flex -space-x-3 mb-4">
                        <?php foreach (['A', 'B', '+'] as $char): ?>
                            <div class="h-10 w-10 rounded-full border-2 border-white dark:border-gray-900 bg-<?= $char == '+' ? 'gray-400' : 'primary-500' ?> flex items-center justify-center text-white font-black text-xs"><?= $char ?></div>
                        <?php endforeach; ?>
                    </div>
                    <span class="text-[10px] font-black text-secondary-900 dark:text-white uppercase tracking-widest">Cross-User Validation</span>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $steps = [
                ['Peer Vouch', 'Direct individual endorsements.', 'primary'],
                ['Team Boost', 'Vouch for entire groups.', 'secondary'],
                ['Local Focus', 'Discovery by immediate region.', 'primary'],
                ['Dual Trust', 'Tenants endorsing Landlords.', 'secondary']
            ];
            foreach ($steps as $s): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $s[2] ?>-500/10 flex items-center justify-center text-<?= $s[2] ?>-500 group-hover:bg-<?= $s[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $s[0] ?></span>
                        <span class="text-[10px] text-gray-400 font-medium leading-none"><?= $s[1] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>