// /resources/js/utils/chats/chat-form.js

import { uploadModal, createUploadHandler } from '../../modals/upload-modal.js';
import { initChatEmojiPicker } from './chat-emoji-picker.js';
import { showToast } from '../../ui/toast.js';

export function initChatForm() {
    const baseUrl = window.APP_CONFIG.baseUrl;
    const chatModalEl = document.getElementById('chat-detail-modal');
    const form = document.getElementById('modal-chat-form');
    const input = document.getElementById('modal-message-input');
    const submitBtn = form?.querySelector('button[type="submit"]');

    if (!form || !input || !submitBtn) return;

    initChatEmojiPicker();

    // 🍊 Validation Logic
    const toggleSubmitButton = () => {
        const hasText = input.value.trim().length > 0;
        const hasAttachment = !!document.querySelector('[data-staged-wrapper]');
        
        submitBtn.disabled = !(hasText || hasAttachment);
        submitBtn.style.opacity = submitBtn.disabled ? '0.5' : '1';
    };

    input.addEventListener('input', toggleSubmitButton);

    // 1. Attachment Trigger
    document.addEventListener('click', (e) => {
        const attachBtn = e.target.closest('#trigger-file-input');
        if (!attachBtn) return;
        e.preventDefault();

        if (chatModalEl) chatModalEl.classList.add('hidden');
        uploadModal.open();

        const upModalElement = document.getElementById('upload-modal');
        if (upModalElement) {
            upModalElement.addEventListener('modal:closed', () => {
                if (chatModalEl) chatModalEl.classList.remove('hidden');
            }, { once: true });
        }

        setTimeout(() => {
            createUploadHandler(`${baseUrl}api/chat-media-upload`, 'chats', async (uploadedFiles) => {
                if (uploadedFiles && uploadedFiles.length > 0) {
                    
                    // 🍊 THE SWAP LOGIC: 
                    // Check if a file is already staged. If so, trigger the cleanup for the OLD one.
                    const existingInput = document.querySelector('input[name="attachment_url"]');
                    if (existingInput && existingInput.value) {
                        const oldFilename = existingInput.value.split('/').pop();
                        try {
                            await fetch(`${baseUrl}api/chat-media-upload`, {
                                method: 'DELETE',
                                body: JSON.stringify({ filename: oldFilename }),
                                headers: { 'Content-Type': 'application/json' }
                            });
                            console.log("Cleanup: Old orphaned file removed.");
                        } catch (err) {
                            console.error("Cleanup of old file failed:", err);
                        }
                    }

                    // Now show the new one
                    renderChatAttachmentPreview(uploadedFiles[0]);
                    uploadModal.close();
                    toggleSubmitButton(); 
                }
            }, 1, true, { single: true });
        }, 50);
    });

    // 2. Final Message Submission to api/chats
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (submitBtn.disabled) return;

        // 1. Manually grab the values from the DOM
        const msgText = document.getElementById('modal-message-input')?.value || '';
        const toId = form.querySelector('[name="to_user_id"]')?.value;
        
        // Search the WHOLE document for the attachment, just in case it's outside the form tags
        const attachmentEl = document.querySelector('input[name="attachment_url"]');
        const attachmentUrl = attachmentEl ? attachmentEl.value : null;

        // 2. Log it here to be SURE
        console.log("SENDING PAYLOAD:", {
            message_text: msgText,
            to_user_id: toId,
            attachment_url: attachmentUrl
        });

        if (!msgText && !attachmentUrl) return;

        submitBtn.disabled = true;

        // 3. Build the JSON payload
        const payload = {
            message_text: msgText,
            to_user_id: toId,
            attachment_url: attachmentUrl,
            type: attachmentUrl ? 'image' : 'text',
            _method: 'POST'
        };

        try {
            const response = await fetch(`${baseUrl}api/chats`, {
                method: 'POST',
                body: JSON.stringify(payload),
                headers: { 
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            });
            
            const data = await response.json();

            if (data.success) {
                // Success cleanup
                form.reset();
                const previewZone = document.getElementById('modal-attachment-preview');
                if (previewZone) previewZone.innerHTML = ''; 
                toggleSubmitButton(); 

                if (window.refreshMessageStream) {
                    window.refreshMessageStream(toId);
                }
            }
        } catch (err) {
            console.error("Fetch error:", err);
        } finally {
            submitBtn.disabled = false;
        }
    });

    toggleSubmitButton();
}

/**
 * Enhanced Preview with "Search and Destroy" cleanup
 */
function renderChatAttachmentPreview(fileData) {
    const previewContainer = document.getElementById('modal-attachment-preview');
    const baseUrl = window.APP_CONFIG.baseUrl;
    if (!previewContainer) return;

    previewContainer.innerHTML = ''; 
    
    const wrapper = document.createElement('div');
    wrapper.setAttribute('data-staged-wrapper', 'true');
    wrapper.className = "relative w-20 h-20 rounded-xl border-2 border-primary-500 overflow-hidden shadow-lg animate-bounce-in";
    
    // We extract the filename for the cleanup process
    const filename = fileData.url.split('/').pop();
    const filenameOnly = fileData.filename || fileData.url.split('/').pop();

    wrapper.innerHTML = `
        <img src="${fileData.url}" class="w-full h-full object-cover">
        <button type="button" data-remove-staged class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-bl-lg hover:bg-red-600 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3"/></svg>
        </button>
        <input type="hidden" name="attachment_url" value="${filenameOnly}">
        <input type="hidden" name="type" value="image">
    `;

    previewContainer.appendChild(wrapper);

    wrapper.querySelector('[data-remove-staged]').onclick = async () => {
        // 🍊 THE CLEANUP CALL
        try {
            // We tell the server: "User changed their mind, kill this file."
            await fetch(`${baseUrl}api/chat-media-upload`, {
                method: 'DELETE',
                body: JSON.stringify({ filename: filename }),
                headers: { 'Content-Type': 'application/json' }
            });
        } catch (err) {
            console.error("Cleanup failed:", err);
        }

        wrapper.remove();
        
        // Re-run the validation we built earlier
        const input = document.getElementById('modal-message-input');
        const submitBtn = document.querySelector('#modal-chat-form button[type="submit"]');
        if (submitBtn && (!input || !input.value.trim())) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
        }
    };
}