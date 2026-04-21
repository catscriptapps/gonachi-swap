// /resources/js/utils/quotations/delete-quotation.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';
import { QuoteCounter } from './quote-counter-helper.js';

/**
 * Attaches delete functionality via delegation.
 * Protected against multiple attachments.
 */
export function initDeleteQuotation(containerSelector = '#quotes-grid') {
    // 1. Singleton Check: Don't attach multiple listeners to the document
    if (window._deleteQuoteListenerAttached) return;
    
    const grid = document.querySelector(containerSelector);
    if (!grid) return;

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/quotations`, 'Quotation');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-quote-btn');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation(); 

        const card = btn.closest('.quotation-card-wrapper');
        const encodedId = btn.dataset.encodedId || card?.dataset.encodedId;

        if (!encodedId || !card) {
            console.error('Delete failed: Missing encoded ID or card element.');
            return;
        }

        deleteHandler.showConfirmation(encodedId, card, (success) => {
            if (!success) return;

            showToast('Project request successfully removed', 'success');

            // Delay counter update slightly to allow factory animations to finish
            setTimeout(() => {
                QuoteCounter.update();
                
                // Handle empty state
                const remainingCards = grid.querySelectorAll('.quotation-card-wrapper').length;
                if (remainingCards === 0) {
                    const container = document.getElementById('my-quotes-container');
                    if (container) {
                        // Ensure we don't double-inject if clicked twice
                        if (!document.getElementById('empty-quotes-state')) {
                            // Keep the Gonachi brand feel for the empty state
                            const emptyStateHtml = `
                                <div id="empty-quotes-state" class="p-20 text-center" data-aos="zoom-in">
                                    <div class="mb-4 flex justify-center text-gray-300">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <button class="text-primary-400 font-black hover:underline text-sm create-quote-trigger uppercase tracking-widest">
                                        Post your first quotation request
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

    // Mark as attached
    window._deleteQuoteListenerAttached = true;
}