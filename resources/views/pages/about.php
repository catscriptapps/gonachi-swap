<?php
// /resources/views/pages/swap.php

declare(strict_types=1);

/** @var string $baseUrl */
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 font-sans overflow-hidden">

    <section class="text-center mb-32 relative pt-20">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-gradient-to-b from-primary-400/10 to-transparent rounded-[3rem] blur-3xl -z-10"></div>

        <div class="relative" data-aos="zoom-out" data-aos-duration="1200">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-[0.3em] mb-8 border border-primary-100 dark:border-primary-800 animate-soft-pulse">
                Revolutionary Exchange
            </div>
            <h1 class="text-6xl lg:text-8xl font-black text-secondary-900 dark:text-white tracking-tighter mb-8 leading-[0.9]">
                Trade. Barter. <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-600">Gonachi Swap.</span>
            </h1>

            <p class="text-xl lg:text-2xl text-gray-600 dark:text-gray-400 max-w-4xl mx-auto leading-relaxed font-medium mb-12">
                The ultimate marketplace for the Gonachi ecosystem. Swap properties, assets, and high-value services through a secure, decentralized framework designed for the modern stakeholder.
            </p>

            <div class="w-1 h-24 bg-gradient-to-b from-primary-500 to-transparent mx-auto rounded-full animate-grow-y"></div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-40">
        <div class="relative group p-12 rounded-[3rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl transition-all duration-500" data-aos="fade-right">
            <div class="w-16 h-16 bg-primary-400 text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-primary-400/30 group-hover:rotate-12 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <h3 class="text-4xl font-black text-secondary-900 dark:text-white mb-6">Zero Friction</h3>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                Traditional liquidations take months. Gonachi Swap enables instant asset matching, allowing you to move from one investment to the next without the standard market delays.
            </p>
        </div>

        <div class="relative group p-12 rounded-[3rem] bg-secondary-900 text-white shadow-2xl border border-secondary-800 hover:-translate-y-2 transition-all duration-500" data-aos="fade-left">
            <div class="w-16 h-16 bg-white text-secondary-900 rounded-2xl flex items-center justify-center mb-8 shadow-lg group-hover:-rotate-12 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h3 class="text-4xl font-black text-primary-400 mb-6">Secured Escrow</h3>
            <p class="text-lg text-secondary-100/80 leading-relaxed font-medium">
                Every swap is backed by our internal verification system. We hold the trust so you can focus on the trade. Reliability isn't a feature; it's our foundation.
            </p>
        </div>
    </section>

    <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-xs font-black uppercase tracking-[0.5em] text-primary-500 mb-4">Swap Mechanics</h2>
        <div class="h-1 w-20 bg-secondary-900 dark:bg-white mx-auto"></div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-40">
        <?php
        $features = [
            ['Smart Matching', 'Our algorithm finds the perfect trade partner based on your asset value and location preferences.', 'primary', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
            ['Direct Messaging', 'Negotiate terms directly with other owners via our encrypted, real-time swap-chat interface.', 'secondary', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
            ['Valuation AI', 'Instantly receive a fair market estimate for your asset to ensure every swap is equitable.', 'primary', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2'],
            ['Proof of Ownership', 'Integrated document verification ensures all listed assets are legitimate and trade-ready.', 'secondary', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Multi-Asset Swaps', 'Trade a combination of assets or services for a single high-value property. Total flexibility.', 'primary', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['History Tracker', 'Maintain a perfect ledger of all previous trades to build your reputation in the Swap network.', 'secondary', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
        ];
        foreach ($features as $index => $f): ?>
            <div data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" class="p-10 rounded-[2.5rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 hover:border-primary-400 transition-all duration-500 group">
                <div class="w-14 h-14 rounded-xl bg-<?= $f[2] ?>-400/10 text-<?= $f[2] ?>-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $f[3] ?>" />
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-secondary-900 dark:text-white mb-4"><?= $f[0] ?></h4>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed"><?= $f[1] ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="text-center pb-20" data-aos="flip-up">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white mb-8 tracking-tighter">
                Ready to <span class="text-primary-500 italic">Swap?</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-12">
                List your first asset today and discover the power of the Gonachi exchange ecosystem.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="<?= $baseUrl ?>swap/create"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-12 py-6 bg-secondary-900 dark:bg-white text-white dark:text-secondary-900 text-xl font-black rounded-2xl hover:bg-primary-500 dark:hover:bg-primary-500 dark:hover:text-white hover:-translate-y-2 transition-all duration-300 shadow-2xl">
                    Create a Listing
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" class="ml-2 w-5 h-5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </a>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">— The Swap Protocol</p>
            </div>
        </div>
    </section>

</div>

<style>
    @keyframes grow-y {
        0% {
            transform: scaleY(0);
            transform-origin: top;
        }

        100% {
            transform: scaleY(1);
            transform-origin: top;
        }
    }

    .animate-grow-y {
        animation: grow-y 2s cubic-bezier(0.16, 1, 0.3, 1) infinite alternate;
    }

    @keyframes soft-pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.8;
            transform: scale(1.02);
        }
    }

    .animate-soft-pulse {
        animation: soft-pulse 3s ease-in-out infinite;
    }
</style>