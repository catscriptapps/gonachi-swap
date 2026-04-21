// /resources/js/utils/quotations/view-content-mapper.js

import { openMediaUpload } from '../media-manager.js';
import { viewMedia } from './view-media.js';
import { closeTriggerESC } from '../helpers.js';

/**
 * Maps Quotation Data to the View Modal DOM - Gonachi Style 💎
 */
export const ViewContentMapper = {
    // A quick helper to call all mapping functions at once
    mapAll(data) {
        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isOwner = String(ownerId) === String(currentUserId);

        // 💎 STAMP THE STATE: Ensure the grid knows it's the owner immediately
        const grid = document.getElementById('quote-pics-wrapper');
        if (grid) {
            grid.dataset.canManage = isOwner ? 'true' : 'false';
        }
        
        this.handleActionButtons(data); // 💎 Handle Self-Messaging / Action logic
        this.mapBasic(data);
        this.mapLocation(data);
        this.mapClassification(data);
        this.mapFinancials(data);
        this.mapLinks(data);
        this.mapStatus(data);
        this.mapMetadata(data);
        this.syncEditButton(data);
        this.syncConnectButton(data);
    },

    /**
     * Logic to transform Proposal button into Deactivate/Reactivate button for quotation owners 💎
     */
    handleActionButtons(data) {
        const actionBtn = document.getElementById('view-quotation-contact-btn');
        if (!actionBtn) return;

        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isSelf = String(ownerId) === String(currentUserId);
        const isArchived = parseInt(data.statusId) === 2;

        // Reset button to base state first
        actionBtn.disabled = false;
        actionBtn.classList.remove(
            'opacity-50', 'cursor-not-allowed', 'grayscale', 
            'deactivate-quotation-trigger', 'reactivate-quotation-trigger',
            'bg-red-50', 'text-red-600', 'hover:bg-red-600', 'hover:text-white',
            'bg-green-50', 'text-green-600', 'hover:bg-green-600', 'hover:text-white',
            'bg-primary-400', 'hover:bg-primary-500', 'text-white'
        );

        if (isSelf) {
            // 💎 Assign the correct trigger class and ID
            const triggerClass = isArchived ? 'reactivate-quotation-trigger' : 'deactivate-quotation-trigger';
            actionBtn.classList.add(triggerClass);
            actionBtn.dataset.encodedId = data.encodedId || data.id;

            if (isArchived) {
                // Styled for Reactivation (Green)
                actionBtn.classList.add('bg-green-50', 'text-green-600', 'border-green-100', 'hover:bg-green-600', 'hover:text-white');
                
                actionBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reactivate Quotation
                `;
            } else {
                // Styled for Deactivation (Red)
                actionBtn.classList.add('bg-red-50', 'text-red-600', 'border-red-100', 'hover:bg-red-600', 'hover:text-white');
                
                actionBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Deactivate Quotation
                `;
            }
        } else {
            // Standard Proposal logic for non-owners
            actionBtn.classList.add('bg-primary-400', 'text-white', 'hover:bg-primary-500');
            actionBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Submit Proposal
            `;
        }
    },

    /**
     * Handles the "Shouts" from the Media Manager
     */
    initMediaListeners() {
        document.addEventListener('quotation:pics-updated', (e) => {
            const modal = document.getElementById('view-quotation-modal');
            const grid = document.getElementById('quote-pics-wrapper');
            
            if (modal && modal.dataset.quoteId == e.detail.id) {
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
            const uploadBtn = e.target.closest('#trigger-quote-pic-upload');
            if (!uploadBtn) return;

            e.preventDefault();
            const modal = document.getElementById('view-quotation-modal');
            const quoteId = modal?.dataset.quoteId;
            if (!quoteId) return;

            openMediaUpload({
                type: 'quotation', 
                id: quoteId,
                gridId: '#quote-pics-wrapper'
            });
        });

        // --- Close Trigger (Click) ---
        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-quotation-modal') || e.target.id === 'close-view-quotation-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });

        // --- Close Trigger (ESC) ---
        closeTriggerESC(this);
    },

    closeModal() {
        const modal = document.getElementById('view-quotation-modal');
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    },

    mapBasic(data) {
        const titleEl = document.getElementById('view-quote-title');
        const tradeSubEl = document.getElementById('view-quote-trade-sub');
        const initialEl = document.getElementById('view-quote-initial');
        const descEl = document.getElementById('view-quote-description');

        if (titleEl) titleEl.textContent = data.title || 'Untitled Project';
        if (tradeSubEl) tradeSubEl.textContent = data.skilledTradeName || 'General Labor';
        if (descEl) descEl.textContent = data.description || 'No detailed description provided.';

        if (initialEl) {
            const firstLetter = data.title ? data.title.trim().charAt(0).toUpperCase() : 'Q';
            initialEl.textContent = firstLetter;
        }
    },

    mapLocation(data) {
        const countryEl = document.getElementById('view-quote-country');
        const regionEl = document.getElementById('view-quote-region');
        const cityEl = document.getElementById('view-quote-city');

        if (countryEl) countryEl.textContent = data.countryName || '---';
        if (regionEl) regionEl.textContent = data.regionName || '---';
        if (cityEl) cityEl.textContent = data.city || '---';
    },

    mapClassification(data) {
        const contractorEl = document.getElementById('view-quote-contractor-type');
        const tradeEl = document.getElementById('view-quote-skilled-trade');
        const unitEl = document.getElementById('view-quote-unit-type');
        const houseEl = document.getElementById('view-quote-house-type');
        const houseWrapper = document.getElementById('view-quote-house-type-wrapper');

        if (contractorEl) contractorEl.textContent = data.contractorTypeName || '---';
        if (tradeEl) tradeEl.textContent = data.skilledTradeName || '---';
        if (unitEl) unitEl.textContent = data.unitTypeName || '---';
        
        if (houseEl) {
            houseEl.textContent = data.houseTypeName || 'N/A';
            if (data.unitTypeId == '5') {
                houseWrapper?.classList.remove('hidden');
            } else {
                houseWrapper?.classList.add('hidden');
            }
        }
    },

    mapFinancials(data) {
        const timeStartEl = document.getElementById('view-quote-timeline-start');
        const timeFinishEl = document.getElementById('view-quote-timeline-finish');
        const typeLabelEl = document.getElementById('view-quote-type-label');
        const budgetEl = document.getElementById('view-quote-budget');

        if (timeStartEl) timeStartEl.textContent = `${data.startDate || '--'} @ ${data.startTime || '--'}`;
        if (timeFinishEl) timeFinishEl.textContent = `${data.finishDate || '--'} @ ${data.finishTime || '--'}`;
        if (typeLabelEl) typeLabelEl.textContent = data.quotationTypeName || 'Standard';
        if (budgetEl) budgetEl.textContent = data.budget || 'Not Disclosed';
    },

    mapLinks(data) {
        const phoneEl = document.getElementById('view-quote-phone');
        const youtubeEl = document.getElementById('view-quote-url');
        const youtubeIconWrapper = youtubeEl?.closest('.flex')?.querySelector('.bg-white\\/10');

        if (phoneEl) phoneEl.textContent = (data.youtubeUrl && data.youtubeUrl !== '#') ? data.contactPhone : 'No phone provided';

        if (youtubeEl) {
            if (data.youtubeUrl && data.youtubeUrl !== '#' && data.youtubeUrl.trim() !== '') {
                youtubeEl.href = data.youtubeUrl;
                youtubeEl.textContent = 'Watch Project Video';
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
        const statusEl = document.getElementById('view-quote-status');
        if (!statusEl) return;
        const statusId = parseInt(data.statusId) || 0;
        let text = 'DRAFT', classes = 'px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border ';
        switch (statusId) {
            case 1: text = 'ACTIVE'; classes += 'bg-emerald-50 text-emerald-600 border-emerald-100'; break;
            case 2: text = 'ARCHIVED'; classes += 'bg-gray-100 text-gray-600 border-gray-200'; break;
            default: text = 'DRAFT'; classes += 'bg-amber-50 text-amber-600 border-amber-100'; break;
        }
        statusEl.textContent = text;
        statusEl.className = classes;
    },

    mapMetadata(data) {
        const createdEl = document.getElementById('view-quote-created');
        const updatedEl = document.getElementById('view-quote-updated');
        if (createdEl) createdEl.textContent = data.createdAt || '---';
        if (updatedEl) updatedEl.textContent = data.updatedAt || '---';
        if (document.getElementById('view-quotation-views-count')) document.getElementById('view-quotation-views-count').textContent = data.viewsCount || '0';
    },

    /**
     * Syncs the "Submit Proposal" button inside the view modal 🤝
     */
    syncConnectButton(data) {
        const connectBtn = document.getElementById('view-quotation-contact-btn');
        
        if (connectBtn) {
            // Mapping the dataset so openConnectQuotationModal() has all the facts 💎
            connectBtn.dataset.id = data.id || data.encodedId;
            connectBtn.dataset.ownerName = data.ownerName || 'Project Owner';
            connectBtn.dataset.ownerId = data.ownerId;
            connectBtn.dataset.title = data.title || 'Project';
            connectBtn.dataset.budget = data.budget;
            
            // NOTE: We no longer hide the button for the owner, 
            // handleActionButtons() takes care of changing its role.
        }
    },

    syncEditButton(data) {
        const viewEditBtn = document.getElementById('view-quote-edit-btn');
        if (!viewEditBtn) return;

        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isSelf = String(ownerId) === String(currentUserId);

        // 💎 Only show Edit button if it's yours
        if (isSelf) {
            viewEditBtn.classList.remove('hidden');
            viewEditBtn.dataset.triggerOrigin = 'view-modal';
            Object.assign(viewEditBtn.dataset, data);
        } else {
            viewEditBtn.classList.add('hidden');
        }
    }
};