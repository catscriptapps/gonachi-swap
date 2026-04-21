// /resources/js/utils/adverts/view-advert.js

import { ViewContentMapper } from './view-content-mapper.js';
import { viewMedia } from './view-media.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";
import { ViewCounter } from '../globals/view-counter.js';

/**
 * Initialize View Advert logic. 
 * Detects Admin context dynamically and handles event-based opening.
 */
export function initViewAdvert() {
    // 1. Singleton Check
    if (window._viewAdListenerAttached) return;

    // 2. Initialize behaviors
    ViewContentMapper.initMediaListeners();
    ViewContentMapper.initUIBehaviors();
    ViewContentMapper.initAdminUIBehaviors(); 

    /**
     * INTERNAL HELPER: The Actual Opening Logic 📢
     */
    const openAdModal = (data) => {
        const modal = document.getElementById('view-ad-modal');
        if (!modal) return;

        // Context detection
        const isFromNotification = data.fromNotification === 'true';
        const isAdminPage = !!document.getElementById('adverts-administration');
        const isSocialFeedAd = !!data.isSocialAd; // You can pass this in the trigger dataset

        const currentUserId = window.sessionUserId; 
        const advertOwnerId = parseInt(data.ownerId);
        
        /**
         * PERMISSION LOGIC:
         * 1. If it's a social feed card or from notification, canManage is false.
         * 2. Otherwise, user can manage if they are the owner.
         */
        let canManage = (currentUserId === advertOwnerId) && !isFromNotification;
        
        if (isSocialFeedAd) {
            canManage = false;
        }

        // Map content using the detected mode
        ViewContentMapper.mapAll(data, isAdminPage);
        
        // Toggle management visibility (Edit buttons, Uploaders, etc.)
        toggleManagementUI(canManage);

        // Load owner details UI component
        modalDetailOwner('ad', data);
        
        const adId = data.encodedId || data.id;
        modal.dataset.adId = adId; 

        // This fires the increment logic globally. It will check sessionStorage to prevent duplicates and update the UI count if successful.
        ViewCounter.increment('ad', adId);
        
        // Ensure the notification modal is hidden to avoid layering
        const notificationModal = document.getElementById('notification-master-modal');
        if (notificationModal) notificationModal.classList.add('hidden');

        // Reveal the modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 

        // Load media AFTER the modal is visible
        if (adId) {
            setTimeout(() => {
                viewMedia(adId, canManage);
            }, 50);
        }
    };

    // 3. MAIN TRIGGER: Standard Clicks
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-ad-trigger');
        
        // Safety: Ignore clicks on sub-buttons within the row
        if (!trigger || e.target.closest('.edit-ad-btn, .delete-ad-btn, .dropdown')) return;

        openAdModal(trigger.dataset);
    });

    // 4. SPECIAL TRIGGER: Force Open (For Notifications) 💎
    document.addEventListener('force-open-advert', (e) => {
        if (e.detail && e.detail.dataset) {
            openAdModal(e.detail.dataset);
        }
    });

    window._viewAdListenerAttached = true;
}

/**
 * Toggle visibility of Edit/Delete/Upload buttons based on ownership/permissions
 */
function toggleManagementUI(canManage) {
    const adminControls = document.querySelectorAll('.ad-admin-only, #view-ad-edit-btn, #trigger-ad-pic-upload, .delete-photo-btn');
    
    adminControls.forEach(el => {
        if (canManage) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    });
}