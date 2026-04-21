// /resources/js/utils/social-feed/view-post.js

import { initScrollToLatest } from "./scroll-to-latest.js";

/**
 * Handles opening and populating the detailed view for a social post.
 */
export function initViewPost() {
    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('.post-media-trigger') || 
                        e.target.closest('.view-comments-btn') || 
                        e.target.closest('.post-content-trigger');
        
        if (!trigger) return;

        const postCard = trigger.closest('[data-post-id]');
        if (!postCard) return;

        const modal = document.getElementById('view-post-modal');
        if (!modal) return;

        // 1. Data Extraction
        const postId = postCard.dataset.postId;
        const author = postCard.querySelector('h3').textContent.trim();
        const time = postCard.querySelector('.text-xs').textContent.trim();
        const content = postCard.querySelector('p').innerHTML;
        
        // Target the post media and user avatar
        const media = postCard.querySelector('.post-main-media');
        const userAvatar = postCard.querySelector('.user-avatar-img');

        // 🍊 THE FIX: Bind the active Post ID to the modal dataset
        // This prevents comments from going to the wrong post!
        modal.dataset.activePostId = postId;

        // 2. Populate Headers
        document.getElementById('view-post-author').textContent = author;
        document.getElementById('view-post-time').textContent = time;
        document.getElementById('view-post-content').innerHTML = content;
        
        // Handle Avatar in Modal Header
        const avatarContainer = document.getElementById('view-post-avatar');
        avatarContainer.innerHTML = ''; // Clear existing
        if (userAvatar) {
            const clonedAvatar = userAvatar.cloneNode(true);
            clonedAvatar.className = "h-10 w-10 rounded-full object-cover border border-gray-100 dark:border-gray-800 shadow-sm";
            avatarContainer.appendChild(clonedAvatar);
        } else {
            avatarContainer.textContent = author.substring(0, 1).toUpperCase();
            avatarContainer.className = "h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-lg shadow-sm";
        }

        // 3. Handle Media Injection
        const mediaContainer = document.getElementById('view-post-media-container');
        mediaContainer.innerHTML = ''; 
        
        if (media) {
            mediaContainer.classList.remove('hidden');
            const clonedMedia = media.cloneNode(true);
            // Remove trigger classes so we don't nest modals
            clonedMedia.classList.remove('post-media-trigger', 'cursor-pointer', 'hover:opacity-95');
            clonedMedia.className = "max-w-full max-h-[70vh] object-contain";
            mediaContainer.appendChild(clonedMedia);
        } else {
            mediaContainer.classList.add('hidden');
        }

        // 4. Comments UI Reset (Show Spinner)
        const commentList = document.getElementById('view-post-comments-list');
        commentList.innerHTML = '<div class="flex justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div></div>';
        
        // Reset comment input field
        const commentInput = document.getElementById('post-comment-input');
        if (commentInput) commentInput.value = '';

        modal.classList.remove('hidden');

        // 5. API Fetch for Comments and Stats
        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/social-feed?action=get-details&post_id=${postId}`);
            const result = await response.json();

            if (result.success) {
                // Update Comments List
                commentList.innerHTML = result.html;

                // 🍊 Initialize the "Jump to Latest" button 
                // This ensures the button is ready as soon as the comments load
                initScrollToLatest(commentList);
                
                // Update Footer Stats
                const likesCount = document.getElementById('modal-likes-count');
                const commentsCount = document.getElementById('modal-comments-count');
                
                if (likesCount) likesCount.textContent = result.likes || 0;
                if (commentsCount) commentsCount.textContent = result.comments_count || 0;
                
                // Final Check: ensure spinner is gone (handled by .innerHTML replacement)
            } else {
                throw new Error(result.messages?.[0] || 'Failed to load details');
            }
        } catch (err) {
            console.error("Modal Fetch Error:", err);
            commentList.innerHTML = '<p class="text-center text-red-500 text-xs py-4">Failed to load comments.</p>';
        }
    });

    const closeModal = () => {
        const modal = document.getElementById('view-post-modal');
        if (modal) {
            modal.classList.add('hidden');
            // Clean up ID so we don't accidentally comment on a closed post
            delete modal.dataset.activePostId;
        }
    };

    // Close Listeners
    document.addEventListener('click', (e) => {
        if (e.target.closest('.close-post-modal') || e.target.id === 'close-post-modal-overlay') {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
}