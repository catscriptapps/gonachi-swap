// /resources/js/utils/notifications/router.js

import { loadPartial } from '../spa-router.js';

export const NotificationRouter = {

    handlers: {
        'SYSTEM': () => import('./system.js'),
        'LISTING': () => import('./listings.js'), // Added Listing handler 🏷️
    },

    async handle(type, data) {
        const { noteId, element } = data;

        // 1. SILENT MARK AS READ (Universal) 🔔
        this.markAsRead(noteId, element);

        const normalizedType = type.toUpperCase();
        const getHandler = this.handlers[normalizedType];

        if (getHandler) {
            try {
                const module = await getHandler();

                // 2. Resolve handler dynamically 🧠
                // Checks for AdvertHandler, MentorHandler, SystemHandler, QuotationHandler, ListingHandler, or default
                const handler =
                    module.SystemHandler ||
                    module.ListingHandler ||
                    module.default;

                if (handler && typeof handler.process === 'function') {
                    await handler.process(data);
                    return;
                }
            } catch (e) {
                console.error("Router Error:", e);
            }
        }

        // 3. Fallback for simple link-based notifications
        this.fallback(element);
    },

    markAsRead(noteId, element) {
        if (!noteId || element.dataset.isRead === '1') return;

        const baseUrl = window.APP_CONFIG?.baseUrl || '/';

        fetch(`${baseUrl}api/notifications-read`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: noteId })
        })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    element.dataset.isRead = '1';

                    const card = element.closest('.group');
                    if (card) {
                        card.classList.remove('border-l-primary-500');
                        card.classList.add('border-l-transparent', 'opacity-75');
                    }

                    const headerCountSpan = document.querySelector('h3 span.text-primary-500');
                    if (headerCountSpan) {
                        this.decrementCount(headerCountSpan, true);
                    }

                    const type = element.dataset.type;
                    this.updateSidebarBadges(type);
                }
            })
            .catch(err => console.error('Router: MarkRead Error:', err));
    },

    decrementCount(element, isHeader = false) {
        let count = parseInt(element.innerText.replace(/[^0-9]/g, '')) || 0;
        if (count > 0) {
            count--;
            if (count === 0) {
                element.classList.add('hidden');
            } else {
                element.innerText = isHeader ? `( ${count} )` : count;
            }
        }
    },

    updateSidebarBadges(type) {
        const allAlertsBadge = document.querySelector('aside button:first-child span:last-child');
        if (allAlertsBadge) this.decrementCount(allAlertsBadge);

        const typeMap = {
            'LISTING': 'Listings',
            'SYSTEM': 'System'
        };

        const targetLabel = typeMap[type.toUpperCase()];
        if (targetLabel) {
            const sidebarButtons = document.querySelectorAll('aside button');
            sidebarButtons.forEach(btn => {
                // Matching based on the uppercase label text
                if (btn.innerText.includes(targetLabel.toUpperCase())) {
                    const badge = btn.querySelector('span:last-child');
                    if (badge) this.decrementCount(badge);
                }
            });
        }
    },

    fallback(element) {
        const url = element.getAttribute('href');
        if (url && url !== '#') {
            const cleanUrl = url.replace(window.APP_CONFIG.baseUrl, '');
            loadPartial(`${window.APP_CONFIG.baseUrl}${cleanUrl}`);
        }
    }
};