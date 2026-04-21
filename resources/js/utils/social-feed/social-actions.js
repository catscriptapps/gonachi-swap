// /resources/js/utils/social-feed/social-actions.js

import { showToast } from '../../ui/toast.js';
import { handleSharePost } from './social-extras.js';
import { updateCardCounters } from './update-card-counters.js';

/**
 * Handles Like toggles and Comment submissions
 */
export function initSocialActions() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';

    document.addEventListener('click', async (e) => {
        const likeBtn = e.target.closest('.like-toggle-btn');
        if (likeBtn) {
            e.preventDefault();
            handleLikeToggle(likeBtn, baseUrl);
            return;
        }

        const submitCommentBtn = e.target.closest('#submit-comment-btn');
        if (submitCommentBtn) {
            e.preventDefault();
            handleCommentSubmit(submitCommentBtn, baseUrl);
            return;
        }

        const deleteBtn = e.target.closest('.delete-comment-btn');
        if (deleteBtn) {
            e.preventDefault();
            handleCommentDelete(deleteBtn, baseUrl);
            return;
        }

        // 🍊 THE FIX: Use e.target instead of target
        const shareBtn = e.target.closest('.share-post-btn'); 
        if (shareBtn) {
            e.preventDefault();
            handleSharePost(shareBtn);
            return;
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.target && e.target.id === 'post-comment-input') {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault(); 
                const submitBtn = document.getElementById('submit-comment-btn');
                if (submitBtn && !submitBtn.disabled) {
                    handleCommentSubmit(submitBtn, baseUrl);
                }
            }
        }
    });
}

async function handleLikeToggle(btn, baseUrl) {
    const postId = btn.dataset.id;
    const icon = btn.querySelector('svg');
    const countSpan = btn.querySelector('.like-count');
    const isLiked = btn.classList.contains('text-primary-600');
    let currentCount = parseInt(countSpan.textContent) || 0;

    btn.classList.toggle('text-primary-600', !isLiked);
    btn.classList.toggle('text-gray-500', isLiked);
    if (icon) icon.classList.toggle('fill-current', !isLiked);
    countSpan.textContent = isLiked ? Math.max(0, currentCount - 1) : currentCount + 1;

    try {
        const response = await fetch(`${baseUrl}api/social-feed`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'toggle-like', post_id: postId })
        });
        const result = await response.json();
        if (result.like_count !== undefined) countSpan.textContent = result.like_count;
    } catch (err) {
        showToast('Could not update like', 'error');
        btn.classList.toggle('text-primary-600', isLiked);
        countSpan.textContent = currentCount;
    }
}

/** Submits a new comment and appends it to the list 🍊
 */
async function handleCommentSubmit(btn, baseUrl) {
    const input = document.getElementById('post-comment-input');
    const commentList = document.getElementById('view-post-comments-list');
    const content = input.value.trim();
    const modal = document.getElementById('view-post-modal');
    const postId = modal ? modal.dataset.activePostId : null;

    if (!content || !postId) return;

    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<div class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';

    try {
        const response = await fetch(`${baseUrl}api/social-feed`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add-comment', post_id: postId, content: content })
        });
        const result = await response.json();

        if (result.success) {
            input.value = '';
            input.style.height = 'auto'; 
            
            if (document.getElementById('no-comments-msg')) {
                document.getElementById('no-comments-msg').remove();
            }

            if (result.commentHtml) {
                // 🍊 Chronological Fix: Append to the BOTTOM instead of the top
                commentList.insertAdjacentHTML('beforeend', result.commentHtml);

                // 🍊 Smooth Scroll: Ensure the user sees their new comment
                const newComment = commentList.lastElementChild;
                if (newComment) {
                    newComment.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    // Add a little visual flair (using your primary orange)
                    newComment.classList.add('ring-2', 'ring-primary-500', 'ring-opacity-50', 'rounded-lg');
                    setTimeout(() => {
                        newComment.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
                    }, 2000);
                }
            }

            // Update counters using your existing shared logic
            updateCardCounters(postId, 1);

            showToast('Comment added!', 'success');
        }
    } catch (err) {
        showToast('Network error', 'error');
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
}

/**
 * Deletes a comment with a non-intrusive "double-check" state 🍊
 */
async function handleCommentDelete(btn, baseUrl) {
    // 1. If we are already in the "Confirm" state, proceed with delete
    if (btn.dataset.confirmed === "true") {
        if (btn.dataset.processing) return;

        const commentId = btn.dataset.commentId;
        const commentRow = btn.closest('.comment-row');
        const modal = document.getElementById('view-post-modal');
        const postId = modal ? modal.dataset.activePostId : null;

        if (!commentId || !commentRow) return;

        btn.dataset.processing = "true";
        commentRow.style.opacity = '0.4';
        commentRow.classList.add('pointer-events-none');

        try {
            const response = await fetch(`${baseUrl}api/social-feed`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete-comment', comment_id: commentId })
            });
            const result = await response.json();

            if (result.success) {
                commentRow.remove();
                if (postId) updateCardCounters(postId, -1);
                showToast('Comment removed', 'success');
            } else {
                throw new Error();
            }
        } catch (err) {
            commentRow.style.opacity = '1';
            commentRow.classList.remove('pointer-events-none');
            resetDeleteBtn(btn);
            showToast('Could not delete', 'error');
        }
        return;
    }

    // 2. First click: Enter "Confirm" state
    enterConfirmState(btn);
}

/**
 * Changes button UI to ask for confirmation
 */
function enterConfirmState(btn) {
    const originalContent = btn.innerHTML;
    btn.dataset.originalHtml = originalContent;
    btn.dataset.confirmed = "true";

    // Change appearance: Red text and a "check" or "confirm" label
    btn.classList.remove('text-gray-400');
    btn.classList.add('text-red-500', 'flex', 'items-center', 'gap-1');
    btn.innerHTML = `
        <span class="text-[10px] font-bold uppercase tracking-tighter">Confirm?</span>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    `;

    // Auto-reset after 3 seconds if they don't click again
    btn._confirmTimeout = setTimeout(() => {
        resetDeleteBtn(btn);
    }, 3000);

    // Also reset if the mouse leaves the button area
    btn.addEventListener('mouseleave', () => {
        resetDeleteBtn(btn);
    }, { once: true });
}

/**
 * Reverts button to its original trash icon state
 */
function resetDeleteBtn(btn) {
    if (btn._confirmTimeout) clearTimeout(btn._confirmTimeout);
    
    btn.dataset.confirmed = "false";
    btn.classList.add('text-gray-400');
    btn.classList.remove('text-red-500', 'flex', 'items-center', 'gap-1');
    if (btn.dataset.originalHtml) {
        btn.innerHTML = btn.dataset.originalHtml;
    }
    delete btn.dataset.processing;
}