// /resources/js/pages/my-listings-page.js

import { AnimationEngine } from '../utils/animations';
import { initListingsModal } from '../modals/listings-modal.js';
import { initViewListing } from '../utils/listings/view-listing.js';
import { initDeleteListing } from '../utils/listings/delete-listing.js';
import { initListingInfiniteScroll } from '../utils/listings/infinite-scroll-listings.js';
import { initListingSearch } from '../utils/listings/search-listings.js';
import { ListingCounter } from '../utils/listings/listing-counter-helper.js';

export function init() {  
    // 1. Initial Load
    refreshPageState();

    // 2. The "Live Update" Listener
    document.addEventListener('listing:updated', () => {
        refreshPageState();
    });

    // 3. Initialize Persistent Features
    initListingsModal();
    initListingInfiniteScroll();
    initListingSearch();

    // 4. HAND-OFF TRIGGER: Check if we need to open the "Add" modal automatically
    if (sessionStorage.getItem('trigger_add_listing_modal') === 'true') {
        sessionStorage.removeItem('trigger_add_listing_modal'); // Clean up immediately

        // Target the button that opens the 'Add' form modal
        const addBtn = document.getElementById('create-new-listing-btn'); 
        if (addBtn) {
            // A small delay ensures the SPA transition has finished 
            // and the DOM is fully painted before the modal pops.
            setTimeout(() => addBtn.click(), 100);
        }
    }
}

/**
 * Re-binds event listeners to cards. 
 * Run this on load and after any AJAX card update.
 */
function refreshPageState() {
    // Refresh animations for new cards
    AnimationEngine.refresh();

    // Re-bind View/Details listeners
    initViewListing();

    // Re-bind Delete buttons
    initDeleteListing();

    // Re-bind Edit buttons (Assuming delegation is handled in initListingsModal)
    
    // Sync the "X Total Listings" text or counter UI
    ListingCounter.update();
}