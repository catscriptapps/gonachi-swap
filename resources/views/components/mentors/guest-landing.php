<?php
// /resources/views/components/mentors/guest-landing.php
?>

<div class="relative min-h-screen flex items-center justify-center px-4 py-20 overflow-hidden bg-gray-50 dark:bg-gray-950">
    <div class="absolute inset-0 z-0 opacity-[0.03] dark:opacity-[0.05]"
        style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="absolute top-1/4 -left-20 w-80 h-80 bg-primary-500/20 rounded-full blur-[100px] -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 -right-20 w-80 h-80 bg-secondary-500/20 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-delay: 3s"></div>

    <div class="max-w-5xl w-full relative z-10 text-center space-y-12">

        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm"
            data-aos="fade-down" data-aos-duration="800">
            <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Expert Network is Live</span>
        </div>

        <div class="space-y-6">
            <h1 class="text-5xl md:text-8xl font-black text-secondary-900 dark:text-white tracking-tighter leading-[0.85] uppercase"
                data-aos="zoom-out-up" data-aos-delay="200" data-aos-duration="1000">
                Learn from the <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500">Industry Best.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-500 dark:text-gray-400 font-medium leading-relaxed px-4"
                data-aos="fade-up" data-aos-delay="400" data-aos-duration="800">
                Unlock access to 1-on-1 mentorship, career guidance, and technical reviews from verified professionals across the globe.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4"
            data-aos="fade-up" data-aos-delay="600" data-aos-duration="800">
            <a href="<?= $baseUrl ?>login" data-login-button
                class="group relative w-full sm:w-auto px-10 py-5 bg-secondary-900 dark:bg-white text-white dark:text-secondary-950 font-black text-sm rounded-2xl transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-secondary-500/20 flex items-center justify-center gap-3 uppercase tracking-widest">
                <span>View All Mentors</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>

            <a href="javascript:"
                class="group register-btn w-full sm:w-auto px-10 py-5 bg-white dark:bg-white/5 text-secondary-900 dark:text-white font-black text-sm rounded-2xl border border-gray-200 dark:border-white/10 transition-all hover:bg-gray-50 dark:hover:bg-white/10 uppercase tracking-widest inline-flex items-center justify-center gap-3">

                <span>Join as a Mentor</span>

                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left pt-16">
            <div class="p-8 rounded-[2rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 transition-transform hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="800">
                <div class="w-12 h-12 rounded-xl bg-primary-500/10 flex items-center justify-center text-primary-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 inline-block">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <h4 class="font-black text-secondary-900 dark:text-white mb-2 uppercase text-sm tracking-tight">Verified Experts</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-normal">Every mentor is manually vetted to ensure high-quality professional guidance.</p>
            </div>

            <div class="p-8 rounded-[2rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 transition-transform hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="1000">
                <div class="w-12 h-12 rounded-xl bg-secondary-500/10 flex items-center justify-center text-secondary-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>

                </div>
                <h4 class="font-black text-secondary-900 dark:text-white mb-2 uppercase text-sm tracking-tight">1-on-1 Sessions</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-normal">Direct messaging and scheduled calls to help you overcome your specific blockers.</p>
            </div>

            <div class="p-8 rounded-[2rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-white/5 transition-transform hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="1200">
                <div class="w-12 h-12 rounded-xl bg-primary-500/10 flex items-center justify-center text-primary-500 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 inline-block">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="2" y1="12" x2="22" y2="12" />
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                </div>
                <h4 class="font-black text-secondary-900 dark:text-white mb-2 uppercase text-sm tracking-tight">Global Network</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-normal">Connect with leaders from top tech hubs and innovative startups worldwide.</p>
            </div>
        </div>

    </div>
</div>