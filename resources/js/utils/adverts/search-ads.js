// /resources/js/utils/adverts/search-ads.js

import { debounce } from '../debounce.js';
import { AdCounter } from './ad-counter-helper.js';

export function initAdSearch() {
    const searchInput = document.getElementById('ad-search-input');
    const adsGrid = document.getElementById('ads-grid');
    const emptyState = document.getElementById('empty-ads-state');
    const searchTermDisplay = document.getElementById('search-term-display');
    const loader = document.getElementById('search-loader');

    if (!searchInput || !adsGrid) return;

    const handleSearch = debounce(async (e) => {
        const query = e.target.value.trim();
        
        if (loader) loader.classList.remove('hidden');
        adsGrid.classList.add('opacity-40');

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/adverts?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();

            if (data.success) {
                const htmlContent = data.html || '';
                
                // 1. Toggle Visibility Logic 🚀
                if (htmlContent.trim() === '') {
                    adsGrid.classList.add('hidden');
                    if (emptyState) {
                        emptyState.classList.remove('hidden');
                        if (searchTermDisplay) searchTermDisplay.textContent = query;
                    }
                } else {
                    adsGrid.classList.remove('hidden');
                    if (emptyState) emptyState.classList.add('hidden');
                    adsGrid.innerHTML = htmlContent;
                }

                AdCounter.update(data.total); // Syncs DOM count and server total

                // 2. Notify Infinite Scroll
                window.dispatchEvent(new CustomEvent('ads-search-updated', {
                    detail: { query: query }
                }));

                // 3. Re-bind actions (Edit/Delete)
                window.dispatchEvent(new CustomEvent('ads-content-updated'));
                
                if (window.AOS) {
                    setTimeout(() => window.AOS.refreshHard(), 100);
                }
            }
        } catch (error) {
            console.error('Gonachi Search Error:', error);
        } finally {
            if (loader) loader.classList.add('hidden');
            adsGrid.classList.remove('opacity-40');
        }
    }, 400);

    searchInput.addEventListener('input', handleSearch);
}