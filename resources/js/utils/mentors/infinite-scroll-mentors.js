// /resources/js/utils/mentors/infinite-scroll-mentors.js

import { AnimationEngine } from '../animations.js';

/**
 * Handles Infinite Scroll for the Mentors Directory 💎
 * Used for the public listing of experts.
 */
export function initMentorInfiniteScroll() {
    const sentinel = document.getElementById('mentors-load-more-sentinel');
    const grid = document.getElementById('mentors-grid');
    const spinner = sentinel?.querySelector('.spinner-border');

    if (!sentinel || !grid) return;

    let page = 1;
    let isLoading = false;
    let hasMore = true;
    let currentFilters = {}; 
    let isResetting = false; 

    // Listen for filter/search updates to reset pagination
    window.addEventListener('mentors-search-updated', (e) => {
        currentFilters = e.detail.filters || {};
        page = 1;
        hasMore = true;
        isLoading = false;
        isResetting = true; 
        
        sentinel.style.display = 'flex';
        observer.observe(sentinel);

        setTimeout(() => {
            isResetting = false;
        }, 500);
    });

    const observer = new IntersectionObserver(async (entries) => {
        const entry = entries[0];

        if (entry.isIntersecting && !isLoading && hasMore && !isResetting) {
            isLoading = true;
            if (spinner) spinner.classList.remove('hidden');

            try {
                page++;
                const baseUrl = window.APP_CONFIG?.baseUrl || '/';
                
                // Construct URL with filters (Country, Type, Skill, etc.)
                const params = new URLSearchParams({
                    page: page,
                    ...currentFilters
                });

                const response = await fetch(`${baseUrl}api/mentors?${params.toString()}`, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();

                if (result.success && result.html && result.html.trim() !== '') {
                    // Append new mentor profile cards
                    grid.insertAdjacentHTML('beforeend', result.html);
                    
                    // Update global active count if the API returns it
                    const counter = document.getElementById('mentors-counter-number');
                    if (counter && result.total) {
                        counter.textContent = result.total;
                    }

                    // Refresh AOS animations
                    setTimeout(() => {
                        if (typeof AnimationEngine !== 'undefined') {
                            AnimationEngine.refresh();
                        }
                    }, 50);

                    hasMore = result.hasMore ?? true;
                } else {
                    hasMore = false;
                }

            } catch (err) {
                console.error('Error loading more mentors:', err);
                hasMore = false;
            } finally {
                isLoading = false;
                if (spinner) spinner.classList.add('hidden');
                
                if (!hasMore) {
                    observer.unobserve(sentinel);
                    sentinel.style.display = 'none';
                }
            }
        }
    }, {
        rootMargin: '300px', // Slightly larger margin for a "premium" seamless feel
        threshold: 0.1
    });

    observer.observe(sentinel);
}