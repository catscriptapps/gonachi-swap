// /resources/js/pages/listings-page.js

import { AnimationEngine } from '../utils/animations';
import { loadPartial } from '../utils/spa-router.js';

import { initListingsModal } from '../modals/listings-modal.js';
import { initViewListing } from '../utils/listings/view-listing.js';
import { initDeleteListing } from '../utils/listings/delete-listing.js';
import { initListingActions } from '../utils/listings/listing-actions.js'; 
import { initListingInfiniteScroll } from '../utils/listings/infinite-scroll-listings.js';
import { initListingSearch } from '../utils/listings/search-listings.js';
import { ListingCounter } from '../utils/listings/listing-counter-helper.js';
import { initRegisterNewUser } from '../utils/home/register-new-user.js';
import { initListingsConnect } from '../modals/listings-connect-modal.js';

export function init() {
    AnimationEngine.refresh();
    initViewListing();
    initDeleteListing();
    initListingActions();
    ListingCounter.update();
    
    initListingsModal();
    initListingInfiniteScroll();
    initListingSearch();
        
    initRegisterNewUser();
    initListingsConnect();

    const addListingBtn = document.querySelector('#post-new-listing-btn');
    if (addListingBtn) {
        addListingBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // 1. Set the hand-off flag for the modal trigger
            sessionStorage.setItem('trigger_add_listing_modal', 'true');

            // 2. Navigate via SPA router to the management page
            const url = `${window.APP_CONFIG.baseUrl}my-listings`;
            loadPartial(url);
            
            // Update title for history/browser tab
            document.title = `My Listings | ${window.APP_CONFIG?.appName || 'Gonachi'}`;
        });
    }
}