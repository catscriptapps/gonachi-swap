// /resources/js/utils/chats/chat-delete.js

import { showToast } from '../../ui/toast.js';

export function initDeleteChat(containerSelector = '#modal-chat-stream') {
    const stream = document.querySelector(containerSelector);
    if (!stream) return;

    stream.addEventListener('click', async (e) => {
        const target = e.target;

        // 1. SHOW CONFIRMATION OVERLAY
        if (target.closest('.trigger-delete-btn')) {
            const bubble = target.closest('[id^="msg-"]');
            const confirmOverlay = bubble.querySelector('[id^="confirm-"]');
            confirmOverlay.classList.remove('hidden');
        }

        // 2. CANCEL DELETION
        if (target.closest('.cancel-delete-btn')) {
            const overlay = target.closest('[id^="confirm-"]');
            overlay.classList.add('hidden');
        }

        // 3. ACTUAL CONFIRM (API CALL)
        if (target.closest('.confirm-delete-btn')) {
            const encodedId = target.closest('.confirm-delete-btn').dataset.id;
            const bubble = document.getElementById(`msg-${encodedId}`);
            
            try {
                const response = await fetch(`${window.APP_CONFIG.baseUrl}api/chats`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ _method: 'DELETE', id: encodedId })
                });
                
                const data = await response.json();

                if (data.success) {
                    bubble.classList.add('opacity-0', 'scale-90');
                    setTimeout(() => bubble.remove(), 300);
                    showToast('Message deleted', 'success');
                }
            } catch (err) {
                console.error("Delete failed:", err);
                showToast('Could not delete message', 'error');
            }
        }
    });
}