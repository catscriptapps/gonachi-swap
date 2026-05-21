// /resources/js/utils/listings/view-content-mapper.js

import { openMediaUpload } from '../media-manager.js';
import { viewMedia } from './view-media.js';
import { closeTriggerESC } from '../helpers.js';

/**
 * Maps Listing Data to the View Modal DOM - Gonachi Style 💎
 */
export const ViewContentMapper = {
    // A quick helper to call all mapping functions at once
    mapAll(data) {
        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isOwner = String(ownerId) === String(currentUserId);

        // 💎 STAMP THE STATE: Ensure the grid knows it's the owner immediately
        const grid = document.getElementById('listing-pics-wrapper');
        if (grid) {
            grid.dataset.canManage = isOwner ? 'true' : 'false';
        }

        this.handleActionButtons(data); // 💎 Handle Self-Messaging / Action logic
        this.mapBasic(data);
        this.mapLocation(data);
        this.mapClassification(data);
        this.mapLinks(data);
        this.mapStatus(data);
        this.mapMetadata(data);
        this.syncEditButton(data);
        this.syncConnectButton(data);
    },

    /**
     * Logic to transform Contact button into Deactivate/Reactivate button for listing owners 💎
     */
    handleActionButtons(data) {
        const actionBtn = document.getElementById('view-listing-connect-btn');
        if (!actionBtn) return;

        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isSelf = String(ownerId) === String(currentUserId);
        const isArchived = parseInt(data.statusId) === 2;

        // Reset button to base state first
        actionBtn.disabled = false;
        actionBtn.classList.remove(
            'opacity-50', 'cursor-not-allowed', 'grayscale',
            'deactivate-listing-trigger', 'reactivate-listing-trigger',
            'bg-red-50', 'text-red-600', 'hover:bg-red-600', 'hover:text-white',
            'bg-green-50', 'text-green-600', 'hover:bg-green-600', 'hover:text-white',
            'bg-primary-500', 'hover:bg-secondary-950', 'text-secondary-950', 'hover:text-white'
        );

        if (isSelf) {
            // 💎 Assign the correct trigger class and ID
            const triggerClass = isArchived ? 'reactivate-listing-trigger' : 'deactivate-listing-trigger';
            actionBtn.classList.add(triggerClass);
            actionBtn.dataset.encodedId = data.encodedId || data.id;

            if (isArchived) {
                // Styled for Reactivation (Green)
                actionBtn.classList.add('bg-green-50', 'text-green-600', 'hover:bg-green-600', 'hover:text-white');
                actionBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reactivate Swap
                `;
            } else {
                // Styled for Deactivation (Red)
                actionBtn.classList.add('bg-red-50', 'text-red-600', 'hover:bg-red-600', 'hover:text-white');
                actionBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    End Listing
                `;
            }
        } else {
            // Standard Connect logic for non-owners
            actionBtn.classList.add('bg-primary-500', 'text-secondary-950', 'hover:bg-secondary-950', 'hover:text-white');
            actionBtn.innerHTML = `Message Swapper`;
        }
    },

    /**
     * Handles the "Shouts" from the Media Manager
     */
    initMediaListeners() {
        document.addEventListener('listing:pics-updated', (e) => {
            const modal = document.getElementById('view-listing-modal');
            const grid = document.getElementById('listing-pics-wrapper');

            if (modal && modal.dataset.listingId == e.detail.id) {
                // 💎 PERSISTENCE FIX: Check the grid's own state, or the modal's state
                const isOwner = grid?.dataset.canManage === 'true' || modal.dataset.isOwner === 'true';
                viewMedia(e.detail.id, isOwner);
            }
        });
    },

    /**
     * Handles Upload Button and Close triggers
     */
    initUIBehaviors() {
        // --- Upload Trigger ---
        document.addEventListener('click', (e) => {
            const uploadBtn = e.target.closest('#trigger-listing-pic-upload');
            if (!uploadBtn) return;

            e.preventDefault();
            const modal = document.getElementById('view-listing-modal');
            const listingId = modal?.dataset.listingId;
            if (!listingId) return;

            openMediaUpload({
                type: 'listing',
                id: listingId,
                gridId: '#listing-pics-wrapper'
            });
        });

        // --- Close Trigger (Click) ---
        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-listing-modal') || e.target.id === 'close-view-listing-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });

        // --- Close Trigger (ESC) ---
        closeTriggerESC(this);
    },

    closeModal() {
        const modal = document.getElementById('view-listing-modal');
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    },

    mapBasic(data) {
        const titleEl = document.getElementById('view-listing-title');
        const categorySubEl = document.getElementById('view-listing-category-sub');
        const initialEl = document.getElementById('view-listing-initial');
        const descEl = document.getElementById('view-listing-description');

        if (titleEl) titleEl.textContent = data.listingTitle || 'Untitled Listing';
        if (categorySubEl) categorySubEl.textContent = data.categoryName || 'General Category';
        if (descEl) descEl.textContent = data.listingDescription || 'No description provided.';

        if (initialEl) {
            const firstLetter = data.categoryName ? data.categoryName.trim().charAt(0).toUpperCase() : 'L';
            initialEl.textContent = firstLetter;
        }
    },

    mapLocation(data) {
        const countryEl = document.getElementById('view-listing-country');
        const regionEl = document.getElementById('view-listing-region');
        const cityEl = document.getElementById('view-listing-city');

        if (countryEl) countryEl.textContent = data.countryName || '---';
        if (regionEl) regionEl.textContent = data.regionName || '---';
        if (cityEl) cityEl.textContent = data.city || '---';
    },

    mapClassification(data) {
        const typeEl = document.getElementById('view-listing-type');
        const conditionEl = document.getElementById('view-listing-condition');
        const tradePrefEl = document.getElementById('view-listing-trade-pref');
        const priceEl = document.getElementById('view-listing-price');
        const tradeWrapper = document.getElementById('view-listing-trade-pref-wrapper');
        const typeBadgeEl = document.getElementById('view-listing-type-badge');
        const conditionBadgeEl = document.getElementById('view-listing-condition-badge');

        if (typeEl) typeEl.textContent = data.typeName || 'Swap';
        if (conditionEl) conditionEl.textContent = data.conditionName || 'Used';
        if (tradePrefEl) tradePrefEl.textContent = data.tradePref || 'None';
        if (priceEl) priceEl.textContent = data.price && data.price !== 'Trade' ? `$${data.price}` : 'Trade Only';
        if (typeBadgeEl) typeBadgeEl.textContent = data.typeName || 'Swap';
        if (conditionBadgeEl) conditionBadgeEl.textContent = data.conditionName || 'Used';

        if (tradeWrapper) {
            tradeWrapper.classList.toggle('hidden', !data.tradePref || data.tradePref === 'None');
        }
    },

    mapLinks(data) {
        const phoneEl = document.getElementById('view-listing-phone');
        const youtubeEl = document.getElementById('view-listing-url');
        const youtubeIconWrapper = youtubeEl?.closest('.flex')?.querySelector('.bg-white\\/10');

        if (phoneEl) phoneEl.textContent = data.contactPhone || 'No phone provided';

        if (youtubeEl) {
            if (data.youtubeUrl && data.youtubeUrl !== '#' && data.youtubeUrl.trim() !== '') {
                youtubeEl.href = data.youtubeUrl;
                youtubeEl.textContent = 'Watch Walkthrough';
                youtubeEl.classList.remove('pointer-events-none', 'opacity-50', 'cursor-default');
                youtubeEl.classList.add('hover:text-primary-300');
                if (youtubeIconWrapper) youtubeIconWrapper.classList.remove('opacity-40', 'grayscale');
            } else {
                youtubeEl.removeAttribute('href');
                youtubeEl.textContent = 'No video provided';
                youtubeEl.classList.add('pointer-events-none', 'opacity-50', 'cursor-default');
                youtubeEl.classList.remove('hover:text-primary-300');
                if (youtubeIconWrapper) youtubeIconWrapper.classList.add('opacity-40', 'grayscale');
            }
        }
    },

    mapStatus(data) {
        const statusEl = document.getElementById('view-listing-status');
        if (!statusEl) return;
        const statusId = parseInt(data.statusId) || 0;
        let text = 'DRAFT', classes = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider border ';
        switch (statusId) {
            case 1:
                text = 'ACTIVE';
                classes += 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-800/30';
                break;
            case 2:
                text = 'ARCHIVED';
                classes += 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700';
                break;
            default:
                text = 'DRAFT';
                classes += 'bg-primary-50 dark:bg-primary-900/20 text-primary-400 dark:text-primary-300 border border-primary-100 dark:border-primary-800/30';
                break;
        }
        statusEl.textContent = text;
        statusEl.className = classes;
    },

    mapMetadata(data) {
        const createdEl = document.getElementById('view-listing-created');
        if (createdEl) createdEl.textContent = data.createdAt || '---';
        if (document.getElementById('view-listing-views-count')) {
            document.getElementById('view-listing-views-count').textContent = data.views || '0';
        }
    },

    syncConnectButton(data) {
        const connectBtn = document.getElementById('view-listing-connect-btn');
        if (connectBtn) {
            connectBtn.dataset.id = data.id || data.encodedId;
            connectBtn.dataset.ownerName = data.ownerName;
            connectBtn.dataset.ownerId = data.ownerId;
            connectBtn.dataset.title = data.listingTitle;
        }
    },

    syncEditButton(data) {
        const viewEditBtn = document.getElementById('view-listing-edit-btn');
        if (!viewEditBtn) return;

        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isSelf = String(ownerId) === String(currentUserId);

        if (isSelf) {
            viewEditBtn.classList.remove('hidden');
            viewEditBtn.dataset.triggerOrigin = 'view-modal';
            Object.assign(viewEditBtn.dataset, data);
        } else {
            viewEditBtn.classList.add('hidden');
        }
    }
};