<?php
// /resources/views/pages/home.php
declare(strict_types=1);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-8 font-sans overflow-hidden">

    <section class="relative min-h-[70vh] flex items-center mb-16 pt-8">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-primary-400/20 rounded-full blur-[100px] animate-pulse-slow"></div>

        <div class="grid lg:grid-cols-2 gap-12 items-center w-full relative z-10">
            <div class="text-left" data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary-50 dark:bg-secondary-900/30 text-secondary-600 dark:text-secondary-400 text-[10px] font-black uppercase tracking-[0.2em] mb-6 border border-secondary-200 dark:border-secondary-800 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    Global Real Estate Ecosystem — V2.0
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white leading-[0.95] tracking-tighter mb-6">
                    Gonachi <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 via-secondary-400 to-primary-500 animate-gradient-x">
                        Connected World.
                    </span>
                </h1>

                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-xl mb-10 font-medium">
                    The ultimate nexus for
                    <span class="text-secondary-600 dark:text-primary-400 font-bold">Real Estate Stakeholders</span>.
                </p>

                <div class="flex flex-wrap items-center gap-6">
                    <a href="javascript:"
                        class="register-btn inline-flex items-center justify-center group relative px-10 py-5 text-lg font-black rounded-2xl bg-secondary-500 text-white shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Join Ecosystem</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-400 to-primary-600 translate-y-[101%] group-hover:translate-y-0 transition-transform duration-300"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round" class="ml-2 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="19" y1="8" x2="19" y2="14" />
                            <line x1="16" y1="11" x2="22" y2="11" />
                        </svg>
                    </a>
                    <div class="flex flex-col items-start group cursor-pointer">
                        <span class="text-[11px] font-black text-secondary-900 dark:text-white uppercase tracking-[0.3em]">Connect • Grow</span>
                        <div class="h-0.5 w-full bg-primary-400 mt-1 origin-left animate-grow-x"></div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block relative group" data-aos="zoom-in">
                <div id="hero-carousel" class="flex overflow-x-hidden snap-x snap-mandatory rounded-[3rem] border-4 border-white/10 shadow-2xl bg-secondary-900 aspect-square transition-all duration-500">
                    <?php
                    $slides = [
                        ['title' => 'Landlords', 'text' => 'Effortless management & tenant validation.', 'img' => '1.jpg', 'id' => '1'],
                        ['title' => 'Tenants', 'text' => 'Find homes & connect with professionals.', 'img' => '2.jpg', 'id' => '5'],
                        ['title' => 'Agents & Brokers', 'text' => 'Expand reach and grow your business.', 'img' => '3.jpg', 'id' => '2'],
                        ['title' => 'Contractors', 'text' => 'Receive requests & expand clientele.', 'img' => '4.jpg', 'id' => '4'],
                        ['title' => 'Property Managers', 'text' => 'Streamline tasks & access services.', 'img' => '5.jpg', 'id' => '6'],
                    ];

                    foreach ($slides as $slide): ?>
                        <div class="snap-start min-w-full h-full relative overflow-hidden group/slide">
                            <img src="<?= $assetBase ?>images/home/<?= $slide['img'] ?>" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover/slide:scale-110 transition-transform duration-700" alt="<?= $slide['title'] ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-secondary-950 via-secondary-900/40 to-transparent"></div>

                            <div class="absolute bottom-0 left-0 p-8 w-full">
                                <h3 class="text-3xl font-black text-white mb-2 leading-none"><?= $slide['title'] ?></h3>
                                <p class="text-sm text-gray-300 mb-6 font-medium"><?= $slide['text'] ?></p>
                                <a href="<?= $baseUrl ?>login" data-login-button
                                    class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-500/20">
                                    See Options
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

    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-20 py-8 px-6 bg-gray-50/50 dark:bg-white/5 rounded-3xl border border-gray-100 dark:border-white/10" data-aos="fade-up">
        <?php
        $stats = [['Verified', 'Stakeholders', 'primary'], ['Global', 'Adverts', 'secondary'], ['Secure', 'Validations', 'primary'], ['Instant', 'Quotations', 'secondary']];
        foreach ($stats as $stat): ?>
            <div class="text-center group">
                <div class="text-2xl lg:text-3xl font-black text-secondary-900 dark:text-white mb-1 group-hover:text-primary-500 transition-colors">
                    <?= $stat[0] ?>
                </div>
                <div class="text-[9px] text-<?= $stat[2] ?>-500 font-black uppercase tracking-widest">
                    <?= $stat[1] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="relative rounded-[3rem] p-12 lg:p-20 text-center overflow-hidden bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 shadow-2xl" data-aos="flip-up">
        <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-4xl lg:text-6xl font-black text-secondary-900 dark:text-white mb-4">
                Find your <span class="text-primary-500">place.</span>
            </h2>

            <p class="text-gray-500 dark:text-gray-400 text-lg mb-10 font-medium">
                Join thousands of professionals today. Create your free account to get started, or sign in if you're already part of the community.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="javascript:"
                    class="register-btn w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 bg-secondary-500 text-white rounded-2xl text-xl font-black shadow-xl hover:bg-primary-500 hover:-translate-y-1 transition-all duration-300">
                    Get Started for Free
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" class="ml-2 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <line x1="19" y1="8" x2="19" y2="14" />
                        <line x1="16" y1="11" x2="22" y2="11" />
                    </svg>
                </a>

                <a href="<?= $baseUrl ?>login" data-login-button
                    class="group w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 py-5 bg-transparent border-2 border-secondary-200 dark:border-gray-700 text-secondary-900 dark:text-white rounded-2xl text-xl font-black hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-300">

                    <span>Sign In</span>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-6 h-6 transition-transform duration-300 group-hover:translate-x-1">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-secondary-500/5 rounded-full blur-3xl"></div>
    </section>

    <section class="mb-20 mt-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <h2 class="text-3xl lg:text-5xl font-black text-secondary-900 dark:text-white mb-6 leading-tight">Global Network <br><span class="text-primary-500">Without Borders</span></h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Landlord in Toronto or contractor in London? Gonachi connects verified opportunities across the globe instantly.
                </p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 font-bold text-sm text-secondary-700 dark:text-gray-300">
                        <div class="shrink-0 p-1 rounded-full bg-primary-400 text-white shadow-md"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                            </svg></div>
                        Cross-border collaboration
                    </div>
                    <div class="flex items-center gap-3 font-bold text-sm text-secondary-700 dark:text-gray-300">
                        <div class="shrink-0 p-1 rounded-full bg-primary-400 text-white shadow-md"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                            </svg></div>
                        Multi-currency engine
                    </div>
                </div>
            </div>
            <div class="bg-secondary-900 rounded-[2.5rem] p-8 relative overflow-hidden text-white" data-aos="fade-left">
                <div class="relative z-10">
                    <h3 class="text-xl font-black mb-2">Live Network Status</h3>
                    <div class="flex items-center gap-2 text-primary-400 text-xs font-black mb-6">
                        <span class="w-2 h-2 bg-primary-500 rounded-full animate-ping"></span> ACTIVE NODES
                    </div>
                    <div class="space-y-3 opacity-80">
                        <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-400 w-3/4 animate-grow-x"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mb-10 flex items-center gap-6" data-aos="fade-up">
        <h2 class="text-2xl font-black text-secondary-900 dark:text-white uppercase tracking-tighter">Platform Powerhouses</h2>
        <div class="flex-1 h-px bg-gray-100 dark:bg-white/10"></div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
        <?php
        $features = [
            [
                'Smart Adverts',
                'AI-driven visibility and lead generation.',
                'primary',
                'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
            ],
            ['Industry Mentorship', 'Guidance from seasoned professionals.', 'secondary', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['Social Live Feed', 'Share insights and follow leaders.', 'primary', 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
            ['Instant Quotations', 'Competitive quotes from contractors.', 'secondary', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['Home Matching', 'Add listings with AI matching.', 'primary', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
            ['Global Trust', 'Community ratings for everyone.', 'secondary', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.482-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z']
        ];
        foreach ($features as $f): ?>
            <div data-aos="fade-up" class="p-6 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all flex gap-5 group">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-<?= $f[2] ?>-400/10 text-<?= $f[2] ?>-500 flex items-center justify-center group-hover:scale-110 transition-transform">
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

    <section class="mb-20 py-12 bg-secondary-900 rounded-[3rem] text-white relative overflow-hidden" data-aos="zoom-in">
        <div class="relative z-10 px-10">
            <h2 class="text-3xl font-black mb-10 text-center">Seamless Integration</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $steps = [
                    ['Create Profile', 'Verify your identity for trust.'],
                    ['Post & Connect', 'List properties or find your home.'],
                    ['Grow Portfolio', 'Scale with Royal Navy security.'],
                    [
                        'Instant Quotes',
                        'Generate professional estimates in seconds.',
                        'primary',
                        'M9 12h3.75M9 15h3.375M9 9h3.375m1.875-1.875V11.25c0 .621.504 1.125 1.125 1.125H18M2.25 6v12a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V7.5L16.5 3H4.5A2.25 2.25 0 002.25 6z'
                    ],
                    [
                        'Social Feed',
                        'Real-time updates from your professional network.',
                        'primary',
                        'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'
                    ],
                    ['Boost with Ads', 'Targeted business growth.']
                ];
                foreach ($steps as $i => $step): ?>
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-10 h-10 bg-primary-400 text-white rounded-full flex items-center justify-center font-black text-sm"><?= $i + 1 ?></div>
                        <div>
                            <h4 class="font-bold text-white"><?= $step[0] ?></h4>
                            <p class="text-xs text-gray-400"><?= $step[1] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
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

    .animate-spin-slow {
        animation: spin 25s linear infinite;
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