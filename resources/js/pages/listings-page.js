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

/**
 * Gonachi Swap - Listings Page Orchestrator
 * This file initializes all UI components for the main marketplace view.
 */
export function init() {
    // 1. Refresh animations for the new content
    AnimationEngine.refresh();

    // 2. Initialize Core Listing Interactivity
    initViewListing();      // Handles clicking cards to see details
    initDeleteListing();    // Handles removal if user is viewing their own card
    initListingActions();   // Handles "Swap Request" or "Interested" buttons

    // 3. Update UI Helpers
    ListingCounter.update(); // Updates the total count in the sidebar/header

    // 4. Initialize Data Fetching & Modals
    initListingsModal();            // General listing-related modals
    initListingInfiniteScroll();    // Handles pagination via scrolling
    initListingSearch();           // Handles the sidebar checkboxes and search input

    // 5. Shared Community Components
    initRegisterNewUser();          // Handles registration from guest views
    initListingsConnect();         // Handles the contact/message modal logic

    // 6. Handle "Post New Listing" Hand-off
    const addListingBtn = document.querySelector('#post-new-listing-btn');
    if (addListingBtn) {
        addListingBtn.addEventListener('click', (e) => {
            e.preventDefault();

            // 1. Set the hand-off flag for the modal trigger in the management page
            sessionStorage.setItem('trigger_add_listing_modal', 'true');

            // 2. Navigate via SPA router to the management page
            const url = `${window.APP_CONFIG.baseUrl}my-listings`;
            loadPartial(url);

            // 3. Update title for history/browser tab
            const appTitle = window.APP_CONFIG?.appName || 'Gonachi Swap';
            document.title = `My Listings | ${appTitle}`;
        });
    }
}