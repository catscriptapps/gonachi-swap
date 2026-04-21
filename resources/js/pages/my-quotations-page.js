// /resources/js/pages/my-quotations-page.js

import { AnimationEngine } from '../utils/animations';
import { initQuotationsModal } from '../modals/quotations-modal.js';
import { initViewQuotation } from '../utils/quotations/view-quotation.js';
import { initDeleteQuotation } from '../utils/quotations/delete-quotation.js';
import { initQuoteInfiniteScroll } from '../utils/quotations/infinite-scroll-quotes.js';
import { initQuoteSearch } from '../utils/quotations/search-quotes.js';
import { QuoteCounter } from '../utils/quotations/quote-counter-helper.js';

export function init() {  
    // 1. Initial Load
    refreshPageState();

    // 2. The "Live Update" Listener
    document.addEventListener('quotation:updated', () => {
        refreshPageState();
    });

    // 3. Initialize Persistent Features
    initQuotationsModal();
    initQuoteInfiniteScroll();
    initQuoteSearch();

    // 4. HAND-OFF TRIGGER: Check if we need to open the "Add" modal automatically
    if (sessionStorage.getItem('trigger_add_quote_modal') === 'true') {
        sessionStorage.removeItem('trigger_add_quote_modal'); // Clean up immediately

        // Target the button that opens the 'Add' form modal
        const addBtn = document.getElementById('create-new-quote-btn'); 
        if (addBtn) {
            // A small delay (100ms) ensures the SPA transition has finished 
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
    initViewQuotation();

    // Re-bind Delete buttons
    initDeleteQuotation();

    // Re-bind Edit buttons (if your initQuotationsModal handles them)
    // Note: If initQuotationsModal() uses delegation, it only needs to run once in init()
    
    // Sync the "X Quotation Requests" text
    QuoteCounter.update();
}