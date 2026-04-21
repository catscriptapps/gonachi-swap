// /resources/js/utils/listings/delete-listing.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';
import { ListingCounter } from './listing-counter-helper.js';

/**
 * Attaches delete functionality for property listings via delegation.
 * Protected against multiple attachments.
 */
export function initDeleteListing(containerSelector = '#listings-grid') {
    // 1. Singleton Check: Don't attach multiple listeners to the document
    if (window._deleteListingListenerAttached) return;
    
    const grid = document.querySelector(containerSelector);
    if (!grid) return;

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    // Connects to the Listing API endpoint
    const deleteHandler = createDeleteHandler(`${baseUrl}api/listings`, 'Listing');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-listing-btn');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation(); 

        const card = btn.closest('.listing-card-wrapper');
        const encodedId = btn.dataset.encodedId || card?.dataset.encodedId;

        if (!encodedId || !card) {
            console.error('Delete failed: Missing encoded ID or card element.');
            return;
        }

        // Use the factory's confirmation logic (sweetalert/modal)
        deleteHandler.showConfirmation(encodedId, card, (success) => {
            if (!success) return;

            showToast('Property listing successfully removed', 'success');

            // Delay counter update slightly to allow factory removal animations to finish
            setTimeout(() => {
                ListingCounter.update();
                
                // Handle empty state if no listings remain
                const remainingCards = grid.querySelectorAll('.listing-card-wrapper').length;
                if (remainingCards === 0) {
                    const container = document.getElementById('my-listings-container');
                    if (container) {
                        // Ensure we don't double-inject if clicked twice
                        if (!document.getElementById('empty-listings-state')) {
                            // Gonachi brand feel for the empty property state
                            const emptyStateHtml = `
                                <div id="empty-listings-state" class="p-20 text-center" data-aos="zoom-in">
                                    <div class="mb-4 flex justify-center text-gray-300">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <button class="text-primary-400 font-black hover:underline text-sm create-listing-trigger uppercase tracking-widest">
                                        Post your first property listing
                                    </button>
                                </div>
                            `;
                            container.insertAdjacentHTML('afterbegin', emptyStateHtml);
                        }
                    }
                }
            }, 300); 
        });
    });

    // Mark as attached to prevent duplicate listeners on page navigation
    window._deleteListingListenerAttached = true;
}