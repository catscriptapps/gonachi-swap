// /resources/js/utils/notifications/quotations-actions.js

import { BaseNotificationActions as Base } from './notification-helpers.js';

export const QuotationActions = {
    init(targetId, noteId) {
        const acceptBtn = document.getElementById('nt-accept-btn');
        const declineBtn = document.getElementById('nt-decline-btn');
        const container = document.querySelector('#nt-modal-body .p-8');
        
        if (!container) return;
        const originalHtml = container.innerHTML;

        if (acceptBtn) {
            acceptBtn.onclick = () => Base.confirmStep(acceptBtn, 'Accept', () => {
                this.renderResponse(container, 'Accept', targetId, noteId, originalHtml);
            });
        }

        if (declineBtn) {
            declineBtn.onclick = () => Base.confirmStep(declineBtn, 'Decline', () => {
                this.renderResponse(container, 'Decline', targetId, noteId, originalHtml);
            });
        }
    },

    renderResponse(container, type, targetId, noteId, originalHtml) {
        Base.renderResponseArea(
            container, 
            `Confirming ${type}`, 
            () => { 
                container.innerHTML = originalHtml; 
                this.init(targetId, noteId); 
            },
            () => Base.apiSubmit(
                'api/quotations-connect',
                { action: type.toLowerCase(), target_id: targetId, notification_id: noteId },
                document.getElementById('nt-final-submit')
            )
        );
    }
};