<?php
// /resources/views/components/quotations/view-quotation-modal.php
?>

<style>
    /* Custom Stylish Scrollbar for the Modal Content */
    #view-quotation-modal-content::-webkit-scrollbar {
        width: 6px;
    }

    #view-quotation-modal-content::-webkit-scrollbar-track {
        background: transparent;
        margin: 10px;
    }

    #view-quotation-modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        border: 2px solid transparent;
    }

    .dark #view-quotation-modal-content::-webkit-scrollbar-thumb {
        background: #334155;
    }

    #view-quotation-modal-content::-webkit-scrollbar-thumb:hover {
        background: #fc832b;
        /* Primary */
    }
</style>

<div id="view-quotation-modal" class="fixed inset-0 z-50 hidden">
    <div id="close-view-quotation-modal-overlay" class="absolute inset-0 bg-secondary-955/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl border border-gray-200 dark:border-secondary-900 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">

            <div class="px-8 py-6 border-b border-gray-100 dark:border-secondary-900 flex items-center justify-between bg-gray-50/50 dark:bg-secondary-950/50">
                <div class="flex items-center space-x-5 overflow-hidden">
                    <div id="view-quote-icon-container" class="h-14 w-14 flex-shrink-0 rounded-2xl bg-primary-400 flex items-center justify-center text-white shadow-lg shadow-primary-400/20">
                        <span id="view-quote-initial" class="text-2xl font-black uppercase"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-black text-secondary-900 dark:text-white truncate leading-tight" id="view-quote-title">Project Title</h3>
                            <span id="view-quote-status" class="px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-primary-100 bg-primary-50 text-primary-400">Active</span>
                        </div>
                        <p id="view-quote-trade-sub" class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">General Labor</p>
                    </div>
                </div>
                <button type="button" class="close-view-quotation-modal text-gray-400 hover:text-primary-400 p-2 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="view-quotation-modal-content" class="p-8 space-y-10 font-sans overflow-y-auto max-h-[70vh]">

                <?php
                $modalDetailOwnerId = 'quote';
                $modalDetailOwnerTitle = 'Project Owner';
                include __DIR__ . '/../ui/modal-detail-owner.php';
                ?>

                <div class="space-y-4 px-2">
                    <div class="flex items-center justify-between border-b border-gray-50 dark:border-secondary-900 pb-2">
                        <div class="flex items-center gap-3">
                            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="w-8 h-[2px] bg-primary-400"></span>
                                <i class="bi bi-images"></i> Project Media
                            </h3>
                            <span id="quote-pics-count" class="text-[10px] font-bold text-primary-400 bg-primary-50 dark:bg-primary-950/30 px-2 py-0.5 rounded-md border border-primary-100 dark:border-primary-900/50"></span>
                        </div>

                        <button type="button" id="trigger-quote-pic-upload" class="quote-admin-only flex items-center gap-1.5 px-3 py-1.5 bg-primary-50 dark:bg-primary-900/20 text-primary-400 hover:bg-primary-400 hover:text-white rounded-lg transition-all group">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-tight">Add Photo</span>
                        </button>
                    </div>

                    <div id="quote-pics-wrapper" class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    </div>

                    <input type="file" id="quote-pic-input" class="hidden" accept="image/*" multiple>
                </div>

                <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
                    <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-primary-400"></span>
                        <i class="bi bi-geo-alt-fill"></i> Project Location
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Country</p>
                            <p id="view-quote-country" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Region / State</p>
                            <p id="view-quote-region" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">City</p>
                            <p id="view-quote-city" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                    </div>
                </div>

                <div class="px-2">
                    <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-primary-400"></span>
                        <i class="bi bi-card-text"></i> Work Description
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-white dark:bg-secondary-900/20 p-6 rounded-2xl border border-gray-100 dark:border-secondary-800">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Scope of Work</p>
                            <p id="view-quote-description" class="text-sm text-secondary-900 dark:text-gray-300 leading-relaxed whitespace-pre-line">---</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-secondary-800">
                    <h3 class="text-xs font-black text-secondary-900 dark:text-white uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-secondary-900 dark:bg-white"></span>
                        <i class="bi bi-tags-fill"></i> Classification
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Contractor Type</p>
                                <p id="view-quote-contractor-type" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Skilled Trade</p>
                                <p id="view-quote-skilled-trade" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Unit Type</p>
                                <p id="view-quote-unit-type" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                            <div id="view-quote-house-type-wrapper">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">House Style</p>
                                <p id="view-quote-house-type" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-2 grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                            <span class="w-8 h-[2px] bg-primary-400"></span>
                            <i class="bi bi-calendar3"></i> Timeline
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-50 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Start Date / Time</span>
                                <span id="view-quote-timeline-start" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-50 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Finish Date / Time</span>
                                <span id="view-quote-timeline-finish" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                            <span class="w-8 h-[2px] bg-primary-400"></span>
                            <i class="bi bi-cash-stack"></i> Financials
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-50 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Quotation Type</span>
                                <span id="view-quote-type-label" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-50 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Budget Range</span>
                                <span id="view-quote-budget" class="text-xs font-bold text-primary-400">---</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center space-x-4 p-5 rounded-2xl bg-secondary-900 text-white">
                        <div class="bg-white/10 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-[#FF0000]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Project Video</p>
                            <a id="view-quote-url" href="#" target="_blank" class="block text-sm font-bold text-primary-400 truncate hover:text-primary-300">No video</a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 p-5 rounded-2xl border border-gray-100 dark:border-secondary-800">
                        <div class="bg-secondary-100 dark:bg-secondary-900 p-3 rounded-xl text-secondary-900 dark:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Contact Phone</p>
                            <p id="view-quote-phone" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-50 dark:border-secondary-900">
                    <div class="flex gap-6">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Created</p>
                            <span id="view-quote-created" class="text-[11px] font-bold text-secondary-900 dark:text-white">---</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Last Updated</p>
                            <span id="view-quote-updated" class="text-[11px] font-bold text-secondary-900 dark:text-white">---</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Views</p>
                            <span id="view-quotation-views-count" class="text-[11px] font-bold text-secondary-900 dark:text-white">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-gray-100 dark:border-secondary-900 bg-gray-50/50 dark:bg-secondary-950/50 flex justify-end items-center space-x-4">
                <button type="button" class="close-view-quotation-modal px-5 py-3 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-secondary-900 dark:hover:text-white transition-colors">
                    Dismiss
                </button>
                <button type="button" id="view-quote-edit-btn" class="quote-admin-only px-8 py-3 text-xs font-black text-white bg-primary-400 hover:bg-primary-500 rounded-xl transition-all active:scale-95 shadow-lg shadow-primary-400/20 flex items-center gap-2 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Edit Quotation
                </button>
                <button type="button" id="view-quotation-contact-btn" class="px-8 py-3 text-xs font-black text-white bg-primary-400 hover:bg-primary-500 rounded-xl transition-all active:scale-95 shadow-lg shadow-primary-400/20 flex items-center gap-2 uppercase tracking-widest">
                    Message Owner
                </button>
            </div>
        </div>
    </div>
</div>