// /resources/js/utils/quotations/view-quotation.js

import { ViewContentMapper } from './view-content-mapper.js';
import { viewMedia } from './view-media.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";
import { resetQuotationActionButton } from "./quotation-actions.js";
import { ViewCounter } from '../globals/view-counter.js';

export function initViewQuotation() {
    if (window._viewQuoteListenerAttached) return;

    ViewContentMapper.initMediaListeners();

    /**
     * INTERNAL HELPER: The Actual Opening Logic
     */
    const openQuoteModal = (data) => {
        const modal = document.getElementById('view-quotation-modal');
        if (!modal) return;

        // Find the action button and force a reset
        // Ensure this selector matches the ID or class on your Deactivate/Reactivate button in the modal
        const actionBtn = modal.querySelector('.deactivate-quotation-trigger, .reactivate-quotation-trigger');
        if (actionBtn) {
            resetQuotationActionButton(actionBtn);
        }

        /**
         * CONTEXT DETECTION
         * We check if we are coming from a notification to strip management rights.
         */
        const isFromNotification = data.fromNotification === 'true';
        const finalId = data.encodedId || data.id || data.targetId;
        const currentUserId = window.sessionUserId; 
        const quotationOwnerId = parseInt(data.ownerId);

        // This fires the increment logic globally. It will check sessionStorage to prevent duplicates and update the UI count if successful.
        ViewCounter.increment('quotation', finalId);
        
        /**
         * PERMISSION LOGIC:
         * User can manage if they are the owner AND not viewing via a notification.
         */
        let canManage = (currentUserId === quotationOwnerId) && !isFromNotification;

        // Map UI text/fields
        ViewContentMapper.mapAll(data);
        
        // Toggle management visibility (Edit/Delete buttons stay hidden if from notification)
        toggleManagementUI(canManage);
        
        // Owner Detail Logic
        modalDetailOwner('quote', data);
        
        // Set the ID on the modal for reference
        modal.dataset.quoteId = finalId; 

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

    // 2. MAIN TRIGGER: Standard Clicks
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-quote-trigger');
        if (!trigger || e.target.closest('.edit-quote-btn, .delete-quote-btn, .dropdown')) return;

        openQuoteModal(trigger.dataset);
    });

    // 3. SPECIAL TRIGGER: Force Open (For Notifications)
    document.addEventListener('force-open-quote', (e) => {
        if (e.detail && e.detail.dataset) {
            openQuoteModal(e.detail.dataset);
        }
    });

    ViewContentMapper.initUIBehaviors();
    window._viewQuoteListenerAttached = true;
}

/**
 * Toggle visibility of Edit/Delete/Upload buttons based on ownership/permissions
 */
function toggleManagementUI(canManage) {
    const adminControls = document.querySelectorAll('.quote-admin-only, .delete-photo-btn');
    
    adminControls.forEach(el => {
        if (canManage) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    });
}