// /resources/js/utils/chats/chat-modal.js

import { initDeleteChat } from './chat-delete.js';
import { openLightbox } from '../../ui/lightbox.js';
import { initNewChat } from './chat-new.js';

let chatInterval = null;
let lastMessageId = 0; // Track the last ID to only pull new messages

export function initChatModal() {
    const modal = document.getElementById('chat-detail-modal');
    const fileInput = document.getElementById('modal-file-input');
    const fileTrigger = document.getElementById('trigger-file-input');
    const previewContainer = document.getElementById('modal-attachment-preview');

    // Initialize the delete delegation for the stream
    initDeleteChat('#modal-chat-stream');
    initNewChat(); // Initialize the "New Chat" button logic

    // 1. Check for the "Flash State" (Redirects from profile/search)
    const pendingId = sessionStorage.getItem('pending_chat_id');
    if (pendingId) {
        openChatModal(pendingId);
        sessionStorage.removeItem('pending_chat_id');
    }

    // 2. Row Click Event Delegation
    document.addEventListener('click', (e) => {
        const row = e.target.closest('.chat-row-trigger');
        if (row) {
            const encodedId = row.getAttribute('data-id');
            if (encodedId) openChatModal(encodedId);
        }
    });

    // 3. File Attachment Logic (Preview)
    if (fileTrigger && fileInput) {
        fileTrigger.onclick = () => fileInput.click();
        fileInput.onchange = () => {
            previewContainer.innerHTML = '';
            Array.from(fileInput.files).forEach(file => {
                const isImage = file.type.startsWith('image/');
                const previewWrapper = document.createElement('div');
                previewWrapper.className = "relative w-12 h-12 rounded-lg border border-gray-200 dark:border-white/10 overflow-hidden bg-gray-100 dark:bg-white/5 shadow-sm";

                if (isImage) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewWrapper.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewWrapper.innerHTML = `<div class="flex items-center justify-center h-full text-[10px] font-black text-red-500 bg-red-500/10 uppercase">PDF</div>`;
                }
                previewContainer.appendChild(previewWrapper);
            });
        };
    }

    // 4. Closing Logic
    const closeChatModal = () => {
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; 
            if (chatInterval) clearInterval(chatInterval);
            lastMessageId = 0; // Reset for the next person
            if (previewContainer) previewContainer.innerHTML = ''; // Clear staged files
        }
    };

    const closeBtn = document.getElementById('close-chat-modal');
    if (closeBtn) closeBtn.onclick = closeChatModal;

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeChatModal();
    });

    modal.addEventListener('click', (e) => {
        if (e.target.id === 'chat-detail-modal') closeChatModal();
    });
}

export async function openChatModal(encodedId) {
    const modal = document.getElementById('chat-detail-modal');
    const stream = document.getElementById('modal-chat-stream');
    const userInfo = document.getElementById('modal-user-info');
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';

    if (!modal) return;
    if (!stream) return;

    stream.addEventListener('click', (e) => {
        // Find the image that has our zoom class
        const img = e.target.closest('img.cursor-zoom-in');
        
        if (img) {
            e.preventDefault();
            openLightbox(img.src);
        }
    });

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
    
    // Loading State
    stream.innerHTML = `<div class="flex items-center justify-center h-full"><div class="w-8 h-8 border-4 border-primary-500/20 border-t-primary-500 rounded-full animate-spin"></div></div>`;

    try {
        const response = await fetch(`${baseUrl}api/chats?with=${encodedId}`);
        const data = await response.json();

        if (data.success) {
            userInfo.innerHTML = data.html_header;
            stream.innerHTML = data.html_messages;
            stream.scrollTop = stream.scrollHeight;
            lastMessageId = data.last_id || 0;

            // Prepare Hidden Form Field
            const form = document.getElementById('modal-chat-form');
            if (form) {
                let input = form.querySelector('input[name="to_user_id"]');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'to_user_id';
                    form.appendChild(input);
                }
                input.value = encodedId;
            }

            // START POLLING
            if (chatInterval) clearInterval(chatInterval);
            chatInterval = setInterval(() => {
                if (document.hasFocus()) refreshMessageStream(encodedId);
            }, 3000);
        }
    } catch (err) {
        console.error("Failed to load chat:", err);
        stream.innerHTML = `<div class="p-8 text-center text-gray-400 italic font-bold uppercase tracking-widest text-[10px]">Failed to load conversation.</div>`;
    }
}

async function refreshMessageStream(encodedId) {
    const stream = document.getElementById('modal-chat-stream');
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    
    try {
        // Only fetch what we don't have yet
        const response = await fetch(`${baseUrl}api/chats?with=${encodedId}&refresh=1&last_id=${lastMessageId}`);
        const data = await response.json();

        if (data.success) {
            // 🍊 1. SYNC DELETIONS (The Janitor)
            // Checks if any message currently on screen is missing from the server's active_ids
            if (data.active_ids) {
                const existingBubbles = stream.querySelectorAll('[id^="msg-"]');
                const incomingIds = data.active_ids.map(id => `msg-${id}`);

                existingBubbles.forEach(bubble => {
                    if (!incomingIds.includes(bubble.id)) {
                        // Smooth removal transition
                        bubble.classList.add('opacity-0', 'scale-95', 'transition-all', 'duration-500');
                        setTimeout(() => bubble.remove(), 500);
                    }
                });
            }

            // 2. APPEND NEW MESSAGES
            if (data.html_messages && data.html_messages.trim() !== '') {
                // Append new content without overwriting (avoids flicker)
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html_messages;
                
                while (tempDiv.firstChild) {
                    stream.appendChild(tempDiv.firstChild);
                }

                // Only update if the new ID is actually newer
                if (data.last_id > lastMessageId) {
                    lastMessageId = data.last_id;
                }
                stream.scrollTop = stream.scrollHeight;
            }
        }
    } catch (err) {
        console.error("Polling error:", err);
    }
}