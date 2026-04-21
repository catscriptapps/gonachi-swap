import { debounce } from '../debounce.js';

/**
 * Mentor Directory Search & Filter Handler 💎
 * Handles debounced searching, empty states, and infinite scroll resets
 */
export function initMentorSearch() {
    const searchInput = document.getElementById('mentor-search-input');
    const typeFilter = document.getElementById('mentor-type-filter'); 
    const mentorsGrid = document.getElementById('mentors-grid');
    const loader = document.getElementById('mentor-search-loader'); 
    
    // States matching your PHP IDs
    const initialEmptyState = document.getElementById('empty-mentors-state'); 
    const noResultsFoundState = document.getElementById('no-mentors-found'); 
    const searchTermDisplay = document.getElementById('mentor-search-term-display'); 

    if (!searchInput || !mentorsGrid || !typeFilter) return;

    /**
     * The core search logic 🚀
     */
    const handleSearch = debounce(async () => {
        const query = searchInput.value.trim();
        const targetType = typeFilter.value; 
        
        // Visual feedback
        if (loader) loader.classList.remove('hidden');
        mentorsGrid.classList.add('opacity-40');

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            
            // Build URL with parameters
            const url = new URL(`${baseUrl}api/mentors`, window.location.origin);
            url.searchParams.append('q', query);
            url.searchParams.append('target_type', targetType);

            const response = await fetch(url.toString(), {
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

                // 1. Manage Visibility Logic
                if (totalCount === 0) {
                    mentorsGrid.classList.add('hidden');
                    mentorsGrid.innerHTML = ''; 
                    
                    const isFiltering = (query !== "" || targetType !== "0");

                    if (isFiltering) {
                        initialEmptyState?.classList.add('hidden');
                        noResultsFoundState?.classList.remove('hidden');
                        if (searchTermDisplay) {
                            searchTermDisplay.textContent = query || 'this category';
                        }
                    } else {
                        initialEmptyState?.classList.remove('hidden');
                        noResultsFoundState?.classList.add('hidden');
                    }
                } else {
                    mentorsGrid.classList.remove('hidden');
                    mentorsGrid.innerHTML = htmlContent;
                    
                    if (noResultsFoundState) noResultsFoundState.classList.add('hidden');
                    if (initialEmptyState) initialEmptyState.classList.add('hidden');
                }

                // 2. Update Global Counter
                const counter = document.getElementById('mentors-counter-number');
                if (counter) counter.textContent = totalCount;

                // 3. Notify Infinite Scroll to reset
                window.dispatchEvent(new CustomEvent('mentors-search-updated', {
                    detail: { filters: { q: query, target_type: targetType } }
                }));

                // 4. Re-bind View/Message actions
                document.dispatchEvent(new CustomEvent('mentor:updated'));
                
                // 5. Refresh animations
                if (window.AOS) {
                    setTimeout(() => window.AOS.refreshHard(), 150);
                }
            }
        } catch (error) {
            console.error('Mentor Filter Error:', error);
        } finally {
            if (loader) loader.classList.add('hidden');
            mentorsGrid.classList.remove('opacity-40');
        }
    }, 400);

    // Trigger search on typing
    searchInput.addEventListener('input', handleSearch);
    
    // Trigger search on selecting a type
    typeFilter.addEventListener('change', handleSearch);

    /**
     * Reset Filters - SPA Style ⚡
     * Clear the UI and re-run handleSearch
     */
    const resetBtn = document.getElementById('clear-mentor-filters'); 
    if (resetBtn) {
        resetBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Clear inputs
            searchInput.value = '';
            typeFilter.value = '0';
            
            // Trigger the search logic to refresh the grid
            handleSearch();
        });
    }
}