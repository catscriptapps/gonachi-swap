<?php
// /resources/views/components/mentors/view-mentor-modal.php
?>

<style>
    /* Custom Stylish Scrollbar for the Mentor Modal Content */
    #view-mentor-modal-content::-webkit-scrollbar {
        width: 6px;
    }

    #view-mentor-modal-content::-webkit-scrollbar-track {
        background: transparent;
        margin: 10px;
    }

    #view-mentor-modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        border: 2px solid transparent;
    }

    .dark #view-mentor-modal-content::-webkit-scrollbar-thumb {
        background: #334155;
    }

    #view-mentor-modal-content::-webkit-scrollbar-thumb:hover {
        background: #8b5cf6;
        /* Updated to Gonachi Primary (500) */
    }
</style>

<div id="view-mentor-modal" class="fixed inset-0 z-50 hidden">
    <div id="close-view-mentor-modal-overlay" class="absolute inset-0 bg-secondary-955/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl border border-gray-200 dark:border-secondary-900 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">

            <div class="px-8 py-6 border-b border-gray-100 dark:border-secondary-900 flex items-center justify-between bg-gray-50/50 dark:bg-secondary-950/50">
                <div class="flex items-center space-x-5 overflow-hidden">
                    <div id="view-mentor-icon-container" class="h-14 w-14 flex-shrink-0 rounded-2xl bg-primary-400 flex items-center justify-center text-white shadow-lg shadow-primary-400/20 overflow-hidden">
                        <img id="view-mentor-avatar" src="" class="h-full w-full object-cover hidden" alt="Mentor">
                        <span id="view-mentor-initial" class="text-2xl font-black uppercase"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-black text-secondary-900 dark:text-white truncate leading-tight" id="view-mentor-name">Mentor Name</h3>
                            <span id="view-mentor-type-badge" class="px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-primary-100 bg-primary-50 text-primary-400">Expert</span>
                        </div>
                        <p id="view-mentor-headline" class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">Headline</p>
                    </div>
                </div>
                <button type="button" class="close-view-mentor-modal text-gray-400 hover:text-primary-400 p-2 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="view-mentor-modal-content" class="p-8 space-y-10 font-sans overflow-y-auto max-h-[70vh]">

                <?php
                $modalDetailOwnerId = 'mentor';
                $modalDetailOwnerTitle = 'Expert Profile';
                include __DIR__ . '/../ui/modal-detail-owner.php';
                ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 space-y-10">
                        <div class="px-2">
                            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                                <span class="w-8 h-[2px] bg-primary-400"></span>
                                <i class="bi bi-person-lines-fill"></i> Professional Bio
                            </h3>
                            <div class="bg-white dark:bg-secondary-900/20 p-6 rounded-2xl border border-gray-100 dark:border-secondary-800">
                                <p id="view-mentor-bio" class="text-sm text-secondary-900 dark:text-gray-300 leading-relaxed whitespace-pre-line">---</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center space-x-4 p-5 rounded-2xl bg-secondary-900 text-white">
                                <div class="youtube-icon-bg bg-white/10 p-3 rounded-xl transition-all">
                                    <svg class="w-6 h-6 text-[#FF0000]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Introduction Video</p>
                                    <a id="view-mentor-youtube-url" href="#" target="_blank" class="block text-sm font-bold text-primary-400 truncate transition-colors">No video</a>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4 p-5 rounded-2xl border border-gray-100 dark:border-secondary-800">
                                <div class="bg-secondary-100 dark:bg-secondary-900 p-3 rounded-xl text-secondary-900 dark:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Professional Website</p>
                                    <a id="view-mentor-website-url" href="#" target="_blank" class="block text-sm font-bold text-secondary-900 dark:text-white truncate hover:text-primary-400 transition-colors">Visit Website</a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
                            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                                <span class="w-8 h-[2px] bg-primary-400"></span>
                                <i class="bi bi-geo-alt-fill"></i> Professional Location
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Country</p>
                                    <p id="view-mentor-country-detail" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Region / State</p>
                                    <p id="view-mentor-region-detail" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">City</p>
                                    <p id="view-mentor-city-detail" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 mt-10">
                        <div class="p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800 bg-white dark:bg-transparent">
                            <h3 class="text-xs font-black text-secondary-900 dark:text-white uppercase tracking-widest mb-6">Quick Facts</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between border-b border-gray-50 dark:border-secondary-900 pb-3">
                                    <span class="text-[11px] text-gray-500 uppercase font-bold tracking-tight">Experience</span>
                                    <span id="view-mentor-exp" class="text-xs font-black text-primary-400">---</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
                            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                                <span class="w-8 h-[2px] bg-primary-400"></span>
                                <i class="bi bi-lightbulb-fill"></i> Areas of Expertise
                            </h3>
                            <div id="view-mentor-skills-container" class="flex flex-wrap gap-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-gray-100 dark:border-secondary-900 bg-gray-50/50 dark:bg-secondary-950/50 flex justify-end items-center space-x-4">
                <button type="button" class="close-view-mentor-modal px-5 py-3 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-secondary-900 dark:hover:text-white transition-colors">
                    Close Profile
                </button>
                <button type="button" id="view-mentor-connect-btn" class="px-8 py-3 text-xs font-black text-white bg-primary-400 hover:bg-primary-500 rounded-xl transition-all active:scale-95 shadow-lg shadow-primary-400/20 flex items-center gap-2 uppercase tracking-widest">
                    Message Mentor
                </button>
            </div>
        </div>
    </div>
</div>