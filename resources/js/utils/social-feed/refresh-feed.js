// /resources/js/utils/social-feed/refresh-feed.js

/**
 * Refreshes the social feed by fetching the latest posts from the server
 */
export async function refreshFeed() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const feedContainer = document.querySelector('#social-feed-container'); // Ensure this ID exists on your feed div
    
    if (!feedContainer) return;

    // Optional: show a small loading state
    feedContainer.style.opacity = '0.5';

    const response = await fetch(`${baseUrl}api/social-feed`); // Hits your index() logic
    const result = await response.json();

    if (result.success) {
        feedContainer.innerHTML = result.html;
        feedContainer.style.opacity = '1';
    }
}