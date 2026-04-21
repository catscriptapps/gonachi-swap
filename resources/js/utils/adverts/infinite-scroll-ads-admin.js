// /resources/js/utils/adverts/infinite-scroll-ads-admin.js

import { AnimationEngine } from '../animations.js';
import { updateCount } from '../../components/table-pagination-count.js';

/**
 * Handles Infinite Scroll for the Adverts Admin Table
 */
export function initAdAdminInfiniteScroll() {
    const sentinel = document.getElementById('ad-admin-load-more-sentinel');
    const tbody = document.getElementById('adverts-tbody');
    const spinner = sentinel?.querySelector('.spinner-border');

    if (!sentinel || !tbody) return;

    let page = 1;
    let isLoading = false;
    let hasMore = true;
    let currentQuery = '';
    let currentTab = 'all'; 
    let isResetting = false;

    /**
     * RESET LOGIC: Triggered by Search or Tab Switches
     */
    const resetScroll = (query = '', tab = 'all') => {
        currentQuery = query;
        currentTab = tab;
        page = 1;
        hasMore = true;
        isLoading = false;
        isResetting = true;

        sentinel.style.display = 'flex';
        observer.observe(sentinel);

        setTimeout(() => { isResetting = false; }, 500);
    };

    // Listen for search updates (from table-search.js)
    window.addEventListener('adverts-admin-search-updated', (e) => {
        resetScroll(e.detail.query, currentTab);
    });

    // Listen for tab switches (from admin-tabs.js)
    window.addEventListener('adverts-admin-tab-changed', (e) => {
        resetScroll(currentQuery, e.detail.tab);
    });

    const observer = new IntersectionObserver(async (entries) => {
        const entry = entries[0];

        if (entry.isIntersecting && !isLoading && hasMore && !isResetting) {
            isLoading = true;
            if (spinner) spinner.classList.remove('hidden');

            try {
                page++;
                const baseUrl = window.APP_CONFIG?.baseUrl || '/';
                
                // Construct URL with page, search query, and current tab filter
                const url = `${baseUrl}api/adverts?page=${page}&q=${encodeURIComponent(currentQuery)}&status=${currentTab}&admin_view=true`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const result = await response.json();

                if (result.success && result.html && result.html.trim() !== '') {
                    // Append rows to the table body
                    tbody.insertAdjacentHTML('beforeend', result.html);
                    
                    // Update the "Showing X of Y" counter
                    updateCount('advert', '#adverts-tbody', '#adverts-count');

                    // Animation Refresh
                    setTimeout(() => { AnimationEngine.refresh(); }, 50);

                    hasMore = result.hasMore ?? true;
                } else {
                    hasMore = false;
                }

            } catch (err) {
                console.error('Error loading admin adverts:', err);
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
        rootMargin: '100px', // Tables are denser, shorter margin
        threshold: 0.1
    });

    observer.observe(sentinel);
}