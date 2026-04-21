// /resources/js/utils/notifications/mentors-actions.js

import { BaseNotificationActions as Base } from './notification-helpers.js';

export const MentorActions = {
    init(targetId, noteId) {
        const acceptBtn = document.getElementById('nt-accept-btn');
        const declineBtn = document.getElementById('nt-decline-btn');
        const container = document.querySelector('#nt-modal-body .p-8');
        
        // Store the original view so the "Back" button works
        const originalHtml = container.innerHTML;

        if (acceptBtn) {
            acceptBtn.onclick = () => Base.confirmStep(acceptBtn, 'Accept', () => {
                this.renderResponse(container, 'Accept', targetId, noteId, originalHtml);
            });
        }

        if (declineBtn) {
            // Remove the default close behavior to handle our custom logic
            declineBtn.classList.remove('close-notification-modal'); 
            declineBtn.onclick = () => Base.confirmStep(declineBtn, 'Decline', () => {
                this.renderResponse(container, 'Decline', targetId, noteId, originalHtml);
            });
        }
    },

    renderResponse(container, type, targetId, noteId, originalHtml) {
        Base.renderResponseArea(
            container, 
            `Confirming ${type}`, 
            // Back Action: Restore HTML and re-init listeners
            () => { 
                container.innerHTML = originalHtml; 
                this.init(targetId, noteId); 
            },
            // Submit Action
            () => Base.apiSubmit(
                'api/mentors-connect', 
                { action: type.toLowerCase(), target_id: targetId, notification_id: noteId },
                document.getElementById('nt-final-submit')
            )
        );
    }
};