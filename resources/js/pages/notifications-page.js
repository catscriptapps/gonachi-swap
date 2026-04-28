// /resources/js/pages/notifications-page.js

import { AnimationEngine } from '../utils/animations';
import { NotificationRouter } from '../utils/notifications/router.js';
import { initDeleteNotification } from '../utils/notifications/delete-notification.js';
import { loadPartial } from '../utils/spa-router.js';

export function init() {
    window.loadPartial = loadPartial;
    AnimationEngine.refresh();

    // 💎 FIX: Ensure this matches the ID of your wrapper
    initDeleteNotification('#notifications-page'); 

    const container = document.getElementById('notifications-page');
    if (!container) return;

    container.addEventListener('click', (e) => {
        // --- 1. Handle Sidebar Filters (Targeting the aside buttons) ---
        const filterBtn = e.target.closest('aside button');
        if (filterBtn) {
            e.preventDefault();
            
            // Grab the text from the span (e.g., "All Alerts" or "Mentors")
            const label = filterBtn.querySelector('span:first-of-type')?.innerText.trim() || 'all';
            
            // Clean the label: "All Alerts" -> "all", "Listings" -> "listings"
            const filter = label.toLowerCase().split(' ')[0];
            const baseUrl = window.APP_CONFIG.baseUrl;
            const targetUrl = `${baseUrl}notifications?filter=${filter}`;

            loadPartial(targetUrl);
            return;
        }

        // --- 2. Handle Notification Actions (View Details) ---
        const actionBtn = e.target.closest('[data-action]');
        if (actionBtn) {
            const action = actionBtn.dataset.action;

            // 🛑 STOP: If the action is delete, let initDeleteNotification handle it.
            if (action === 'delete-notification') return;

            e.preventDefault();
            const noteId = actionBtn.dataset.id;
            const type = actionBtn.dataset.type; // This is what was undefined
            const targetId = actionBtn.dataset.targetId;

            // Only route if we have a type
            if (type) {
                NotificationRouter.handle(type, { noteId, targetId, element: actionBtn });
            }
        }
    });
}