<?php
// /resources/views/pages/home-buyers.php

// Page Icon: A Key
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>';
?>

<div id="home-buyers-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-8 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-14" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-primary-500 animate-pulse"></span>
                        Market Matching Active
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.9] mb-6">
                        The Path to <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Ownership</span>
                    </h1>

                    <p class="max-w-xl text-lg text-gray-500 dark:text-gray-400 mb-10 leading-relaxed font-medium">
                        Bridging the gap between prospective buyers and elite real estate professionals. Find your future home or discover qualified capital.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Add Buyer Profile
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Find Home Buyers
                        </button>
                    </div>
                </div>

                <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <div class="lg:col-span-4 rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 shadow-2xl relative overflow-hidden group flex flex-col justify-between" data-aos="fade-left">
                <div class="flex justify-between items-start mb-6">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-width="2" />
                        </svg>
                    </div>
                    <span class="px-2 py-0.5 bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase rounded border border-primary-500/20">High Intent</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-1">Target Region</span>
                        <div class="text-xl font-bold text-white">Ontario, Canada</div>
                    </div>
                    <div class="h-[1px] bg-white/5 w-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="h-1.5 w-1.5 rounded-full bg-secondary-500 animate-pulse"></div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Pre-Qualified Match</span>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-3">
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5 text-center">
                        <div class="text-lg font-black text-white leading-none">2.4k</div>
                        <div class="text-[7px] text-gray-500 uppercase font-black mt-1">Seekers</div>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5 text-center">
                        <div class="text-lg font-black text-white leading-none">850</div>
                        <div class="text-[7px] text-gray-500 uppercase font-black mt-1">Agents</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $ecosystem = [
                ['Buyer Criteria', 'Detailed purchase requirements.', 'primary'],
                ['Realtor Access', 'High-intent buyer database.', 'secondary'],
                ['Market Sync', 'Regional buyer trends.', 'primary'],
                ['Integration', 'Manage buyer/seller settings.', 'secondary']
            ];
            foreach ($ecosystem as $item): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $item[2] ?>-500/10 flex items-center justify-center text-<?= $item[2] ?>-500 group-hover:bg-<?= $item[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $item[0] ?></span>
                        <span class="text-[10px] text-gray-400 font-medium leading-none"><?= $item[1] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>