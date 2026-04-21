// /resources/js/utils/listings/infinite-scroll-listings.js

import { AnimationEngine } from '../animations.js';
import { ListingCounter } from './listing-counter-helper.js';

/**
 * Handles Infinite Scroll for the Property Listings Grid 💎
 */
export function initListingInfiniteScroll() {
    const sentinel = document.getElementById('listings-load-more-sentinel');
    const grid = document.getElementById('listings-grid');
    const spinner = sentinel?.querySelector('.spinner-border');

    if (!sentinel || !grid) return;

    let page = 1;
    let isLoading = false;
    let hasMore = true;
    let currentQuery = ''; 
    let isResetting = false; // Prevents race conditions during search 🔒

    // Listen for search updates to reset state (triggered from your search component)
    window.addEventListener('listings-search-updated', (e) => {
        currentQuery = e.detail.query;
        page = 1;
        hasMore = true;
        isLoading = false;
        isResetting = true; 
        
        // Show sentinel again to allow scrolling on the new result set
        sentinel.style.display = 'flex';
        observer.observe(sentinel);

        // Allow scroll to resume after the search results render
        setTimeout(() => {
            isResetting = false;
        }, 500);
    });

    const observer = new IntersectionObserver(async (entries) => {
        const entry = entries[0];

        // Trigger only if intersecting AND not busy AND has more data
        if (entry.isIntersecting && !isLoading && hasMore && !isResetting) {
            isLoading = true;
            if (spinner) spinner.classList.remove('hidden');

            try {
                page++;
                const baseUrl = window.APP_CONFIG?.baseUrl || '/';
                const url = `${baseUrl}api/listings?page=${page}&q=${encodeURIComponent(currentQuery)}`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();

                if (result.success && result.html && result.html.trim() !== '') {
                    // Append the new property card HTML to the grid
                    grid.insertAdjacentHTML('beforeend', result.html);
                    
                    // Update the "Showing X" counter using the server-side total
                    ListingCounter.update(result.total); 

                    // Refresh AOS so the new property cards animate in properly
                    setTimeout(() => {
                        if (typeof AnimationEngine !== 'undefined') {
                            AnimationEngine.refresh();
                        }
                    }, 50);

                    // Update hasMore based on server-side pagination state
                    hasMore = result.hasMore ?? true;
                } else {
                    hasMore = false;
                }

            } catch (err) {
                console.error('Error loading more listings:', err);
                hasMore = false;
            } finally {
                isLoading = false;
                if (spinner) spinner.classList.add('hidden');
                
                // Stop observing if we've reached the end of the line
                if (!hasMore) {
                    observer.unobserve(sentinel);
                    sentinel.style.display = 'none';
                }
            }
        }
    }, {
        rootMargin: '250px', // Trigger earlier for that smooth Gonachi UX
        threshold: 0.1
    });

    observer.observe(sentinel);
}