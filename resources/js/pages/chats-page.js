// /resources/js/pages/chats-page.js

import { initChatModal } from '../utils/chats/chat-modal.js';
import { initChatForm } from '../utils/chats/chat-form.js';
import { initInboxFilter } from '../utils/chats/chat-inbox-filter.js';

/**
 * Exported init for Vite/Loader setup of Chats Landing page
 */
export function init() {
    initChatModal();
    initChatForm();
    initInboxFilter();
}