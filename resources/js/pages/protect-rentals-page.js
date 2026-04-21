// /resources/js/pages/protect-rentals-page.js

import { AnimationEngine } from '../utils/animations';

/**
 * Initialize Protect Rentals Intelligence Hub
 */
export function init() {
    AnimationEngine.refresh();

    const addPropertyBtn = document.querySelector('.bg-primary-600');
    const searchBtn = document.querySelector('.bg-secondary-900');

    if (addPropertyBtn) {
        addPropertyBtn.addEventListener('click', () => {
            console.log('Opening Add Property Wizard...');
            // Logic to link property to landlord account
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            console.log('Opening Rental Experience Search...');
            // Logic to search properties/tenants by reference
        });
    }
}