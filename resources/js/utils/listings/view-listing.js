// /resources/js/utils/listings/view-listing.js

import { ViewContentMapper } from './view-content-mapper.js';
import { viewMedia } from './view-media.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";
import { resetListingActionButton } from "./listing-actions.js";
import { ViewCounter } from '../globals/view-counter.js';

export function initViewListing() {
    if (window._viewListingListenerAttached) return;

    ViewContentMapper.initMediaListeners();

    const openListingModal = (data) => {
        const modal = document.getElementById('view-listing-modal');
        if (!modal) return;

        // Find the action button and force a reset
        // Ensure this selector matches the ID or class on your Deactivate/Reactivate button in the modal
        const actionBtn = modal.querySelector('.deactivate-listing-trigger, .reactivate-listing-trigger');
        if (actionBtn) {
            resetListingActionButton(actionBtn);
        }

        // 1. Identify context
        const isFromNotification = data.fromNotification === 'true'; // 💎 Detection
        const finalId = data.encodedId || data.id || data.targetId;
        const currentUserId = window.sessionUserId; 
        const listingOwnerId = parseInt(data.ownerId);

        // This fires the increment logic globally. It will check sessionStorage to prevent duplicates and update the UI count if successful.
        ViewCounter.increment('listing', finalId);
        
        // 2. Determine Permission
        // Even if they ARE the owner, if they came from a notification, we'll strip the edit rights
        let canManage = (currentUserId === listingOwnerId) && !isFromNotification;

        // 3. Map UI
        ViewContentMapper.mapAll(data);
        
        // 4. Toggle UI 
        // This will now hide the Edit button because canManage will be false
        toggleManagementUI(canManage);
        
        modalDetailOwner('listing', data);
        modal.dataset.listingId = finalId; 

        // 5. Reveal
        const notificationModal = document.getElementById('notification-master-modal');
        if (notificationModal) notificationModal.classList.add('hidden');
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 

        if (finalId) {
            setTimeout(() => { viewMedia(finalId, canManage); }, 50);
        }
    };

    // 2. MAIN TRIGGER: Standard Click
    document.addEventListener('click', (e) => {
        // 💎 THE ULTIMATE SHIELD
        const editBtn = e.target.closest('.edit-listing-btn, .edit-quote-btn');
        const viewTrigger = e.target.closest('.view-listing-trigger');

        // 1. If it's an EDIT button:
        if (editBtn) {
            // Stop it from hitting the notification listener 🛑
            e.stopPropagation(); 
            
            // Manually trigger the edit modal opening
            // We dispatch a new event that the EditHandler is listening for,
            // but one that isn't wrapped in the "notification" bubble.
            const editEvent = new CustomEvent('trigger-edit-listing', { 
                detail: { dataset: editBtn.dataset } 
            });
            document.dispatchEvent(editEvent);
            return;
        }

        // 2. If it's the VIEW trigger:
        if (viewTrigger) {
            // Ignore if we are clicking an internal button
            if (e.target.closest('.edit-listing-btn, .delete-listing-btn, .dropdown')) return;
            
            e.preventDefault();
            e.stopPropagation();
            openListingModal(viewTrigger.dataset);
        }
    });

    // 3. SPECIAL TRIGGER: Force Open (Notifications)
    document.addEventListener('force-open-listing', (e) => {
        if (e.detail && e.detail.dataset) {
            openListingModal(e.detail.dataset);
        }
    });

    ViewContentMapper.initUIBehaviors();
    window._viewListingListenerAttached = true;
}

function toggleManagementUI(canManage) {
    const adminControls = document.querySelectorAll('.listing-admin-only, .delete-photo-btn');
    adminControls.forEach(el => {
        if (canManage) { el.classList.remove('hidden'); } 
        else { el.classList.add('hidden'); }
    });
}