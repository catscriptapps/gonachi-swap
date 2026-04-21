// /resources/js/utils/adverts/ad-media-actions.js

import { openMediaUpload } from '../media-manager.js';

export function initAdMediaActions() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('#trigger-ad-pic-upload');
        if (!btn) return;

        e.preventDefault();
        
        // Grab the ID we injected into the modal dataset earlier
        const modal = document.getElementById('view-ad-modal');
        const adId = modal ? modal.dataset.adId : null;

        if (!adId) {
            console.error('Gonachi Error: Ad ID missing from modal dataset.');
            return;
        }

        // Trigger the factory!
        openMediaUpload({
            type: 'advert', // Matches new 'api/advert-pictures' naming
            id: adId,
            gridId: '#ad-pics-wrapper'
        });
    });
}