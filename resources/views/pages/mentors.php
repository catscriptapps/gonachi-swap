<?php
// /resources/views/pages/mentors.php

use Src\Controller\MentorsController;

if ($isLoggedIn) {

    $mentorController = new MentorsController();
    $mentorController->index();

    $mentorCards   = $GLOBALS['mentorCards'] ?? '';
    $userTypes     = $GLOBALS['userTypes'] ?? [];
    $totalMentors  = $GLOBALS['totalMentors'] ?? 0;

    // Filter out 'Admin' from the user types list
    $filteredUserTypes = array_filter($userTypes, function ($type) {
        return strtolower($type['user_type']) !== 'admin';
    });

    $pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>';
?>

    <div id="mentors-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300 w-full max-w-full overflow-hidden">
        <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8 w-full box-border">

            <div class="overflow-x-clip lg:overflow-visible mb-8">
                <div class="grid lg:grid-cols-12 gap-4 items-stretch">

                    <div class="lg:col-span-8 relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900 shadow-lg border border-gray-200/60 dark:border-white/5 p-6 lg:p-8" data-aos="fade-right">
                        <div class="relative z-10 flex flex-col justify-between h-full">
                            <div>
                                <div class="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[8px] font-black uppercase tracking-widest mb-3">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-primary-500"></span>
                                    </span>
                                    Expert Network
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                    <div>
                                        <h1 class="text-2xl sm:text-3xl font-black tracking-tighter text-secondary-900 dark:text-white leading-none uppercase mb-2">
                                            Bridge the <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Knowledge Gap</span>
                                        </h1>
                                        <p class="max-w-md text-xs text-gray-500 dark:text-gray-400 font-medium">
                                            Connect with seasoned experts. Search by skill or specialty.
                                        </p>
                                    </div>

                                    <button class="register-mentor-trigger group relative px-6 py-3 bg-primary-600 dark:bg-white/5 text-white text-[10px] font-black rounded-xl border border-transparent dark:border-white/10 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 shrink-0">
                                        <span class="relative z-10 uppercase">Become a Mentor</span>
                                        <svg class="w-3.5 h-3.5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-2 p-1.5 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                                <div class="relative group">
                                    <input type="text" id="mentor-search-input" placeholder="Find a Mentor..."
                                        class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-900 border border-transparent rounded-xl text-sm font-bold focus:ring-2 focus:ring-primary-400/10 transition-all outline-none">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <div class="relative">
                                    <select id="mentor-type-filter" class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-transparent rounded-xl text-sm font-bold text-secondary-900 dark:text-white outline-none cursor-pointer appearance-none">
                                        <option value="0">All Mentor Types</option>
                                        <?php foreach ($filteredUserTypes as $type): ?>
                                            <option value="<?= $type['user_type_id'] ?>"><?= htmlspecialchars($type['user_type']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 text-xs">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -right-6 opacity-5 dark:opacity-10 transform -rotate-12 pointer-events-none">
                            <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-40 h-40"$2', $pageIcon) ?>
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-3">
                        <div id="active-mentors" class="flex-1 rounded-2xl bg-secondary-950 p-4 flex flex-col justify-center border border-white/5 relative overflow-hidden" data-aos="fade-left">
                            <span class="text-gray-500 text-[9px] font-black uppercase tracking-widest">Active Mentors</span>
                            <div class="text-2xl font-black text-white tracking-tighter">
                                <?= number_format($totalMentors) ?><span class="text-primary-500">+</span>
                            </div>
                            <div class="w-full h-1 bg-white/5 rounded-full mt-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-primary-500 to-secondary-500 w-[85%]"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 flex-1">
                            <div class="rounded-2xl bg-white dark:bg-white/5 p-4 flex flex-col justify-center border border-gray-100 dark:border-white/5" data-aos="fade-left" data-aos-delay="100">
                                <span class="text-primary-500 text-[9px] font-black uppercase tracking-widest">Reach</span>
                                <div class="text-xl font-black text-secondary-900 dark:text-white tracking-tighter">24/7</div>
                                <p class="text-[8px] text-gray-400 font-bold uppercase mt-1">Global</p>
                            </div>

                            <div class="rounded-2xl bg-white dark:bg-white/5 p-4 flex flex-col justify-center border border-gray-100 dark:border-white/5" data-aos="fade-left" data-aos-delay="200">
                                <span class="text-secondary-400 text-[9px] font-black uppercase tracking-widest">Rating</span>
                                <div class="text-xl font-black text-secondary-900 dark:text-white tracking-tighter flex items-center gap-1">
                                    4.9 <i class="bi bi-star-fill text-[10px] text-primary-500"></i>
                                </div>
                                <p class="text-[8px] text-gray-400 font-bold uppercase mt-1">Top Tier</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="mentors-container" class="w-full">
                <?php if (empty($mentorCards)): ?>
                    <div id="empty-mentors-state" class="py-16 sm:py-24 text-center bg-white dark:bg-gray-900/50 rounded-3xl sm:rounded-[3rem] border border-dashed border-gray-200 dark:border-gray-800" data-aos="zoom-in">
                        <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-50 dark:bg-gray-800/50 mb-6">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-secondary-900 dark:text-white mb-2 px-4">No Mentors Joined Yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8 font-medium px-6">
                            Our expert network is just getting started. Be the first to share your expertise!
                        </p>
                        <button class="register-mentor-trigger px-8 py-4 bg-secondary-900 dark:bg-primary-400 text-white font-black rounded-2xl shadow-xl transition-all hover:scale-105 active:scale-95">
                            Become a Founding Mentor
                        </button>
                    </div>
                <?php endif; ?>

                <div id="mentors-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 <?= empty($mentorCards) ? 'hidden' : '' ?>">
                    <?= $mentorCards ?>
                </div>

                <div id="no-mentors-found" class="hidden py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-50 dark:bg-primary-950/20 mb-6">
                        <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-secondary-900 dark:text-white mb-2">No matches found</h3>
                    <p class="text-gray-500 dark:text-gray-400 px-4">We couldn't find any experts matching those criteria.</p>
                    <button type="button" id="clear-mentor-filters" class="mt-6 text-sm font-black text-primary-400 hover:underline uppercase tracking-widest">Reset Filters</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #active-mentors .animate-shimmer {
            animation: mentors-shimmer 60s linear infinite;
        }

        @keyframes mentors-shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>

<?php

} else {
    include __DIR__ . '/../components/mentors/guest-landing.php';
}
