// /resources/js/utils/adverts/infinite-scroll-ads.js

import { AnimationEngine } from '../animations.js';
import { AdCounter } from './ad-counter-helper.js';

/**
 * Handles Infinite Scroll for the Adverts Grid
 * Patterned after Users Infinite Scroll 🚀
 */
export function initAdInfiniteScroll() {
    const sentinel = document.getElementById('ads-load-more-sentinel');
    const grid = document.getElementById('ads-grid');
    const spinner = sentinel?.querySelector('.spinner-border');

    if (!sentinel || !grid) return;

    let page = 1;
    let isLoading = false;
    let hasMore = true;
    let currentQuery = ''; 
    let isResetting = false; // Flag to prevent race conditions during search 🔒

    // Listen for search updates to reset state
    window.addEventListener('ads-search-updated', (e) => {
        currentQuery = e.detail.query;
        page = 1;
        hasMore = true;
        isLoading = false;
        isResetting = true; // Block the observer momentarily
        
        // Show sentinel again if it was hidden
        sentinel.style.display = 'flex';
        observer.observe(sentinel);

        // Allow scroll to resume after the search results have a chance to render
        setTimeout(() => {
            isResetting = false;
        }, 500);
    });

    const observer = new IntersectionObserver(async (entries) => {
        const entry = entries[0];

        // Check if intersecting AND not loading AND has more AND we aren't currently resetting search
        if (entry.isIntersecting && !isLoading && hasMore && !isResetting) {
            isLoading = true;
            if (spinner) spinner.classList.remove('hidden');

            try {
                page++;
                const baseUrl = window.APP_CONFIG?.baseUrl || '/';
                const url = `${baseUrl}api/adverts?page=${page}&q=${encodeURIComponent(currentQuery)}`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();

                if (result.success && result.html && result.html.trim() !== '') {
                    // Append the new card HTML to the grid
                    grid.insertAdjacentHTML('beforeend', result.html);
                    AdCounter.update(result.total); // Updates "Showing X"

                    // Refresh AOS so the new cards animate in properly
                    setTimeout(() => {
                        AnimationEngine.refresh();
                    }, 50);

                    // Update hasMore based on server response
                    hasMore = result.hasMore ?? true;
                } else {
                    hasMore = false;
                }

            } catch (err) {
                console.error('Error loading more adverts:', err);
                hasMore = false;
            } finally {
                isLoading = false;
                if (spinner) spinner.classList.add('hidden');
                
                // If no more ads, stop observing to save resources
                if (!hasMore) {
                    observer.unobserve(sentinel);
                    sentinel.style.display = 'none';
                }
            }
        }
    }, {
        rootMargin: '200px', // Start loading before the user hits the very bottom
        threshold: 0.1
    });

    observer.observe(sentinel);
}