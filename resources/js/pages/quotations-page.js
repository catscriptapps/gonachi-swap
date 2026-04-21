// /resources/js/pages/quotations-page.js

import { AnimationEngine } from '../utils/animations';
import { loadPartial } from '../utils/spa-router.js';

import { initQuotationsModal } from '../modals/quotations-modal.js';
import { initViewQuotation } from '../utils/quotations/view-quotation.js';
import { initDeleteQuotation } from '../utils/quotations/delete-quotation.js';
import { initQuotationActions } from '../utils/quotations/quotation-actions.js'; 
import { initQuoteInfiniteScroll } from '../utils/quotations/infinite-scroll-quotes.js';
import { initQuoteSearch } from '../utils/quotations/search-quotes.js';
import { QuoteCounter } from '../utils/quotations/quote-counter-helper.js';
import { initRegisterNewUser } from '../utils/home/register-new-user.js';
import { initQuotationsConnect } from '../modals/quotations-connect-modal.js';

export function init() {
    AnimationEngine.refresh();
    
    initViewQuotation();
    initDeleteQuotation();
    initQuotationActions();
    QuoteCounter.update();
    
    initQuotationsModal();
    initQuoteInfiniteScroll();
    initQuoteSearch();
        
    initRegisterNewUser();
    initQuotationsConnect();

    const requestBtn = document.querySelector('#request-quotation-btn');
    if (requestBtn) {
        requestBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            // 1. Set the hand-off flag
            sessionStorage.setItem('trigger_add_quote_modal', 'true');

            // 2. Navigate via SPA router
            const url = `${window.APP_CONFIG.baseUrl}my-quotations`;
            loadPartial(url);
            
            // Update title for history/tab
            document.title = `My Quotations | ${window.APP_CONFIG?.appName || 'Gonachi'}`;
        });
    }
}