// /resources/js/utils/listings/view-media.js

import { updateMediaCountLabel, photosEmptyStatePlaceholder } from "../helpers.js";
import { registerImagePreview } from "../globals/preview.js";
import { deleteMedia } from "../media-manager.js";

/**
 * Fetches and renders gallery images for a specific marketplace listing.
 * 
 * @param {number|string} listingId - The ID of the listing.
 * @param {boolean|null} canManage - Permission flag. If null, it retrieves the persisted state from the DOM.
 */
export async function viewMedia(listingId, canManage = null) {
    const grid = document.getElementById('listing-pics-wrapper');
    if (!grid) return;

    // 1. Persistence: Save permission state on first load, or retrieve it on refresh
    if (canManage !== null) {
        grid.dataset.canManage = String(canManage); // Force string 'true'/'false'
    } else {
        // Fallback to DOM state for refreshes/re-renders
        canManage = grid.dataset.canManage === 'true';
    }

    // --- DELETE LISTENER START ---
    if (!grid._deleteAttached) {
        grid.addEventListener('click', async (e) => {
            const btn = e.target.closest('[data-action="delete-picture"]');
            if (btn) {
                const picId = btn.getAttribute('data-id');
                // Use the listingId from the outer scope
                await deleteMedia({
                    type: 'listing',
                    picId: picId,
                    parentId: listingId
                });
            }
        });
        grid._deleteAttached = true;
    }
    // --- DELETE LISTENER END ---

    try {
        const response = await fetch(`${window.APP_CONFIG.baseUrl}api/listing-pictures?id=${listingId}`);
        const result = await response.json();

        if (result.success) {
            // Update Count Label in Modal Header
            updateMediaCountLabel('listing-pics-count', result.pictures.length);

            // Clean up old elements (cards and empty state placeholders)
            grid.querySelectorAll('.relative.group, .empty-state-placeholder').forEach(el => el.remove());

            if (result.pictures.length === 0) {
                photosEmptyStatePlaceholder(grid);
                return;
            }

            result.pictures.forEach(pic => {
                const imgSrc = `${window.APP_CONFIG.baseUrl}images/uploads/listings/${pic.pic_name}`;

                // 🛡️ Only generate the delete button HTML if the user has management rights
                const deleteBtnHtml = canManage ? `
                    <button type="button" data-action="delete-picture" data-id="${pic.pic_id || pic.id}" class="p-2 bg-red-500/80 hover:bg-red-500 rounded-full text-white transition-colors" title="Delete Photo">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>` : '';

                const html = `
                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                        <img src="${imgSrc}" 
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                            alt="Listing Photo">
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button type="button" data-action="view-picture" data-img-src="${imgSrc}" class="p-2 bg-white/20 hover:bg-white/40 rounded-full backdrop-blur-md text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            ${deleteBtnHtml}
                        </div>
                    </div>
                `;
                grid.insertAdjacentHTML('beforeend', html);
            });

            // Re-initialize lightbox/previewer for newly added DOM elements
            registerImagePreview();
        }
    } catch (error) {
        console.error('Failed to refresh listing pictures:', error);
    }
}