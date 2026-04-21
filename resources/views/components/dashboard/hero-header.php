<?php
// /resources/views/components/dashboard/hero-header.php

// Inherits $userName, $appName, etc. from dashboard.php scope
?>

<div class="relative overflow-hidden rounded-[2.5rem] bg-gray-50 dark:bg-gray-900/50 p-8 lg:p-12 shadow-xl border border-gray-200/60 dark:border-white/5 transition-colors duration-300">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-secondary-500/5 opacity-100"></div>
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-400/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-secondary-400/10 rounded-full blur-3xl"></div>

    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="flex items-center gap-6">
            <?php if (isset($pageIcon)): ?>
                <div class="hidden lg:flex w-16 h-16 items-center justify-center rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-primary-600 dark:text-primary-400 shadow-sm backdrop-blur-md animate-float">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5"$2', $pageIcon) ?>
                </div>
            <?php endif; ?>

            <div>
                <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white leading-tight">
                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500 dark:from-primary-400 dark:to-secondary-400"><?= htmlspecialchars($userName) ?></span>
                </h1>

                <div class="mt-2 flex items-center gap-3">
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-500/10 border border-secondary-500/20 text-secondary-600 dark:text-secondary-400 text-[10px] font-black uppercase tracking-widest">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary-500"></span>
                        </span>
                        System Online
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium border-l border-gray-300 dark:border-white/10 pl-3">
                        The <?= $appName ?> - Real Estate World Hub
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-start md:items-end">
            <div class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">Local Time</div>
            <div class="px-6 py-3 bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl shadow-sm dark:shadow-inner">
                <span class="text-xl font-black text-gray-800 dark:text-white"><?= date('F j, Y') ?></span>
            </div>
        </div>
    </div>
</div>