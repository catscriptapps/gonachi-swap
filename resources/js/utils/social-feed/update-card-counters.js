// /resources/js/utils/social-feed/update-card-counters.js

/**
 * Shared logic to update Modal and Background card counts 🍊
 * @param {string|number} postId - The ID of the post
 * @param {number} change - +1 or -1
 */
export function updateCardCounters(postId, change) {
    console.log(`Updating counters for Post ${postId} by ${change}`);

    // 1. Update the Modal Counter (Top of the comment list)
    const modalCount = document.getElementById('modal-comments-count');
    if (modalCount) {
        const currentModalVal = parseInt(modalCount.textContent) || 0;
        modalCount.textContent = Math.max(0, currentModalVal + change);
    }

    // 2. Update the Feed Card Counter (The one on the main wall)
    // We search for the post card by either data-post-id or data-id
    const feedCard = document.querySelector(`.post-card[data-post-id="${postId}"]`) || 
                     document.querySelector(`.post-card[data-id="${postId}"]`) ||
                     document.querySelector(`[data-post-id="${postId}"]`);

    if (feedCard) {
        // Try to find the specific span that holds the number
        const cardLabel = feedCard.querySelector('.comment-count-label') || 
                          feedCard.querySelector('.view-comments-btn span');

        if (cardLabel) {
            const currentCardVal = parseInt(cardLabel.textContent) || 0;
            const newVal = Math.max(0, currentCardVal + change);
            cardLabel.textContent = newVal;

            // Highlight the count if it's active
            if (newVal > 0) {
                cardLabel.classList.add('text-primary-600', 'font-bold');
            } else {
                cardLabel.classList.remove('text-primary-600', 'font-bold');
            }
        }
    }
}