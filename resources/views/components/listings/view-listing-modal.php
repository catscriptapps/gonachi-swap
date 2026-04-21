<?php
// /resources/views/components/listings/view-listing-modal.php
?>

<style>
    /* Custom Stylish Scrollbar for the Modal Content */
    #view-listing-modal-content::-webkit-scrollbar {
        width: 6px;
    }

    #view-listing-modal-content::-webkit-scrollbar-track {
        background: transparent;
        margin: 10px;
    }

    #view-listing-modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        border: 2px solid transparent;
    }

    .dark #view-listing-modal-content::-webkit-scrollbar-thumb {
        background: #334155;
    }

    #view-listing-modal-content::-webkit-scrollbar-thumb:hover {
        background: #8b5cf6;
        /* Primary 500 */
    }
</style>

<div id="view-listing-modal" class="fixed inset-0 z-50 hidden">
    <div id="close-view-listing-modal-overlay" class="absolute inset-0 bg-secondary-955/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 w-full max-w-5xl rounded-[2.5rem] shadow-2xl border border-gray-200 dark:border-secondary-900 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">

            <div class="px-8 py-5 border-b border-gray-100 dark:border-secondary-900 flex items-center justify-between bg-gray-50/50 dark:bg-secondary-950/50">
                <div class="flex items-center space-x-5 overflow-hidden">
                    <div id="view-listing-icon-container" class="h-14 w-14 flex-shrink-0 rounded-2xl bg-primary-400 flex items-center justify-center text-white shadow-lg shadow-primary-400/20">
                        <span id="view-listing-initial" class="text-2xl font-black uppercase"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-black text-secondary-900 dark:text-white truncate leading-tight" id="view-listing-title">Listing Title</h3>
                            <span id="view-listing-status" class="px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-primary-100 bg-primary-50 text-primary-400">Active</span>
                        </div>

                        <p id="view-listing-category-sub" class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">Real Estate</p>

                        <div class="flex items-center gap-1.5 mt-1 text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span id="view-listing-location-header" class="text-[10px] font-bold uppercase tracking-wider truncate">Location, Country</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="close-view-listing-modal text-gray-400 hover:text-primary-400 p-2 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="view-listing-modal-content" class="p-8 space-y-6 font-sans overflow-y-auto max-h-[70vh]">

                <?php
                $modalDetailOwnerId = 'listing';
                $modalDetailOwnerTitle = 'Listing Agent / Owner';
                include __DIR__ . '/../ui/modal-detail-owner.php';
                ?>

                <div class="space-y-4 px-2">
                    <div class="flex items-center justify-between pb-2">
                        <div class="flex items-center gap-3">
                            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2">
                                <span class="w-8 h-[2px] bg-primary-400"></span>
                                <i class="bi bi-images"></i> Listing Photos
                            </h3>
                            <span id="listing-pics-count" class="text-[10px] font-bold text-primary-400 bg-primary-50 dark:bg-primary-950/30 px-2 py-0.5 rounded-md border border-primary-100 dark:border-primary-900/50">0</span>
                        </div>

                        <button type="button" id="trigger-listing-pic-upload" class="listing-admin-only flex items-center gap-1.5 px-3 py-1.5 bg-primary-50 dark:bg-primary-900/20 text-primary-400 hover:bg-primary-400 hover:text-white rounded-lg transition-all group">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-tight">Add Photo</span>
                        </button>
                    </div>

                    <div id="listing-pics-wrapper" class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    </div>

                    <input type="file" id="listing-pic-input" class="hidden" accept="image/*" multiple>
                </div>

                <div id="section-location" class="bg-gray-50/50 dark:bg-secondary-900/10 p-4 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
                    <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-primary-400"></span>
                        <i class="bi bi-geo-alt-fill"></i> Property Location
                    </h3>
                    <div class="flex flex-row gap-6">
                        <div class="w-1/2">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Street Address</p>
                            <p id="view-listing-address" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                        <div class="w-1/4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Region / State</p>
                            <p id="view-listing-region" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                        <div class="w-1/4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Country</p>
                            <p id="view-listing-country" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                        <div class="w-1/4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">City</p>
                            <p id="view-listing-city" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                    </div>
                </div>

                <div class="px-2">
                    <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-primary-400"></span>
                        <i class="bi bi-card-text"></i> Description
                    </h3>
                    <div class="bg-white dark:bg-secondary-900/20 p-4 rounded-2xl border border-gray-100 dark:border-secondary-800">
                        <p id="view-listing-description" class="text-sm text-secondary-900 dark:text-gray-300 leading-relaxed whitespace-pre-line">---</p>
                    </div>
                </div>

                <div id="section-property-details" class="bg-gray-50/50 dark:bg-secondary-900/10 p-8 rounded-[2rem] shadow-xl border border-gray-100 dark:border-secondary-800">
                    <h3 class="text-xs font-black text-secondary-900 dark:text-white uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-secondary-900 dark:bg-white"></span>
                        <i class="bi bi-building"></i> Property Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Unit Type</p>
                                <p id="view-listing-unit-type" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">House Style</p>
                                <p id="view-listing-house-type" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Bedrooms</p>
                                <p id="view-listing-bedrooms" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Bathrooms</p>
                                <p id="view-listing-bathrooms" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Property Size</p>
                                <p id="view-listing-size" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Parking</p>
                                <p id="view-listing-parking" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="section-amenities" class="px-2">
                    <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                        <span class="w-8 h-[2px] bg-primary-400"></span>
                        <i class="bi bi-stars"></i> Included Amenities
                    </h3>
                    <div id="view-listing-amenities-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 bg-white dark:bg-secondary-900/10 p-4 rounded-2xl border border-gray-100 dark:border-secondary-800">
                        <p class="text-xs text-gray-400 italic col-span-full">No specific amenities listed.</p>
                    </div>
                </div>

                <div id="section-availability-financials" class="px-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                            <span class="w-8 h-[2px] bg-primary-400"></span>
                            <i class="bi bi-calendar-check"></i> Availability & Features
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Air Conditioning</span>
                                <span id="view-listing-is-ac" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Furnished</span>
                                <span id="view-listing-is-furnished" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Pets Allowed</span>
                                <span id="view-listing-pets" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                            <span class="w-8 h-[2px] bg-primary-400"></span>
                            <i class="bi bi-cash-coin"></i> Financials
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Price / Rent</span>
                                <span id="view-listing-price" class="text-xs font-black text-primary-400">---</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Agreement Type</span>
                                <span id="view-listing-agreement" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-secondary-900 pb-2">
                                <span class="text-xs text-gray-500">Move-in Date</span>
                                <span id="view-listing-move-in" class="text-xs font-bold text-secondary-900 dark:text-white">---</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-4 p-5 rounded-2xl bg-secondary-900 text-white">
                        <div class="bg-white/10 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-[#FF0000]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Property Video</p>
                            <a id="view-listing-url" href="#" target="_blank" class="block text-sm font-bold text-primary-400 truncate hover:text-primary-300">No video available</a>
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
                            <p id="view-listing-phone" class="text-sm font-bold text-secondary-900 dark:text-white">---</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-gray-200 dark:border-secondary-900">
                    <div class="flex gap-8">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Date Created</p>
                            <span id="view-listing-created" class="text-[11px] font-bold text-secondary-900 dark:text-white">---</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Last Updated</p>
                            <span id="view-listing-updated" class="text-[11px] font-bold text-secondary-900 dark:text-white">---</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Views</p>
                            <span id="view-listing-views-count" class="text-[11px] font-bold text-secondary-900 dark:text-white">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-4 border-t border-gray-100 dark:border-secondary-900 bg-gray-50/50 dark:bg-secondary-950/50 flex justify-end items-center space-x-4">
                <button type="button" class="close-view-listing-modal px-5 py-3 text-xs font-black text-gray-500 uppercase tracking-widest hover:text-secondary-900 dark:hover:text-white transition-colors">
                    Dismiss
                </button>
                <button type="button" id="view-listing-edit-btn" class="listing-admin-only px-8 py-3 text-xs font-black text-white bg-primary-400 hover:bg-primary-500 rounded-xl transition-all active:scale-95 shadow-lg shadow-primary-400/20 flex items-center gap-2 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Edit Listing
                </button>
                <button type="button" id="view-listing-contact-btn" class="px-8 py-3 text-xs font-black text-white bg-primary-400 hover:bg-primary-500 rounded-xl transition-all active:scale-95 shadow-lg shadow-primary-400/20 flex items-center gap-2 uppercase tracking-widest">
                    Message Owner
                </button>
            </div>
        </div>
    </div>
</div>