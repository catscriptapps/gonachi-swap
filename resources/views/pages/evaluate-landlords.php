<?php
// /resources/views/pages/evaluate-landlords.php

// Page Icon: A star representing ratings
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>';
?>

<div id="evaluate-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Tenant Empowerment Active
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-6">
                        Elevate the <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Rental Standard</span>
                    </h1>

                    <p class="max-w-md text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium">
                        Search and evaluate landlords based on communication, maintenance, and fairness. Help the global community.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Evaluate a Landlord
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Browse Evaluations
                        </button>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">

                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group shadow-2xl" data-aos="fade-left">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-primary-500 to-secondary-400 flex items-center justify-center text-white text-xs font-black">JD</div>
                        <div>
                            <h4 class="text-xs font-black text-white leading-none">John Doe</h4>
                            <span class="text-[9px] text-gray-500 uppercase tracking-widest">Verified Landlord</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-1">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <div class="px-2.5 py-1 bg-primary-500/20 text-primary-400 text-[9px] font-black rounded-lg inline-block border border-primary-500/20 uppercase tracking-tighter">Timely Maintenance</div>
                        <p class="text-[12px] text-gray-400 italic font-medium leading-relaxed">"Very understanding and flexible with payment dates during my transition period."</p>
                    </div>
                </div>

                <div class="flex-1 rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 shadow-sm overflow-hidden relative">
                    <div class="grid grid-cols-2 gap-4 h-full">
                        <div class="space-y-2 border-r border-gray-100 dark:border-white/5 pr-4">
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest block mb-2">Positives</span>
                            <?php foreach (['Response', 'Communication', 'Maintenance', 'Fairness'] as $p): ?>
                                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-600 dark:text-gray-300">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> <?= $p ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="space-y-2 pl-2">
                            <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest block mb-2">Warnings</span>
                            <?php foreach (['Delays', 'Poor Comms', 'Inflexible', 'Hidden Fees'] as $n): ?>
                                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-600 dark:text-gray-300">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div> <?= $n ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $metrics = [
                ['Communication', '92% Satisfaction', 'primary'],
                ['Maintenance', 'Average 24h response', 'secondary'],
                ['Fairness', 'Transparency focus', 'primary'],
                ['Verification', 'Community vetted', 'secondary']
            ];
            foreach ($metrics as $m): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 text-center group hover:bg-<?= $m[2] ?>-500/5 transition-all">
                    <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $m[0] ?></span>
                    <span class="text-[10px] text-<?= $m[2] ?>-500 font-black uppercase tracking-tighter"><?= $m[1] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>