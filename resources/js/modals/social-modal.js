// /resources/js/modals/social-modal.js

import { Modal } from '../factories/modal-factory.js';
import { socialPostForm } from '../forms/social-post-form.js';
import { handleSocialFormSubmission } from '../utils/social-feed/form-submit.js';

let socialModal = null;

/**
 * The core opener that renders the form and attaches JS logic
 */
export function openCreatePostModal(data = {}) {
    if (socialModal) socialModal.destroy();

    socialModal = new Modal({
        id: 'create-post-modal',
        title: 'Create Post',
        content: socialPostForm({
            formId: 'social-post-add-form',
            buttonLabel: 'Publish Post',
            content: data.content || '',
            mediaUrl: data.mediaUrl || '',
            mediaType: data.mediaType || 'none'
        }),
        size: 'md',
        showFooter: false,
    });

    socialModal.open();

    // Give the browser a frame to paint the modal content
    requestAnimationFrame(() => {
        const form = document.getElementById('social-post-add-form');
        if (form) {
            if (data.mediaFilename) {
                form.dataset.preloadedFilename = data.mediaFilename;
            }
            handleSocialFormSubmission(form, socialModal);
        }
    });
}

let listenersAttached = false;

/**
 * Initializes listeners for standard text-post triggers
 */
export function initSocialModal() {
    if (listenersAttached) return;

    document.addEventListener('click', (e) => {
        // TEXT ONLY TRIGGER (The main "What's on your mind" bar or buttons)
        const textTrigger = e.target.closest('#shortcut-post-trigger') || e.target.closest('#create-post-btn');
        
        if (textTrigger) {
            e.preventDefault();
            openCreatePostModal(); 
        }
    });

    listenersAttached = true;
}