// /resources/js/utils/adverts/delete-advert.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';
import { AdCounter } from './ad-counter-helper.js';

/**
 * Attaches delete functionality to the adverts grid via delegation.
 */
export function initDeleteAdvert(containerSelector = '#ads-grid') {
    const grid = document.querySelector(containerSelector);
    if (!grid) return;

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/adverts`, 'Advert');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-ad-btn');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation(); 

        const card = btn.closest('.ad-card-wrapper');
        const encodedId = btn.dataset.encodedId || card?.dataset.encodedId;

        if (!encodedId || !card) {
            console.error('Delete failed: Missing encoded ID or card element.');
            return;
        }

        // Trigger the factory's confirmation modal
        deleteHandler.showConfirmation(encodedId, card, (success) => {
            if (!success) return;

            // 1. Show high-contrast toast (Gonachi Style)
            showToast('Advert successfully removed', 'success');

            // 2. 🚀 UPDATE COUNTER 
            // We wait to ensure the factory has finished removing the element from the DOM
            setTimeout(() => {
                AdCounter.update();
                
                const remainingCards = grid.querySelectorAll('.ad-card-wrapper').length;
                
                if (remainingCards === 0) {
                    const container = document.getElementById('my-ads-container');
                    if (container) {
                        // 1. Hide the grid, don't destroy it!
                        grid.classList.add('hidden');

                        // 2. Insert the empty state with the correct Gonachi IDs and colors
                        const emptyStateHtml = `
                            <div id="empty-ads-state" class="p-20 text-center" data-aos="zoom-in">
                                <div class="max-w-xs mx-auto space-y-4">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium italic">
                                        You have no adverts currently.
                                    </p>
                                    <button class="text-orange-600 font-bold hover:underline text-sm create-ad-trigger uppercase tracking-widest">
                                        Create your first ad
                                    </button>
                                </div>
                            </div>
                        `;
                        // Use insertAdjacentHTML so we don't overwrite the hidden grid
                        container.insertAdjacentHTML('afterbegin', emptyStateHtml);
                    }
                }
            }, 150);
        });
    });
}