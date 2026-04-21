<?php
// /resources/views/pages/about.php
declare(strict_types=1);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 font-sans overflow-hidden">

    <section class="text-center mb-32 relative pt-20">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-gradient-to-b from-primary-400/10 to-transparent rounded-[3rem] blur-3xl -z-10"></div>

        <div class="relative" data-aos="zoom-out" data-aos-duration="1200">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-[0.3em] mb-8 border border-primary-100 dark:border-primary-800 animate-soft-pulse">
                The Gonachi Story
            </div>
            <h1 class="text-6xl lg:text-8xl font-black text-secondary-900 dark:text-white tracking-tighter mb-8 leading-[0.9]">
                Built for the <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-600">Ecosystem.</span>
            </h1>

            <p class="text-xl lg:text-2xl text-gray-600 dark:text-gray-400 max-w-4xl mx-auto leading-relaxed font-medium mb-12">
                Gonachi Real Estate World is a comprehensive global platform designed to simplify property activities and promote a seamless exchange of services between all stakeholders.
            </p>

            <div class="w-1 h-24 bg-gradient-to-b from-primary-500 to-transparent mx-auto rounded-full animate-grow-y"></div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-40">
        <div class="relative group p-12 rounded-[3rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl transition-all duration-500" data-aos="fade-right">
            <div class="w-16 h-16 bg-primary-400 text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-primary-400/30 group-hover:rotate-12 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-4xl font-black text-secondary-900 dark:text-white mb-6">The Mission</h3>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                To provide a one-stop-shop that caters to the diverse needs of landlords, contractors, agents, and tenants. We are committed to building a platform rooted in transparency, reliability, and trust.
            </p>
        </div>

        <div class="relative group p-12 rounded-[3rem] bg-secondary-900 text-white shadow-2xl border border-secondary-800 hover:-translate-y-2 transition-all duration-500" data-aos="fade-left">
            <div class="w-16 h-16 bg-white text-secondary-900 rounded-2xl flex items-center justify-center mb-8 shadow-lg group-hover:-rotate-12 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <h3 class="text-4xl font-black text-primary-400 mb-6">The Vision</h3>
            <p class="text-lg text-secondary-100/80 leading-relaxed font-medium">
                To create a global real estate and social media hybrid that connects the industry across borders, making it easy for professionals and users to collaborate and grow their businesses.
            </p>
        </div>
    </section>

    <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-xs font-black uppercase tracking-[0.5em] text-primary-500 mb-4">Deep Ecosystem Features</h2>
        <div class="h-1 w-20 bg-secondary-900 dark:bg-white mx-auto"></div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-40">
        <?php
        $features = [
            ['Rating & Recommendations', 'Make informed decisions with user-driven insights and a reliable peer-recommendation engine.', 'primary', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.482-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
            ['Contractor Quotations', 'Request quotes and view ratings for contractors directly. Transparent bidding at its best.', 'secondary', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['Tenant & Landlord Validation', 'Validate potential tenants and reference previous landlords to protect your rental portfolio.', 'primary', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['Mentoring System', 'Experienced industry veterans guiding new professionals. Knowledge sharing built-in.', 'secondary', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['Home Matching (AI)', 'Dedicated systems for buyers and sellers with AI-driven preference matching and media uploads.', 'primary', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['Social Network & Groups', 'Connect, follow, and join specialized groups like "My Tenants" or "My Agents" on our live feed.', 'secondary', 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z']
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

    <section class="mb-40 py-24 bg-secondary-950 rounded-[4rem] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>

        <div class="relative z-10 px-10">
            <h2 class="text-4xl lg:text-6xl font-black text-white text-center mb-20" data-aos="fade-down">
                Tailored for <span class="text-primary-400">Everyone.</span>
            </h2>

            <div class="grid lg:grid-cols-2 gap-16">
                <div class="space-y-12" data-aos="fade-right">
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center font-black text-white group-hover:animate-bounce">L</div>
                        <div>
                            <h4 class="text-xl font-bold text-white mb-2">For Landlords</h4>
                            <p class="text-secondary-300">Advertise properties, manage tenant validations, and connect with contractors for property maintenance in one dashboard.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center font-black text-white group-hover:animate-bounce">A</div>
                        <div>
                            <h4 class="text-xl font-bold text-white mb-2">For Agents & Brokers</h4>
                            <p class="text-secondary-300">Access a global directory of clients and properties. Reach buyers and sellers within your specific city or province instantly.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-12" data-aos="fade-left">
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center font-black text-white group-hover:animate-bounce">T</div>
                        <div>
                            <h4 class="text-xl font-bold text-white mb-2">For Tenants</h4>
                            <p class="text-secondary-300">Simple search tools and intuitive communication. Evaluate your current or previous landlords to help the community.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center font-black text-white group-hover:animate-bounce">C</div>
                        <div>
                            <h4 class="text-xl font-bold text-white mb-2">For Contractors</h4>
                            <p class="text-secondary-300">Receive service requests from a wide range of customers within your specified location. Build your reputation via ratings.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-40" data-aos="zoom-in-up">
        <div class="bg-gradient-to-br from-primary-400 to-primary-600 rounded-[3rem] p-1 lg:p-2 shadow-2xl">
            <div class="bg-white dark:bg-gray-900 rounded-[2.8rem] p-12 lg:p-20 flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="inline-block px-4 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-black text-xs uppercase tracking-widest mb-6">Cutting Edge Tech</div>
                    <h2 class="text-4xl lg:text-6xl font-black text-secondary-900 dark:text-white mb-8">Your All-in-One <br><span class="text-primary-500">AI Assistant.</span></h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-8">
                        Imagine having a knowledgeable companion at your fingertips. Our AI chatbot understands your needs and provides tailored recommendations to streamline your journey through the Gonachi ecosystem.
                    </p>
                    <div class="flex items-center gap-4 text-secondary-900 dark:text-white font-black italic underline decoration-primary-400">
                        Always ready. Always learning.
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="aspect-square rounded-full bg-primary-400/20 absolute inset-0 animate-pulse"></div>
                    <div class="relative bg-secondary-900 rounded-[2rem] p-8 shadow-2xl border-4 border-white dark:border-gray-800 rotate-3 group-hover:rotate-0 transition-transform duration-500">
                        <div class="space-y-4">
                            <div class="h-4 w-3/4 bg-white/20 rounded-full animate-pulse"></div>
                            <div class="h-4 w-1/2 bg-white/10 rounded-full"></div>
                            <div class="h-20 w-full bg-primary-400/20 rounded-2xl border border-primary-400/30 flex items-center justify-center text-primary-400 font-black">
                                AI PROCESSING...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="text-center pb-20" data-aos="flip-up">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white mb-8 tracking-tighter">
                Start your <span class="text-primary-500 italic">growth.</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-12">
                Join the global community today and experience the power of a truly connected property ecosystem.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="javascript:"
                    class="register-btn inline-flex items-center justify-center w-full sm:w-auto px-12 py-6 bg-secondary-900 dark:bg-white text-white dark:text-secondary-900 text-xl font-black rounded-2xl hover:bg-primary-500 dark:hover:bg-primary-500 dark:hover:text-white hover:-translate-y-2 transition-all duration-300 shadow-2xl">
                    Join Us Today
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" class="ml-2 w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <line x1="19" y1="8" x2="19" y2="14" />
                        <line x1="16" y1="11" x2="22" y2="11" />
                    </svg>
                </a>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">— The Gonachi Team</p>
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