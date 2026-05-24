// /resources/js/utils/listings/delete-listing.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';
import { ListingCounter } from './listing-counter-helper.js';

/**
 * Attaches delete functionality to the listings grid via delegation.
 */
export function initDeleteListing(gridSelector = '#listings-grid') {
    const grid = document.querySelector(gridSelector);
    if (!grid) return;

    // Initialize the factory pointing to the listings API
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/listings`, 'Listing');

    // Delegate click to delete buttons
    grid.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-listing-btn');
        if (!btn) return;

        e.stopPropagation(); // Prevent card click navigation / modal view trigger
        e.preventDefault();

        // Listing cards are wrappers with data-encoded-id
        const card = btn.closest('.listing-card-wrapper');
        const encodedId = card?.dataset.encodedId;

        if (!encodedId || !card) {
            console.error('Delete failed: Missing encoded ID or card element.');
            return;
        }

        // Trigger the factory's confirmation modal
        deleteHandler.showConfirmation(encodedId, card, (result) => {
            if (!result || !result.success) return;

            // 1. Show high-contrast toast
            showToast('Listing successfully deleted', 'success');

            // Update listings counter
            ListingCounter.update();

            // 2. Handle empty state if no cards are left
            const remainingCards = grid.querySelectorAll('.listing-card-wrapper').length;
            if (remainingCards === 0) {
                grid.classList.add('hidden');
                let emptyState = document.getElementById('empty-listings-state');
                if (!emptyState) {
                    emptyState = document.createElement('div');
                    emptyState.id = 'empty-listings-state';
                    emptyState.className = 'p-32 text-center bg-white dark:bg-secondary-950 rounded-[3rem] border border-dashed border-gray-200 dark:border-white/10';
                    emptyState.innerHTML = '<p class="text-gray-400 font-black uppercase tracking-widest text-sm">No items found matching your filters.</p>';
                    grid.parentNode.insertBefore(emptyState, grid);
                } else {
                    emptyState.classList.remove('hidden');
                }
            }
        });
    });
}
