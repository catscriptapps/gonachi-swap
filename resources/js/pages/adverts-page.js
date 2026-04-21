// /resources/js/pages/adverts-page.js

import { AnimationEngine } from '../utils/animations';
import { initAdvertsModal } from '../modals/adverts-modal.js';
import { initViewAdvert } from '../utils/adverts/view-advert.js';
import { initDeleteAdvert } from '../utils/adverts/delete-advert.js';
import { initAdInfiniteScroll } from '../utils/adverts/infinite-scroll-ads.js';
import { initAdSearch } from '../utils/adverts/search-ads.js';
import { AdCounter } from '../utils/adverts/ad-counter-helper.js';
import { initRegisterNewUser } from '../utils/home/register-new-user.js';

/**
 * Initialize the Adverts page events
 */
export function init() {
    AnimationEngine.refresh();
    initAdvertsModal();
    initViewAdvert();
    initDeleteAdvert();
    initAdInfiniteScroll();
    initAdSearch();
    AdCounter.update();
    
    initRegisterNewUser();
}