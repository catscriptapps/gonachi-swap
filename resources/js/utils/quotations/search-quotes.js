// /resources/js/utils/quotations/search-quotes.js

import { debounce } from '../debounce.js';
import { QuoteCounter } from './quote-counter-helper.js';

/**
 * Project Quotation Search Handler - Gonachi Edition 💎
 * Handles debounced searching, empty states, and infinite scroll resets
 */
export function initQuoteSearch() {
    const searchInput = document.getElementById('quote-search-input');
    const quotesGrid = document.getElementById('quotes-grid');
    const loader = document.getElementById('quote-search-loader'); 
    
    // States
    const initialEmptyState = document.getElementById('empty-quotes-state'); // "Post your first"
    const noResultsFoundState = document.getElementById('no-quotes-found-state'); // "No matches found"
    const searchTermDisplay = document.getElementById('quote-search-term-display'); // The <span> for the query

    if (!searchInput || !quotesGrid) return;

    const handleSearch = debounce(async (e) => {
        const query = e.target.value.trim();
        
        // Visual feedback during request
        if (loader) loader.classList.remove('hidden');
        quotesGrid.classList.add('opacity-40');

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}my-quotations?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();

            if (data.success) {
                const htmlContent = data.html || '';
                // Ensure we use the total count from the server response
                const totalCount = parseInt(data.total) || 0;

                // 1. Manage Visibility Logic 🚀
                if (totalCount === 0) {
                    // --- CASE: NO RESULTS ---
                    quotesGrid.classList.add('hidden');
                    // Clear grid content so it doesn't hold old cards
                    quotesGrid.innerHTML = ''; 
                    
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                    
                    if (noResultsFoundState) {
                        noResultsFoundState.classList.remove('hidden');
                        if (searchTermDisplay) searchTermDisplay.textContent = query;
                    }
                } else {
                    // --- CASE: RESULTS FOUND ---
                    quotesGrid.classList.remove('hidden');
                    quotesGrid.innerHTML = htmlContent;
                    
                    if (noResultsFoundState) noResultsFoundState.classList.add('hidden');
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                }

                // 2. Sync the Counter (Force update with server total)
                // This triggers the hide/show logic in QuoteCounter for the icon and subtext
                QuoteCounter.update(totalCount);

                // 3. Notify Infinite Scroll to reset its page tracking
                window.dispatchEvent(new CustomEvent('quotes-search-updated', {
                    detail: { query: query }
                }));

                // 4. Re-bind grid actions (View/Edit/Delete) via main page listener
                document.dispatchEvent(new CustomEvent('quotation:updated'));
                
                // 5. Hard refresh animations for new cards
                if (window.AOS) {
                    setTimeout(() => window.AOS.refreshHard(), 150);
                }
            }
        } catch (error) {
            console.error('Gonachi Quotation Search Error:', error);
        } finally {
            if (loader) loader.classList.add('hidden');
            quotesGrid.classList.remove('opacity-40');
        }
    }, 400);

    searchInput.addEventListener('input', handleSearch);
}