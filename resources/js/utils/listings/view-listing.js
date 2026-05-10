// /resources/js/utils/listings/view-listing.js

import { ViewContentMapper } from './view-content-mapper.js';
import { viewMedia } from './view-media.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";
import { resetListingActionButton } from "./listing-actions.js";
import { ViewCounter } from '../globals/view-counter.js';

export function initViewListing() {
    if (window._viewListingListenerAttached) return;

    ViewContentMapper.initMediaListeners();

    /**
     * INTERNAL HELPER: The Actual Opening Logic
     */
    const openListingModal = (data) => {
        const modal = document.getElementById('view-listing-modal');
        if (!modal) return;

        // Find the action button and force a reset
        const actionBtn = modal.querySelector('.deactivate-listing-trigger, .reactivate-listing-trigger');
        if (actionBtn) {
            resetListingActionButton(actionBtn);
        }

        /**
         * CONTEXT DETECTION
         * We check if we are coming from a notification to strip management rights.
         */
        const isFromNotification = data.fromNotification === 'true';
        const finalId = data.encodedId || data.id || data.targetId;
        const currentUserId = window.sessionUserId;
        const listingOwnerId = parseInt(data.ownerId);

        // Increment view count globally
        ViewCounter.increment('listing', finalId);

        /**
         * PERMISSION LOGIC:
         * User can manage if they are the owner AND not viewing via a notification.
         */
        let canManage = (currentUserId === listingOwnerId) && !isFromNotification;

        // Map UI text/fields
        ViewContentMapper.mapAll(data);

        // Toggle management visibility (Edit/Delete buttons stay hidden if from notification)
        toggleManagementUI(canManage);

        // Owner Detail Logic
        modalDetailOwner('listing', data);

        // Set the ID on the modal for reference
        modal.dataset.listingId = finalId;

        // Ensure the notification modal is hidden to avoid layering
        const notificationModal = document.getElementById('notification-master-modal');
        if (notificationModal) notificationModal.classList.add('hidden');

        // 1. Reveal the modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // 2. Load media AFTER the modal is visible
        if (finalId) {
            setTimeout(() => {
                viewMedia(finalId, canManage);
            }, 50);
        }
    };

    // 2. MAIN TRIGGER: Standard Clicks (Images or "View Details" buttons)
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-listing-trigger') || e.target.closest('.connect-listing-trigger');
        if (!trigger || e.target.closest('.edit-listing-btn, .deactivate-listing-trigger, .reactivate-listing-trigger, .dropdown')) return;

        openListingModal(trigger.dataset);
    });

    // 3. SPECIAL TRIGGER: Force Open (For Notifications)
    document.addEventListener('force-open-listing', (e) => {
        if (e.detail && e.detail.dataset) {
            openListingModal(e.detail.dataset);
        }
    });

    ViewContentMapper.initUIBehaviors();
    window._viewListingListenerAttached = true;
}

function toggleManagementUI(canManage) {
    const ownerControls = document.querySelectorAll('.listing-owner-only, .delete-photo-btn');
    ownerControls.forEach(el => el.classList.toggle('hidden', !canManage));
}