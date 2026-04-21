<?php
// /resources/views/pages/tenant-validation.php

// Page Icon: Shield with a checkmark for security
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>';
?>

<div id="validation-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Security Protocol Active
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-6">
                        Verified <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Tenant Trust</span>
                    </h1>

                    <p class="max-w-lg text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium">
                        Automate reference checks and validate prospective tenants through the industry's most robust verification engine.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Request Validation
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-6 py-4 rounded-xl bg-secondary-900 dark:bg-white/5 text-white text-sm font-black border border-transparent dark:border-white/10 backdrop-blur-md hover:bg-secondary-800 transition-all flex items-center justify-center">
                            Review History
                        </button>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform -rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 grid grid-rows-2 gap-6">
                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group" data-aos="fade-left">
                    <div class="absolute top-0 right-0 p-6">
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase tracking-widest border border-primary-500/20">System Integrity</div>
                    </div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Verification Accuracy</span>
                    <div class="text-6xl font-black text-white mt-2 tracking-tighter">99.9<span class="text-primary-500">%</span></div>
                    <div class="w-full h-2 bg-white/5 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-primary-500 w-[96%] animate-shimmer" style="background-size: 200% 100%;"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[10px] font-black uppercase tracking-widest">Turnaround</span>
                        <div class="text-3xl font-black text-secondary-900 dark:text-white mt-1">&lt;24h</div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[10px] font-black uppercase tracking-widest">Trust Index</span>
                        <div class="text-3xl font-black text-secondary-900 dark:text-white mt-1">AAA</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $steps = [
                ['Reference Auto', 'Instant reach out to landlords.', 'primary'],
                ['Secure Vault', 'Managed tenant document storage.', 'secondary'],
                ['Fraud Check', 'Authentic data cross-referencing.', 'primary'],
                ['Trust Metrics', 'Reputation scores for tenants.', 'secondary']
            ];
            foreach ($steps as $s): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-start gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $s[2] ?>-500/10 flex items-center justify-center text-<?= $s[2] ?>-500 group-hover:bg-<?= $s[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4" />
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

<style>
    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    .animate-shimmer {
        animation: shimmer 60s linear infinite;
    }
</style>