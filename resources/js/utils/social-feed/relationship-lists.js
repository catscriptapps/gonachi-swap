// /resources/js/utils/social-feed/relationship-lists.js
import { Modal } from '../../factories/modal-factory.js';
import { updateProfileStats } from './profile-stats.js';
import { showToast } from '../../ui/toast.js';

let relationsModal = null;
const SHARED_MODAL_ID = 'relations-list-modal';

/**
 * Initializes listeners for the sidebar count clicks using delegation
 */
export function initRelationshipLists() {
    // 🍊 Document delegation ensures listeners don't "die" if the sidebar re-renders
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('#following-count, #followers-count');
        if (!trigger) return;

        e.preventDefault();
        const type = trigger.id === 'following-count' ? 'following' : 'followers';
        openRelationsModal(type);
    });

    // Add visual cues to the parents of the counts if they exist on load
    const counts = document.querySelectorAll('#following-count, #followers-count');
    counts.forEach(el => {
        const wrapper = el.parentElement;
        wrapper.classList.add('cursor-pointer', 'hover:opacity-70', 'transition-opacity');
    });
}

/**
 * Opens the modal and fetches the user list
 */
async function openRelationsModal(type) {
    const title = type === 'following' ? 'Following' : 'Followers';
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';

    // Cleanup any existing instance to avoid DOM collisions
    if (relationsModal) {
        relationsModal.destroy();
    }
    
    relationsModal = new Modal({
        id: SHARED_MODAL_ID,
        title: title,
        content: `<div id="relations-loader" class="p-10 text-center"><div class="animate-spin inline-block w-6 h-6 border-2 border-primary-500 border-t-transparent rounded-full"></div></div>`,
        size: 'sm',
        showFooter: false
    });

    relationsModal.open();

    try {
        const response = await fetch(`${baseUrl}api/social-relations?action=get-list&type=${type}`);
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const result = await response.json();

        if (result.success) {
            // Ensure the Modal DOM is fully painted before injecting content
            requestAnimationFrame(() => {
                renderListIntoModal(result.users, type);
            });
        } else {
            throw new Error(result.messages?.[0] || 'Failed to load list');
        }
    } catch (err) {
        console.error("Fetch error:", err);
        const loader = document.getElementById('relations-loader');
        if (loader) {
            loader.innerHTML = `<p class="text-xs text-red-500 font-sans p-4">Error loading ${type}. Please try again.</p>`;
        }
    }
}

/**
 * Renders the users and attaches Unfollow/Block listeners
 */
function renderListIntoModal(users, type) {
    const container = document.getElementById(SHARED_MODAL_ID);
    if (!container) return;

    // Target the specific body area of your factory's output
    const modalBody = container.querySelector('.modal-body') || 
                      container.querySelector('.modal-content-area') || 
                      container.querySelector('.p-4'); 
    
    if (!modalBody) return;

    // 1. Render the HTML content
    if (users.length === 0) {
        modalBody.innerHTML = `<div class="p-8 text-center text-gray-500 italic text-sm font-sans">No ${type} found.</div>`;
    } else {
        modalBody.innerHTML = `
            <div class="divide-y divide-gray-50 dark:divide-gray-800 max-h-[400px] overflow-y-auto custom-scrollbar">
                ${users.map(user => renderUserRow(user, type)).join('')}
            </div>
        `;
    }

    // 2. Attach Delegation Listener (Resetting onclick to prevent duplicates)
    modalBody.onclick = null; 
    modalBody.addEventListener('click', async (e) => {
        const btn = e.target.closest('.rel-action-btn');
        if (!btn) return;

        const userId = btn.dataset.id;
        const action = btn.dataset.action; 
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';
        const endpoint = action === 'block' ? 'api/social-block' : 'api/social-relations';

        // UI Feedback
        btn.disabled = true;
        btn.classList.add('opacity-50');

        try {
            const response = await fetch(`${baseUrl}${endpoint}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(action === 'block' ? { target_id: userId } : { following_id: userId })
            });

            const res = await response.json();
            
            if (res.success) {
                const row = btn.closest('.flex.items-center.justify-between');
                row.classList.add('opacity-0', 'transition-opacity', 'duration-200');
                
                setTimeout(() => {
                    row.remove();
                    updateProfileStats();

                    const message = action === 'block' ? 'User blocked' : 'Unfollowed';
                    showToast(message, action === 'block' ? 'error' : 'success');
                    
                    if (modalBody.querySelectorAll('.rel-action-btn').length === 0) {
                        modalBody.innerHTML = `<div class="p-8 text-center text-gray-500 italic text-sm font-sans">No ${type} remaining.</div>`;
                    }
                }, 200);
            }
        } catch (err) {
            console.error('Action failed:', err);
            btn.disabled = false;
            btn.classList.remove('opacity-50');
            showToast('Something went wrong', 'error');
        }
    });
}

/** * Renders a single user row
 */
function renderUserRow(user, type) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const initials = (user.name || 'U').charAt(0).toUpperCase();
    const actionLabel = type === 'following' ? 'Unfollow' : 'Block';
    const actionType = type === 'following' ? 'unfollow' : 'block';
    
    const actionClass = type === 'following' 
        ? 'text-red-500 bg-red-50 hover:bg-red-100 dark:bg-red-900/20' 
        : 'text-gray-500 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800';

    const avatarHtml = user.avatar 
        ? `<img src="${baseUrl}images/uploads/avatars/${user.avatar}" class="h-9 w-9 rounded-full border border-gray-100 dark:border-gray-800 object-cover shadow-sm" alt="${user.name}">`
        : `<div class="h-9 w-9 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">${initials}</div>`;

    return `
        <div class="flex items-center justify-between p-3 transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
            <div class="flex items-center space-x-3">
                ${avatarHtml}
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[150px]">${user.name}</span>
                    <span class="text-[10px] text-gray-500">@${user.username}</span>
                </div>
            </div>
            <button data-id="${user.id}" data-action="${actionType}" class="rel-action-btn ${actionClass} text-[10px] font-bold px-3 py-1.5 rounded-lg transition-all active:scale-95">
                ${actionLabel}
            </button>
        </div>
    `;
}