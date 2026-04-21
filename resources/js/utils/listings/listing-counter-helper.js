// /resources/js/utils/listings/listing-counter-helper.js

/**
 * Handles dynamic UI updates for property listing counts - Gonachi Edition 💎
 */
export const ListingCounter = {
    /**
     * Updates the counter badge and toggles visibility of the counter container
     * @param {number|null} manualCount - Optional explicit count from server response
     */
    update: (manualCount = null) => {
        const countSpan = document.getElementById('listings-counter-number');
        const grid = document.getElementById('listings-grid');

        if (!countSpan) return;

        // Determine count based on manual input or DOM scanning
        const finalCount = (manualCount !== null) 
            ? parseInt(manualCount) 
            : (grid ? grid.querySelectorAll('.listing-card-wrapper').length : 0);

        countSpan.textContent = finalCount;
        
        // Find the parent text container (e.g., "You have X listings")
        const parentP = countSpan.closest('p');
        
        // Target the navy/secondary icon box associated with listings
        // Assuming your layout follows the Quotations pattern
        const iconBox = document.querySelector('.bg-secondary-400');

        if (finalCount > 0) {
            if (parentP) parentP.classList.remove('hidden');
            if (iconBox) iconBox.classList.remove('hidden');
        } else {
            if (parentP) parentP.classList.add('hidden');
            if (iconBox) iconBox.classList.add('hidden');
        }
    }
};