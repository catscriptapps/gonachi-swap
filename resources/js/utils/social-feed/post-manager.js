// /resources/js/utils/social-feed/post-manager.js

import { showToast } from "../../ui/toast.js";

/**
 * PostManager Utility 🍊
 * Handles deletion and lifecycle of entire post cards
 * SILENT VERSION: No automatic empty state injection to prevent UI bugs.
 */

export const PostManager = {
    init() {
        this.bindEvents();
    },

    bindEvents() {
        document.addEventListener('click', (e) => {
            const menuBtn = e.target.closest('.post-options-btn');
            if (menuBtn) {
                this.toggleMenu(menuBtn);
                return;
            }

            const deleteBtn = e.target.closest('.delete-post-btn');
            if (deleteBtn) {
                this.handleDelete(deleteBtn);
                return;
            }

            if (!e.target.closest('.post-options-container')) {
                this.closeAllMenus();
            }
        });
    },

    toggleMenu(btn) {
        const menu = btn.nextElementSibling;
        const isOpen = !menu.classList.contains('hidden');
        this.closeAllMenus();
        if (!isOpen) {
            menu.classList.remove('hidden');
            menu.classList.add('animate-fade-in-up');
        }
    },

    closeAllMenus() {
        document.querySelectorAll('.post-options-menu').forEach(m => m.classList.add('hidden'));
    },

    async handleDelete(btn) {
        const postId = btn.dataset.postId;
        const postCard = btn.closest('.post-card') || document.querySelector(`[data-post-id="${postId}"]`);
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';

        if (btn.dataset.confirmed !== "true") {
            btn.dataset.confirmed = "true";
            const originalContent = btn.innerHTML;
            btn.dataset.originalContent = originalContent;

            btn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span class="font-bold">Click to Confirm</span>
            `;
            btn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
            btn.classList.remove('text-red-600', 'hover:bg-red-50');

            setTimeout(() => this.resetDeleteButton(btn), 4000);
            return;
        }

        try {
            const response = await fetch(`${baseUrl}api/social-feed`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete-post', post_id: postId })
            });

            const result = await response.json();

            if (result.success) {
                // 🍊 Just remove the card. No questions asked. No counting.
                postCard.style.transform = 'translateX(100px)';
                postCard.style.opacity = '0';
                postCard.style.transition = 'all 0.4s ease';
                
                setTimeout(() => {
                    postCard.remove();
                }, 400);

                showToast('Post deleted', 'success');
            } else {
                showToast(result.messages?.[0] || 'Error deleting post', 'error');
                this.resetDeleteButton(btn);
            }
        } catch (err) {
            console.error(err);
            showToast('Network error', 'error');
            this.resetDeleteButton(btn);
        }
    },

    resetDeleteButton(btn) {
        if (btn.dataset.originalContent) {
            btn.innerHTML = btn.dataset.originalContent;
            btn.dataset.confirmed = "false";
            btn.classList.remove('bg-red-600', 'text-white', 'hover:bg-red-700');
            btn.classList.add('text-red-600', 'hover:bg-red-50');
        }
    }
};