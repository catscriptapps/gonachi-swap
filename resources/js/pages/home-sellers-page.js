// /resources/js/pages/home-sellers-page.js
import { AnimationEngine } from '../utils/animations';

/**
 * Initialize Home Sellers Portal
 */
export function init() {
    AnimationEngine.refresh();

    const addListingBtn = document.querySelector('.bg-primary-600');
    const findSellersBtn = document.querySelector('.bg-secondary-900');

    if (addListingBtn) {
        addListingBtn.addEventListener('click', () => {
            console.log('Opening Listing Detail Form for prospective sellers...');
            // Step-by-step form for sellers to provide property info
        });
    }

    if (findSellersBtn) {
        findSellersBtn.addEventListener('click', () => {
            console.log('Filtering prospective sellers for verified Realtors...');
        });
    }
}