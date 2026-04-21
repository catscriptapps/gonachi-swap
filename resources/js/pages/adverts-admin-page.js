// /resources/js/pages/adverts-admin-page.js

/**
 * Adverts Admin Page JS (Review & Audit Only)
 * /resources/js/pages/adverts-admin-page.js
 */

import { updateCount } from '../components/table-pagination-count.js';
import { enableTableSearch } from '../components/table-search.js';
import { initViewAdvert  } from '../utils/adverts/view-advert.js';
import { initAdminActions } from '../utils/adverts/admin-actions.js';
import { initAdminTabs } from '../utils/adverts/admin-tabs.js';
import { initAdAdminInfiniteScroll } from '../utils/adverts/infinite-scroll-ads-admin.js';

/**
 * Initialize the Adverts Admin page JS.
 */
export function init() {
    // 1. Enable the pro AJAX search
    // Using 'adverts-admin-search' to avoid collision with potential user-side searches
    enableTableSearch({
        searchInputId: 'adverts-admin-search',
        tbodyId: 'adverts-tbody',
        countId: 'adverts-count',
        endpoint: `${window.APP_CONFIG?.baseUrl}api/adverts`,
        resourceLabel: 'advert',
        addButtonId: 'add-advert-btn' 
    });

    // 2. Initial count check
    updateCount('advert', '#adverts-tbody', '#adverts-count');
    
    // 3. Initialize the detailed view/profile modal (The "Audit" view)
    initViewAdvert();

    // 4. Initialize the Status Update Action Handlers (Approve/Reject/Deactivate)
    initAdminActions();

    // 5. Initialize Admin Tabs
    initAdminTabs(); 

    // 6. Initialize Infinite Scroll for the admin audit trail
    initAdAdminInfiniteScroll(); 
}