// /resources/js/utils/advertisements/ad-counter-helper.js

export const AdCounter = {
    /**
     * Updates the UI count based on DOM elements or a forced number
     */
    update: (manualCount = null) => {
        const countSpan = document.getElementById('ads-counter-number');
        const grid = document.getElementById('ads-grid');

        if (!countSpan || !grid) return;

        // If a manual count (like a total from server) is provided, use it.
        // Otherwise, count the actual card wrappers in the DOM.
        const finalCount = (manualCount !== null) 
            ? manualCount 
            : grid.querySelectorAll('.ad-card-wrapper').length;

        countSpan.textContent = finalCount;
        
        // Toggle visibility of the whole line if 0
        const parentP = countSpan.closest('p');
        if (parentP) {
            finalCount > 0 ? parentP.classList.remove('hidden') : parentP.classList.add('hidden');
        }
    }
};