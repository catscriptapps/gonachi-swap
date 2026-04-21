// /resources/js/utils/social-feed/delete-post.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';

/**
 * Attaches delete functionality to the social feed via delegation.
 */
export function initDeletePost(containerSelector = '#social-feed-container') {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    // Initialize the factory for the Social Feed endpoint
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/social-feed`, 'Post');

    // Delegate click to delete buttons (usually hidden in a meatballs/dropdown menu)
    container.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-post-btn');
        if (!btn) return;

        e.preventDefault();

        // Posts are divs with data-post-id or data-encoded-id
        const postCard = btn.closest('[data-post-id]');
        const encodedId = postCard?.dataset.postId;

        if (!encodedId || !postCard) {
            console.error('Delete failed: Missing post ID or card element.');
            return;
        }

        // Trigger the factory's confirmation modal
        deleteHandler.showConfirmation(encodedId, postCard, (success) => {
            if (!success) return;

            // 1. Show orange-accented/high-contrast toast
            showToast('Post has been removed', 'success');

            // 2. Handle empty state if no posts are left
            const remainingPosts = container.querySelectorAll('[data-post-id]').length;
            if (remainingPosts === 0) {
                container.innerHTML = `
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-12 text-center border border-gray-200 dark:border-gray-800">
                        <div class="flex flex-col items-center">
                            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-full mb-4 text-primary-500">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <p class="font-bold text-lg text-gray-900 dark:text-white font-sans">Feed is empty</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Share an update to get the conversation started.</p>
                        </div>
                    </div>
                `;
            }
        });
    });
}