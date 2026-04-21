// resources/js/utils/chats/chat-new.js

import { openSearchModal } from '../../ui/header-search.js';

export function initNewChat() {
    const btnNewChat = document.getElementById('btn-new-chat');

    if (btnNewChat) {
        btnNewChat.onclick = (e) => {
            e.preventDefault();
            // 🍊 Direct trigger to the global search modal
            openSearchModal();
        };
    }
}