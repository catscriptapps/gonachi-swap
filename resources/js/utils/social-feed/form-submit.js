// /resources/js/utils/social-feed/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';

/**
 * Maps form data to an API payload for Social Posts
 */
function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // 🍊 Handshake check:
    // If we have data in the hidden inputs (from our form builder), use those.
    // Otherwise, fallback to the dataset attached to the form element.
    const mediaUrl = data.media_url || form.dataset.preloadedFilename || '';
    let mediaType = data.media_type || 'none';

    // 🍊 Robust Type Correction: 
    // If the hidden input is 'none' but we actually have a filename, 
    // and we know it's not a video, default to 'image'.
    if (mediaUrl && mediaType === 'none') {
        mediaType = 'image';
    }
    
    return {
        action: 'create', 
        content: data.content?.trim() || '',
        media_url: mediaUrl,
        media_type: mediaType
    };
}

/**
 * Handles Social Post form submission
 */
export function handleSocialFormSubmission(
    form, 
    modalInstance, 
    feedSelector = '#social-feed-container'
) {
    if (form._socialFormListenerAttached) return;
    form._socialFormListenerAttached = true;

    const validator = new FormValidator(form);
    const submitBtn = form.querySelector('#post-submit-btn');
    let apiMsg = form.querySelector('.api-message');

    // Remove media logic
    const removeMediaBtn = form.querySelector('#remove-media-btn');
    if (removeMediaBtn) {
        removeMediaBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const previewArea = form.querySelector('#media-preview-area');
            const mediaTypeInput = form.querySelector('#post-media-type');
            const mediaUrlInput = form.querySelector('#post-media-url'); // 🍊 Added
            
            if (previewArea) previewArea.classList.add('hidden');
            if (mediaTypeInput) mediaTypeInput.value = 'none';
            if (mediaUrlInput) mediaUrlInput.value = ''; // 🍊 Clear hidden input
            
            form.dataset.preloadedFilename = '';
            form.dataset.preloadedMedia = '';
        });
    }

    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300';
        form.appendChild(apiMsg);
    }

    const originalLabel = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const payload = getPayload(form);

        // Custom validation: Must have either text OR media
        if (!payload.content && !payload.media_url) {
            apiMsg.innerHTML = `
                <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center">
                    Please add some text or media to your post.
                </div>`;
            return;
        }

        // UI State: Loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 
        apiMsg.innerHTML = '';

        try {
            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/social-feed`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success) {
                const feedContainer = document.querySelector(feedSelector);
                
                if (feedContainer) {
                    const emptyState = document.getElementById('empty-feed-msg');
                    if (emptyState) emptyState.remove();

                    if (result.html) {
                        feedContainer.insertAdjacentHTML('afterbegin', result.html);
                    }
                }

                apiMsg.innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center font-sans">
                        🚀 Post published successfully!
                    </div>
                `;

                submitBtn.style.display = 'none'; 

                setTimeout(() => {
                    if (modalInstance && typeof modalInstance.close === 'function') {
                        modalInstance.close();
                    } else if (modalInstance && typeof modalInstance.destroy === 'function') {
                        modalInstance.destroy();
                    }
                }, 800);
            } else {
                apiMsg.innerHTML = (result.messages || [result.message || 'Error saving post']).map(msg => `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center">
                        ${msg}
                    </div>
                `).join('');
            }

        } catch (err) {
            console.error('Social Feed Submission Error:', err);
            apiMsg.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 text-center">
                    Connection error. Please try again.
                </div>`;
        } finally {
            if (submitBtn.style.display !== 'none') {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}