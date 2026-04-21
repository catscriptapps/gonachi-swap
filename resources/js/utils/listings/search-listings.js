// /resources/js/utils/listings/search-listings.js

import { debounce } from '../debounce.js';
import { ListingCounter } from './listing-counter-helper.js';

/**
 * Property Listing Search Handler - Gonachi Edition 💎
 * Handles debounced searching, empty states, and infinite scroll resets
 */
export function initListingSearch() {
    const searchInput = document.getElementById('listing-search-input');
    const listingsGrid = document.getElementById('listings-grid');
    const loader = document.getElementById('listing-search-loader'); 
    
    // States
    const initialEmptyState = document.getElementById('empty-listings-state'); // "Post your first"
    const noResultsFoundState = document.getElementById('no-listings-found-state'); // "No matches found"
    const searchTermDisplay = document.getElementById('listing-search-term-display'); // The <span> for the query

    if (!searchInput || !listingsGrid) return;

    const handleSearch = debounce(async (e) => {
        const query = e.target.value.trim();
        
        // Visual feedback during request
        if (loader) loader.classList.remove('hidden');
        listingsGrid.classList.add('opacity-40');

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            // Targeting the listings-specific index endpoint
            const response = await fetch(`${baseUrl}my-listings?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();

            if (data.success) {
                const htmlContent = data.html || '';
                const totalCount = parseInt(data.total) || 0;

                // 1. Manage Visibility Logic 🚀
                if (totalCount === 0) {
                    // --- CASE: NO RESULTS ---
                    listingsGrid.classList.add('hidden');
                    listingsGrid.innerHTML = ''; // Clear stale cards
                    
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                    
                    if (noResultsFoundState) {
                        noResultsFoundState.classList.remove('hidden');
                        if (searchTermDisplay) searchTermDisplay.textContent = query;
                    }
                } else {
                    // --- CASE: RESULTS FOUND ---
                    listingsGrid.classList.remove('hidden');
                    listingsGrid.innerHTML = htmlContent;
                    
                    if (noResultsFoundState) noResultsFoundState.classList.add('hidden');
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                }

                // 2. Sync the Counter
                // Triggers the hide/show logic in ListingCounter for the icon and subtext
                ListingCounter.update(totalCount);

                // 3. Notify Infinite Scroll to reset its page tracking
                window.dispatchEvent(new CustomEvent('listings-search-updated', {
                    detail: { query: query }
                }));

                // 4. Re-bind grid actions (View/Edit/Delete)
                document.dispatchEvent(new CustomEvent('listing:updated'));
                
                // 5. Hard refresh animations for new cards (AOS)
                if (window.AOS) {
                    setTimeout(() => window.AOS.refreshHard(), 150);
                }
            }
        } catch (error) {
            console.error('Gonachi Listing Search Error:', error);
        } finally {
            if (loader) loader.classList.add('hidden');
            listingsGrid.classList.remove('opacity-40');
        }
    }, 400);

    searchInput.addEventListener('input', handleSearch);
}