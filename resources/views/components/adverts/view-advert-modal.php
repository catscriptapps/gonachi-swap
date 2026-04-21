<?php
// /resources/views/components/adverts/view-advert-modal.php
?>

<style>
    /* Custom Stylish Scrollbar for the Modal Content */
    #view-ad-modal-content::-webkit-scrollbar {
        width: 6px;
    }

    #view-ad-modal-content::-webkit-scrollbar-track {
        background: transparent;
        margin: 10px;
    }

    #view-ad-modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        /* slate-300 */
        border-radius: 10px;
        border: 2px solid transparent;
    }

    .dark #view-ad-modal-content::-webkit-scrollbar-thumb {
        background: #334155;
        /* slate-700 */
    }

    #view-ad-modal-content::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
        /* Gonachi Orange (orange-600) */
    }
</style>

<div id="view-ad-modal" class="fixed inset-0 z-50 hidden">
    <div id="close-view-ad-modal-overlay" class="absolute inset-0 bg-secondary-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl border border-gray-200 dark:border-gray-800 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">

            <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-4 overflow-hidden">
                    <div id="view-ad-package-icon-container" class="h-12 w-12 flex-shrink-0 rounded-2xl bg-orange-600 flex items-center justify-center text-white shadow-lg shadow-orange-600/20">
                        <span id="view-ad-initial" class="text-xl font-black uppercase"></span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-lg font-black text-secondary-900 dark:text-white truncate leading-tight" id="view-ad-title">Ad Title</h3>
                        <p id="view-ad-package-sub" class="text-[10px] text-orange-600 dark:text-orange-400 font-black uppercase tracking-[0.2em]">Free Package</p>
                        <div id="view-ad-status-container">
                            <span id="view-ad-status" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-orange-100 bg-orange-50 text-orange-600"></span>
                        </div>
                    </div>
                </div>
                <button type="button" class="close-view-ad-modal text-gray-400 hover:text-orange-600 p-2 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="view-ad-modal-content" class="p-4 space-y-4 font-sans overflow-y-auto max-h-[65vh]">

                <?php
                $modalDetailOwnerId = 'ad';
                $modalDetailOwnerTitle = 'Advert Owner';
                include __DIR__ . '/../ui/modal-detail-owner.php';
                ?>

                <div class="space-y-3">
                    <div class="flex items-center justify-between pb-3 pt-3">
                        <div class="flex items-center gap-3">
                            <h4 class="text-[10px] font-black text-secondary-900 dark:text-white uppercase tracking-[0.2em]">Media Assets</h4>
                            <span id="ad-pics-count" class="text-[10px] font-bold text-orange-600 bg-orange-50 dark:bg-orange-950/30 px-2 py-0.5 rounded-md">0 / 4</span>
                        </div>

                        <button type="button" id="trigger-ad-pic-upload" class="ad-admin-only flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-600 hover:bg-orange-600 hover:text-white rounded-lg transition-all group">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-tight">Add Photo</span>
                        </button>
                    </div>

                    <div id="ad-pics-wrapper" class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    </div>

                    <input type="file" id="ad-pic-input" class="hidden" accept="image/*" multiple>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-800/40 p-6 rounded-2xl border border-gray-100 dark:border-gray-800/50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Advert Content</p>
                        <p id="view-ad-description" class="text-sm text-secondary-900 dark:text-gray-300 leading-relaxed italic"></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800/40 p-6 rounded-2xl border border-gray-100 dark:border-gray-800/50">
                        <p class="text-[10px] font-black text-secondary-400 uppercase tracking-widest mb-3">Search Keywords</p>
                        <p id="view-ad-keywords" class="text-sm text-secondary-600 dark:text-secondary-300 leading-relaxed font-bold"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                            <h4 class="text-[10px] font-black text-secondary-900 dark:text-white uppercase tracking-widest">Target Countries</h4>
                        </div>
                        <div id="view-ad-countries-container" class="flex flex-wrap gap-1.5"></div>
                    </div>
                    <div class="p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <h4 class="text-[10px] font-black text-secondary-900 dark:text-white uppercase tracking-widest">Audience Types</h4>
                        </div>
                        <div id="view-ad-types-container" class="flex flex-wrap gap-1.5"></div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-8 bg-secondary-900 text-white p-6 rounded-2xl shadow-xl border border-white/5">

                    <div class="flex items-center space-x-4 w-full sm:w-auto">
                        <div class="bg-orange-600 p-3 rounded-xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Destination Link</p>
                            <a id="view-ad-url" href="#" target="_blank" class="block text-sm font-bold text-orange-400 truncate hover:text-orange-300 transition-colors"></a>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto text-left sm:text-right shrink-0 border-t sm:border-t-0 sm:border-l border-white/10 pt-4 sm:pt-0 sm:pl-8">
                        <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Call To Action</p>
                        <p id="view-ad-cta-text" class="text-sm font-black text-white italic tracking-wide"></p>
                    </div>
                </div>

                <div class="flex gap-8 text-left">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Created On</p>
                        <span id="view-ad-joined" class="text-[11px] font-bold text-secondary-900 dark:text-white uppercase tracking-tight"></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Last Updated</p>
                        <span id="view-ad-updated" class="text-[11px] font-bold text-secondary-900 dark:text-white uppercase tracking-tight"></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Views</p>
                        <span id="view-ad-views-count" class="text-[11px] font-bold text-secondary-900 dark:text-white">0</span>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">

                <div id="view-ad-admin-actions" class="hidden flex items-center gap-3">
                    <button type="button" id="admin-approve-ad-btn" class="px-6 py-3 text-xs font-black text-white bg-green-600 hover:bg-green-700 rounded-xl transition-all shadow-lg shadow-green-600/20 uppercase tracking-widest active:scale-95">
                        Approve
                    </button>

                    <button type="button" id="admin-deactivate-ad-btn" class="px-6 py-3 text-xs font-black text-white bg-slate-600 hover:bg-slate-700 rounded-xl transition-all shadow-lg shadow-slate-600/20 uppercase tracking-widest active:scale-95">
                        Deactivate
                    </button>

                    <button type="button" id="admin-reject-ad-btn" class="px-6 py-3 text-xs font-black text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all shadow-lg shadow-red-600/20 uppercase tracking-widest active:scale-95 text-nowrap">
                        Reject
                    </button>
                </div>

                <div class="flex items-center space-x-4 ml-auto">
                    <button type="button" class="close-view-ad-modal px-5 py-3 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-secondary-900 dark:hover:text-white transition-colors">
                        Dismiss
                    </button>

                    <button type="button" id="view-ad-edit-btn" class="ad-admin-only px-8 py-3 text-xs font-black text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition-all active:scale-95 shadow-lg shadow-orange-600/20 flex items-center gap-2 uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        <span id="view-ad-edit-btn-text">Edit Ad</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>