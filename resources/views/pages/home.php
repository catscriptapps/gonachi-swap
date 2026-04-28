<?php
// /resources/views/pages/home.php

declare(strict_types=1);

/**
 * Gonachi Swap - Guest Home Page
 * Navy: secondary (900/950)
 * Orange: primary (500)
 */
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-8 font-sans overflow-hidden">

    <section class="relative min-h-[75vh] flex items-center mb-16 pt-8">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-primary-500/10 rounded-full blur-[100px] animate-pulse-slow"></div>

        <div class="grid lg:grid-cols-2 gap-12 items-center w-full relative z-10">
            <div class="text-left" data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-900/5 dark:bg-secondary-900/40 text-secondary-900 dark:text-secondary-400 text-[10px] font-black uppercase tracking-[0.2em] mb-6 border border-secondary-200 dark:border-secondary-800 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    The Barter Economy — V1.0
                </div>

                <h1 class="text-4xl lg:text-6xl font-black text-secondary-900 dark:text-white leading-[0.95] tracking-tighter mb-6">
                    Don't Throw It. <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 via-orange-400 to-primary-600 animate-gradient-x">
                        Swap It.
                    </span>
                </h1>

                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-xl mb-10 font-medium">
                    The ultimate digital marketplace to
                    <span class="text-secondary-900 dark:text-primary-500 font-bold underline decoration-primary-500/30">Exchange, Sell, or Gift</span>
                    items within your local community. No cash? No problem.
                </p>

                <div class="flex flex-wrap items-center gap-6">
                    <a href="javascript:"
                        class="register-btn inline-flex items-center justify-center group relative px-10 py-5 text-lg font-black rounded-2xl bg-secondary-900 text-white shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Start Swapping</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-orange-600 translate-y-[101%] group-hover:translate-y-0 transition-transform duration-300"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round" class="ml-2 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                            <path d="m16 3 4 4-4 4" />
                            <path d="M20 7H4" />
                            <path d="m8 21-4-4 4-4" />
                            <path d="M4 17h16" />
                        </svg>
                    </a>
                    <div class="flex flex-col items-start group cursor-pointer">
                        <span class="text-[11px] font-black text-secondary-900 dark:text-white uppercase tracking-[0.3em]">Trade • Connect</span>
                        <div class="h-0.5 w-full bg-primary-500 mt-1 origin-left animate-grow-x"></div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block relative group" data-aos="zoom-in">
                <div id="hero-carousel" class="flex overflow-x-hidden snap-x snap-mandatory rounded-[3rem] shadow-2xl bg-secondary-950 aspect-square transition-all duration-500">
                    <?php
                    $slides = [
                        ['title' => 'Direct Swaps', 'text' => 'Trade your laptop for a console instantly.', 'img' => '1.webp'],
                        ['title' => 'Sell for Cash', 'text' => 'List items for sale at your own price.', 'img' => '2.jpg'],
                        ['title' => 'Give for Free', 'text' => 'Declutter by gifting items to neighbors.', 'img' => '3.jpg'],
                        ['title' => 'Safe Meetings', 'text' => 'Connect via proximity-based secure matching.', 'img' => '4.jpg'],
                        ['title' => 'Social Feed', 'text' => 'Stay up to date with your listings.', 'img' => '5.jpg'],
                    ];

                    foreach ($slides as $slide): ?>
                        <div class="snap-start min-w-full h-full relative overflow-hidden group/slide">
                            <img src="<?= $assetBase ?>images/home/<?= $slide['img'] ?>" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover/slide:scale-110 transition-transform duration-700" alt="<?= $slide['title'] ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-secondary-950 via-secondary-900/40 to-transparent"></div>

                            <div class="absolute bottom-0 left-0 p-12 w-full">
                                <h3 class="text-4xl font-black text-white mb-2 leading-none"><?= $slide['title'] ?></h3>
                                <p class="text-md text-gray-300 mb-6 font-medium"><?= $slide['text'] ?></p>
                                <a href="<?= $baseUrl ?>login"
                                    class="px-8 py-3 bg-primary-500 hover:bg-primary-600 text-secondary-950 text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-500/20">
                                    Explore Listings
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <?php foreach ($slides as $i => $s): ?>
                        <div class="carousel-dot w-2 h-2 rounded-full bg-white/20 transition-all cursor-pointer" onclick="scrollToSlide(<?= $i ?>)"></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-20 py-8 px-6 bg-gray-100 dark:bg-white/5 rounded-3xl border border-gray-300 dark:border-white/10" data-aos="fade-up">
        <?php
        $stats = [['Location', 'Proximity', 'primary'], ['Verified', 'Swappers', 'secondary'], ['Zero', 'Fees', 'primary'], ['Instant', 'Chat', 'secondary']];
        foreach ($stats as $stat): ?>
            <div class="text-center group">
                <div class="text-2xl lg:text-3xl font-black text-secondary-900 dark:text-white mb-1 group-hover:text-primary-500 transition-colors">
                    <?= $stat[0] ?>
                </div>
                <div class="text-[9px] text-<?= $stat[2] === 'primary' ? 'primary-500' : 'secondary-400' ?> font-black uppercase tracking-widest">
                    <?= $stat[1] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="my-20" data-aos="zoom-in">
        <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-2xl border border-gray-100 dark:border-white/10">
            <div class="aspect-video">
                <iframe
                    class="w-full h-full"
                    src="https://www.youtube.com/embed/0k49DSqCnk0"
                    title="Gonachi Swap Overview"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>

    <div class="mb-10 flex items-center gap-6" data-aos="fade-up">
        <h2 class="text-2xl font-black text-secondary-900 dark:text-white uppercase tracking-tighter">Swap Ecosystem</h2>
        <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
        <?php
        $features = [
            ['Smart Matching', 'Our algorithm suggests swaps based on what you have and what you want.', 'primary', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
            ['Proximity Search', 'Find items within walking distance using GPS-based location tracking.', 'secondary', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
            ['In-App Negotiation', 'Secure messaging system to discuss item condition and meet-up spots.', 'primary', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['Trust Score', 'Trade with confidence using our 1–5 star rating and successful swap history.', 'secondary', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['Media Rich Listings', 'Support for multiple photos, video tours, and YouTube links.', 'primary', 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ['Switchable Types', 'Change your listing from Swap to Sale or Free at any time.', 'secondary', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15']
        ];
        foreach ($features as $f): ?>
            <div data-aos="fade-up" class="p-6 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all flex gap-5 group">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-<?= $f[2] === 'primary' ? 'primary-500' : 'secondary-900' ?>/10 text-<?= $f[2] === 'primary' ? 'primary-500' : 'secondary-600' ?> flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $f[3] ?>" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-secondary-900 dark:text-white mb-1"><?= $f[0] ?></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-tight"><?= $f[1] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="relative rounded-[3rem] p-12 lg:p-20 text-center overflow-hidden bg-secondary-950 border border-white/5 shadow-2xl" data-aos="flip-up">
        <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-4xl lg:text-6xl font-black text-white mb-4">
                Real value. <span class="text-primary-500">Zero cash.</span>
            </h2>

            <p class="text-gray-400 text-lg mb-10 font-medium">
                Join the global exchange economy. Turn your unused items into the things you actually want today.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="javascript:"
                    class="register-btn w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 bg-primary-500 text-secondary-950 rounded-2xl text-xl font-black shadow-xl hover:bg-orange-400 hover:-translate-y-1 transition-all duration-300">
                    Create Free Account
                </a>

                <a href="<?= $baseUrl ?>login"
                    class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 py-5 bg-white/5 border-2 border-white/10 text-white rounded-2xl text-xl font-black hover:bg-white/10 transition-all duration-300">
                    Sign In
                </a>
            </div>
        </div>

        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-secondary-500/10 rounded-full blur-3xl"></div>
    </section>
</div>

<style>
    @keyframes gradient-x {

        0%,
        100% {
            background-position: left center;
        }

        50% {
            background-position: right center;
        }
    }

    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 5s ease infinite;
    }

    @keyframes grow-x {
        from {
            transform: scaleX(0);
        }

        to {
            transform: scaleX(1);
        }
    }

    .animate-grow-x {
        animation: grow-x 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-pulse-slow {
        animation: pulse 8s ease-in-out infinite;
        opacity: 0.3;
    }
</style>