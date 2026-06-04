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

    if (!listingsGrid) return;

    // Helper to gather all active filters and fetch listings
    const fetchFilteredListings = async () => {
        // Visual feedback during request
        if (loader) loader.classList.remove('hidden');
        listingsGrid.classList.add('opacity-40');

        const query = searchInput ? searchInput.value.trim() : '';

        // Gather categories
        const categoryInputs = document.querySelectorAll('input[name="category[]"]:checked');
        const categories = [];
        categoryInputs.forEach(input => {
            categories.push(input.value);
        });

        // Gather type
        const activeTypeBtn = document.querySelector('.listing-type-btn.active');
        const typeId = activeTypeBtn ? activeTypeBtn.dataset.typeId : 'all';

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            
            // Construct query parameters
            let url = `${baseUrl}api/listings?page=1&all=true`;
            if (query) {
                url += `&q=${encodeURIComponent(query)}`;
            }
            if (typeId && typeId !== 'all') {
                url += `&type_id=${encodeURIComponent(typeId)}`;
            }
            if (categories.length > 0) {
                categories.forEach(catId => {
                    url += `&categories[]=${encodeURIComponent(catId)}`;
                });
            }

            const response = await fetch(url, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();

            if (data.success) {
                const htmlContent = data.data ? data.data.map(item => item.cardHtml).join('') : '';
                const totalCount = parseInt(data.meta?.total) || 0;
                const hasMore = data.meta?.hasMore ?? false;

                // 1. Manage Visibility Logic 🚀
                if (totalCount === 0) {
                    // --- CASE: NO RESULTS ---
                    listingsGrid.classList.add('hidden');
                    listingsGrid.innerHTML = ''; // Clear stale cards
                    
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                    
                    if (noResultsFoundState) {
                        noResultsFoundState.classList.remove('hidden');
                        if (searchTermDisplay) {
                            if (query) {
                                searchTermDisplay.textContent = `"${query}"`;
                            } else {
                                searchTermDisplay.textContent = "selected filters";
                            }
                        }
                    }
                } else {
                    // --- CASE: RESULTS FOUND ---
                    listingsGrid.classList.remove('hidden');
                    listingsGrid.innerHTML = htmlContent;
                    
                    if (noResultsFoundState) noResultsFoundState.classList.add('hidden');
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                }

                // 2. Sync the Counter
                ListingCounter.update(totalCount);

                // 3. Notify Infinite Scroll to reset its page tracking
                window.dispatchEvent(new CustomEvent('listings-search-updated', {
                    detail: { 
                        query: query,
                        hasMore: hasMore
                    }
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
    };

    // Debounced version of fetching for search input (typing)
    const debouncedFetch = debounce(fetchFilteredListings, 400);

    // Event listener for search typing
    if (searchInput) {
        searchInput.addEventListener('input', debouncedFetch);
    }

    // Event listener for Category Checkboxes
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const filterAllCheckbox = document.getElementById('filter-all');

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            if (checkbox === filterAllCheckbox) {
                if (filterAllCheckbox.checked) {
                    // Uncheck all other category checkboxes
                    document.querySelectorAll('input[name="category[]"]').forEach(cb => {
                        cb.checked = false;
                    });
                }
            } else {
                // If any specific category is checked, uncheck "All"
                if (checkbox.checked && filterAllCheckbox) {
                    filterAllCheckbox.checked = false;
                }
                // If no specific categories are checked, check "All"
                const anyChecked = Array.from(document.querySelectorAll('input[name="category[]"]')).some(cb => cb.checked);
                if (!anyChecked && filterAllCheckbox) {
                    filterAllCheckbox.checked = true;
                }
            }
            fetchFilteredListings();
        });
    });

    // Event listener for Listing Type Buttons
    const typeButtons = document.querySelectorAll('.listing-type-btn');
    typeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('active')) return;

            // Remove active classes from all buttons
            typeButtons.forEach(b => {
                b.classList.remove('active', 'border-primary-500', 'bg-primary-500', 'text-secondary-950', 'shadow-lg', 'shadow-primary-500/20');
                b.classList.add('border-gray-100', 'dark:border-white/5', 'bg-white', 'dark:bg-secondary-950', 'text-gray-500', 'dark:text-gray-400');
                b.classList.remove('hover:border-primary-500', 'hover:text-primary-500');
                // Re-add hover states to inactive buttons
                b.classList.add('hover:border-primary-500', 'hover:text-primary-500');
            });

            // Add active classes to the clicked button
            btn.classList.add('active', 'border-primary-500', 'bg-primary-500', 'text-secondary-950', 'shadow-lg', 'shadow-primary-500/20');
            btn.classList.remove('border-gray-100', 'dark:border-white/5', 'bg-white', 'dark:bg-secondary-950', 'text-gray-500', 'dark:text-gray-400', 'hover:border-primary-500', 'hover:text-primary-500');

            fetchFilteredListings();
        });
    });
}
