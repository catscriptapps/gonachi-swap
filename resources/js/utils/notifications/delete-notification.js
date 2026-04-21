// /resources/js/utils/notifications/delete-notification.js

// /resources/js/utils/notifications/delete-notification.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';
import { loadPartial } from '../spa-router.js'; // 💎 Import your partial loader

/**
 * Attaches delete functionality for Notifications via delegation.
 */
export function initDeleteNotification(containerSelector = '#notifications-page') {
    if (window._deleteNotificationListenerAttached) return;
    
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/notifications`, 'Notification');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="delete-notification"]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation(); 

        const id = btn.dataset.id;
        // We don't even need the 'item' element anymore because we're reloading the page
        const item = btn.closest('.notification-item') || btn.closest('.group');

        if (!id) return;

        deleteHandler.showConfirmation(id, item, (success) => {
            if (!success) return;

            showToast('Notification removed', 'success');

            // 💎 THE FIX: Simply reload the current partial view.
            // This pulls the fresh list (minus the deleted one) and updates counters.
            setTimeout(() => {
                const currentUrl = window.location.href;
                loadPartial(currentUrl);
            }, 300); 
        });
    });

    window._deleteNotificationListenerAttached = true;
}