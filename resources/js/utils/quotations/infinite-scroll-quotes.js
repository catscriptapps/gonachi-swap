// /resources/js/utils/quotations/infinite-scroll-quotes.js

import { AnimationEngine } from '../animations.js';
import { QuoteCounter } from './quote-counter-helper.js';

/**
 * Handles Infinite Scroll for the Quotations Grid 💎
 */
export function initQuoteInfiniteScroll() {
    const sentinel = document.getElementById('quotes-load-more-sentinel');
    const grid = document.getElementById('quotes-grid');
    const spinner = sentinel?.querySelector('.spinner-border');

    if (!sentinel || !grid) return;

    let page = 1;
    let isLoading = false;
    let hasMore = true;
    let currentQuery = ''; 
    let isResetting = false; // Prevents race conditions during search 🔒

    // Listen for search updates to reset state
    window.addEventListener('quotes-search-updated', (e) => {
        currentQuery = e.detail.query;
        page = 1;
        hasMore = true;
        isLoading = false;
        isResetting = true; 
        
        // Show sentinel again if it was hidden
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
                const url = `${baseUrl}api/quotations?page=${page}&q=${encodeURIComponent(currentQuery)}`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();

                if (result.success && result.html && result.html.trim() !== '') {
                    // Append the new project card HTML to the grid
                    grid.insertAdjacentHTML('beforeend', result.html);
                    
                    // Update the "Showing X" counter using the server-side total
                    QuoteCounter.update(result.total); 

                    // Refresh AOS so the new project cards animate in properly
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
                console.error('Error loading more quotations:', err);
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
        rootMargin: '250px', // Trigger slightly earlier for a smoother feel
        threshold: 0.1
    });

    observer.observe(sentinel);
}