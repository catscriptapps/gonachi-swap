// /resources/js/utils/adverts/admin-actions.js

import { confirmDialog } from '../../ui/confirm.js';

/**
 * Generic handler for Admin Status updates
 */
export function initAdminActions() {
    // Prevent multiple listeners during loadPartial navigation
    if (window._adminActionsAttached) return;

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('#admin-approve-ad-btn, #admin-deactivate-ad-btn, #admin-reject-ad-btn');
        if (!btn) return;

        const modal = document.getElementById('view-ad-modal');
        const adId = modal?.dataset.adId; // This might be "Ng"
        if (!adId) return;

        let newStatus = 'active';
        let actionLabel = 'Approve';
        let btnClass = 'bg-green-600 hover:bg-green-700';

        if (btn.id.includes('deactivate')) {
            newStatus = 'inactive';
            actionLabel = 'Deactivate';
            btnClass = 'bg-orange-600 hover:bg-orange-700';
        }

        if (btn.id.includes('reject')) {
            newStatus = 'rejected';
            actionLabel = 'Reject';
            btnClass = 'bg-red-600 hover:bg-red-700';
        }

        const message = `Are you sure you want to ${actionLabel.toLowerCase()} this advert?`;
        const confirmed = await confirmDialog(message, actionLabel, "Cancel", btnClass);

        if (!confirmed) return;

        try {
            const response = await fetch(`${window.APP_CONFIG.baseUrl}api/advert-status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    id: adId,
                    status: newStatus 
                })
            });

            const result = await response.json();

            if (result.success && result.rowHtml) {
                // We use result.id (the numeric 6) to match your PHP <tr id="ad-row-6">
                const numericId = result.id; 
                const existingRow = document.getElementById(`ad-row-${numericId}`);
                
                if (existingRow) {
                    // Replace the entire row with the fresh HTML from the server
                    existingRow.outerHTML = result.rowHtml;
                }

                // Close the view modal
                const closeBtn = document.querySelector('.close-view-ad-modal');
                if (closeBtn) {
                    closeBtn.click();
                } else {
                    // Fallback close logic
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        } catch (error) {
            console.error('Failed to update advert status:', error);
        }
    });

    window._adminActionsAttached = true;
}