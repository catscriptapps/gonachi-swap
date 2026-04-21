<?php
// /resources/views/pages/protect-rentals.php

// Page Icon: Shield with User/Property check
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>';
?>

<div id="protect-rentals-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Community Intelligence Active
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-6">
                        Protect Your <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Rental Legacy</span>
                    </h1>

                    <p class="max-w-lg text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium">
                        Share rental experiences and track payment reliability in a transparent ecosystem built by landlords, for landlords.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Add a Property
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Search Experiences
                        </button>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform -rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 grid grid-rows-2 gap-6">

                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group shadow-2xl" data-aos="fade-left">
                    <div class="flex justify-between items-start mb-6">
                        <div class="h-10 w-10 rounded-xl bg-primary-500/20 flex items-center justify-center text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-width="2.5" />
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-tighter border border-emerald-500/20 rounded-lg">Favourable</span>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-300">
                            <svg class="w-3.5 h-3.5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                            </svg>
                            Reliable Payments
                        </div>
                        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-300">
                            <svg class="w-3.5 h-3.5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                            </svg>
                            Clear Communication
                        </div>
                    </div>
                    <p class="text-[12px] text-gray-400 italic font-medium">"Consistent, high-quality tenancy."</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[10px] font-black uppercase tracking-widest">Shared Logs</span>
                        <div class="text-3xl font-black text-secondary-900 dark:text-white mt-1">1.8k+</div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[10px] font-black uppercase tracking-widest">Trust Index</span>
                        <div class="text-3xl font-black text-secondary-900 dark:text-white mt-1">94%</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $intel = [
                ['Property Logs', 'Track historical interactions.', 'primary'],
                ['Experience Hub', 'Submit conduct reviews.', 'secondary'],
                ['Landlord Ratings', 'Verify posting integrity.', 'primary'],
                ['Secure Discuss', 'Direct owner communication.', 'secondary']
            ];
            foreach ($intel as $s): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-start gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $s[2] ?>-500/10 flex items-center justify-center text-<?= $s[2] ?>-500 group-hover:bg-<?= $s[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $s[0] ?></span>
                        <span class="text-[11px] text-gray-400 font-medium leading-tight"><?= $s[1] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>