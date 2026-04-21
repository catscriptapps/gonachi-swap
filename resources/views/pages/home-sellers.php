<?php
// /resources/views/pages/home-sellers.php
$title = 'Home Sellers | Gonachi Real Estate';

$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>';
?>

<div id="home-sellers-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-8 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-14" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-secondary-500/10 border border-secondary-500/20 text-secondary-600 dark:text-secondary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-secondary-500 animate-pulse"></span>
                        Seller Network Active
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.9] mb-6">
                        Maximize Your <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary-400 to-primary-500">Property Value</span>
                    </h1>

                    <p class="max-w-xl text-lg text-gray-500 dark:text-gray-400 mb-10 leading-relaxed font-medium">
                        Connect with verified realtors holding ready-to-act buyers. Detailed property insights lead to faster, transparent sales.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Add Listing Info
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center text-center">
                            Find Prospective Sellers
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 flex flex-col gap-4" data-aos="fade-left">

                <div class="rounded-3xl bg-secondary-950 p-6 border border-white/5 shadow-xl relative overflow-hidden group mt-6">
                    <div class="flex justify-between items-center relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-secondary-500 flex items-center justify-center text-white shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8" stroke-width="2" />
                                </svg>
                            </div>
                            <span class="text-[12px] font-black text-white uppercase tracking-widest">Market Status</span>
                        </div>
                        <div class="text-xl font-black text-green-400 tracking-tighter">+12.4%</div>
                    </div>
                    <div class="mt-4 h-2 w-full bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-500 rounded-full w-3/4 animate-shimmer"></div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 flex-1">
                    <?php
                    $steps = [
                        ['Define Asset', 'Input location and price.', 'primary', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2'],
                        ['Pro Match', 'Top Realtors browse listings.', 'secondary', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        ['Market Sync', 'Live to Buyer Network.', 'primary', 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6']
                    ];
                    foreach ($steps as $i => $s): ?>
                        <div class="p-4 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 group hover:bg-gray-50 dark:hover:bg-white/[0.08] transition-all">
                            <div class="h-11 w-11 shrink-0 rounded-2xl bg-<?= $s[2] ?>-500/10 flex items-center justify-center text-<?= $s[2] ?>-500 border border-<?= $s[2] ?>-500/20 group-hover:bg-<?= $s[2] ?>-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $s[3] ?>" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-black text-<?= $s[2] ?>-500 uppercase">Phase 0<?= $i + 1 ?></span>
                                    <div class="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-700"></div>
                                    <span class="font-black text-secondary-900 dark:text-white uppercase truncate"><?= $s[0] ?></span>
                                </div>
                                <p class="text-[12px] text-gray-500 font-medium truncate"><?= $s[1] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up">
            <?php
            $advantages = [
                ['Targeted Reach', 'Realtor-facing property details.', 'primary'],
                ['Elite Filtering', 'Target by location/type.', 'secondary'],
                ['Stealth Mode', 'Privacy and visibility toggle.', 'primary'],
                ['Vetted Leads', '100% professional vetting.', 'secondary']
            ];
            foreach ($advantages as $i => $item): ?>
                <div class="group p-6 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 hover:border-<?= $item[2] ?>-500/50 transition-all shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-<?= $item[2] ?>-500/10 flex items-center justify-center text-<?= $item[2] ?>-500 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="block text-sm font-black text-secondary-900 dark:text-white mb-1 uppercase tracking-tight"><?= $item[0] ?></span>
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium leading-snug"><?= $item[1] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>