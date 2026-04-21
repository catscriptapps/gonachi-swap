// /resources/js/utils/social-feed/follow-actions.js

import { refreshFeed } from "./refresh-feed.js";
import { updateProfileStats } from "./profile-stats.js";

/**
 * Handle Follow/Unfollow button clicks in the sidebar via delegation
 */
export function initFollowActions() {
    // 🍊 Delegating to document or a common parent ensures we catch 
    // clicks from both Suggestions AND Search Results.
    document.addEventListener('click', async (e) => {
        // Look for the follow button (matches the class we'll add in PHP)
        const btn = e.target.closest('.follow-toggle-btn');
        if (!btn) return;

        // Use 'data-id' as per your Controller helper, or 'data-user-id'
        const userId = btn.getAttribute('data-id') || btn.getAttribute('data-user-id');
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';

        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerText = '...';

        try {
            const response = await fetch(`${baseUrl}api/social-relations`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ following_id: userId })
            });

            const result = await response.json();

            if (result.success) {
                const row = btn.closest('.group');
                if (row) {
                    row.classList.add('opacity-0', 'translate-x-4', 'transition-all', 'duration-300');
                    setTimeout(() => {
                        row.remove();
                        refreshFeed();
                        updateProfileStats(); 
                    }, 300);
                }
            } else {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        } catch (err) {
            console.error('Follow error:', err);
            btn.disabled = false;
            btn.innerText = originalText;
        }
    });
}