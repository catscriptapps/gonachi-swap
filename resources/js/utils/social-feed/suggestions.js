// /resources/js/utils/social-feed/suggestions.js

import { updateProfileStats } from "./profile-stats.js";

/**
 * Fetches and renders suggested users to follow
 */
export async function loadFollowSuggestions() {
    const container = document.querySelector('#suggested-follows');
    if (!container) return;
    
    // 🍊 Call this immediately so stats load while the server 
    // is still thinking about who to suggest!
    updateProfileStats();

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    
    try {
        const response = await fetch(`${baseUrl}api/social-relations`);
        const result = await response.json();

        if (result.success && result.users.length > 0) {
            container.innerHTML = result.users.map(user => renderSuggestionRow(user)).join('');
        } else {
            container.innerHTML = '<p class="text-xs text-gray-500 italic px-2">No new suggestions at the moment.</p>';
        }
    } catch (err) {
        console.error('Failed to load suggestions:', err);
        container.innerHTML = ''; 
    }
}

function renderSuggestionRow(user) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const initials = (user.name || 'U').charAt(0).toUpperCase();
    
    // 🍊 Directory: images/uploads/avatars/
    const avatarHtml = user.avatar 
        ? `<img src="${baseUrl}images/uploads/avatars/${user.avatar}" class="h-10 w-10 rounded-full border border-gray-100 dark:border-gray-800 object-cover" alt="${user.name}">`
        : `<div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">${initials}</div>`;

    return `
        <div class="flex items-center justify-between group">
            <div class="flex items-center space-x-3">
                ${avatarHtml}
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[120px]">${user.name}</span>
                    <span class="text-[10px] text-gray-500">@${user.username || 'user'}</span>
                </div>
            </div>
            <button data-user-id="${user.id}" class="follow-toggle-btn text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 dark:bg-primary-900/20 px-3 py-1.5 rounded-lg transition-all active:scale-95">
                Follow
            </button>
        </div>
    `;
}