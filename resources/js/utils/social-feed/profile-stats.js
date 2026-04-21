// /resources/js/utils/social-feed/profile-stats.js

/**
 * Fetches and updates the profile stats (following/followers counts)
 */
export async function updateProfileStats() {
    const followingEl = document.querySelector('#following-count');
    const followersEl = document.querySelector('#followers-count');
    if (!followingEl || !followersEl) return;

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';

    try {
        const response = await fetch(`${baseUrl}api/social-relations?action=get-stats`);
        const result = await response.json();

        if (result.success) {
            followingEl.innerText = result.following;
            followersEl.innerText = result.followers;
        }
    } catch (err) {
        console.warn('Stats fetch failed', err);
    }
}