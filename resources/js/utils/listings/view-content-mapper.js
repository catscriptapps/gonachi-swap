// /resources/js/utils/listings/view-content-mapper.js

import { openMediaUpload } from '../media-manager.js';
import { viewMedia } from './view-media.js';
import { closeTriggerESC } from '../helpers.js';

/**
 * Maps Listing Data to the View Modal DOM - Gonachi Style 💎
 */
export const ViewContentMapper = {
    mapAll(data) {
        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isOwner = String(ownerId) === String(currentUserId);

        const grid = document.getElementById('listing-pics-wrapper');
        if (grid) {
            grid.dataset.canManage = isOwner ? 'true' : 'false';
        }

        this.toggleCategorySections(data);
        this.handleActionButtons(data); 

        this.mapBasic(data);
        this.mapLocation(data);
        this.mapPropertySpecs(data);
        this.mapFeatures(data); 
        this.mapFinancials(data);
        this.mapLinks(data);
        this.mapStatus(data);
        this.mapMetadata(data);
        this.syncEditButton(data);
        this.syncConnectButton(data);
    },

    /**
     * Logic to transform Contact button into Deactivate button for listing owners 💎
     */
    handleActionButtons(data) {
        const actionBtn = document.getElementById('view-listing-contact-btn');
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
            'bg-primary-400', 'hover:bg-primary-500', 'text-white'
        );

        // Reset button to base state first
        if (isSelf) {
            // 💎 THE FIX: Assign the correct trigger class so the listener picks it up
            const triggerClass = isArchived ? 'reactivate-listing-trigger' : 'deactivate-listing-trigger';
            actionBtn.classList.add(triggerClass);

            if (isArchived) {
                // Styled for Reactivation (Green/Success)
                actionBtn.classList.add('bg-green-50', 'text-green-600', 'hover:bg-green-100', 'border-green-100');
                actionBtn.classList.remove('bg-primary-400', 'text-white', 'bg-red-50', 'text-red-600');
                
                actionBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reactivate Listing
                `;
            } else {
                // Styled for Deactivation (Red/Danger)
                actionBtn.classList.add('bg-red-50', 'text-red-600', 'hover:bg-red-100', 'border-red-100');
                actionBtn.classList.remove('bg-primary-400', 'text-white', 'bg-green-50', 'text-green-600');
                
                actionBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    End Listing
                `;
            }
        } else {
            // Standard Contact logic for non-owners...
            actionBtn.classList.add('bg-primary-400', 'text-white', 'hover:bg-primary-500');
            actionBtn.innerHTML = `Contact Owner`;
        }
    },

    toggleCategorySections(data) {
        const reSections = ['section-location', 'section-property-details', 'section-amenities', 'section-availability-financials'];
        const categoryId = parseInt(data.categoryId);
        const isService = (categoryId === 2 || categoryId === 3);

        reSections.forEach(id => {
            const element = document.getElementById(id);
            if (element) element.classList.toggle('hidden', isService);
        });
    },

    initMediaListeners() {
        document.addEventListener('listing:pics-updated', (e) => {
            const modal = document.getElementById('view-listing-modal');
            const grid = document.getElementById('listing-pics-wrapper');
            if (modal && modal.dataset.listingId == e.detail.id) {
                const isOwner = grid?.dataset.canManage === 'true' || modal.dataset.isOwner === 'true';
                viewMedia(e.detail.id, isOwner);
            }
        });
    },

    initUIBehaviors() {
        document.addEventListener('click', (e) => {
            const uploadBtn = e.target.closest('#trigger-listing-pic-upload');
            if (uploadBtn) {
                e.preventDefault();
                const modal = document.getElementById('view-listing-modal');
                openMediaUpload({ type: 'listing', id: modal?.dataset.listingId, gridId: '#listing-pics-wrapper' });
            }
        });

        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-listing-modal') || e.target.id === 'close-view-listing-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });

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
        const catSubEl = document.getElementById('view-listing-category-sub');
        const initialEl = document.getElementById('view-listing-initial');
        const descEl = document.getElementById('view-listing-description');

        if (titleEl) titleEl.textContent = data.listingTitle || 'Untitled Property';
        if (catSubEl) catSubEl.textContent = data.categoryName || 'Real Estate';
        if (descEl) descEl.textContent = data.listingDescription || 'No description provided.';
        if (initialEl) initialEl.textContent = data.listingTitle ? data.listingTitle.trim().charAt(0).toUpperCase() : 'P';
    },

    mapLocation(data) {
        const regionEl = document.getElementById('view-listing-region');
        const countryEl = document.getElementById('view-listing-country');
        const cityEl = document.getElementById('view-listing-city');
        const addressEl = document.getElementById('view-listing-address');
        const headerLocEl = document.getElementById('view-listing-location-header');

        if (regionEl) regionEl.textContent = data.regionName || '---';
        if (countryEl) countryEl.textContent = data.countryName || '---';
        if (cityEl) cityEl.textContent = data.city || '---';
        if (addressEl) addressEl.textContent = data.address || 'Address hidden';

        if (headerLocEl) {
            const city = data.city ? `${data.city} - ` : '';
            headerLocEl.textContent = `${city}${data.regionName || ''}, ${data.countryName || ''}`;
        }
    },

    mapPropertySpecs(data) {
        const unitEl = document.getElementById('view-listing-unit-type');
        const houseEl = document.getElementById('view-listing-house-type');
        const bedsEl = document.getElementById('view-listing-bedrooms'); 
        const bathsEl = document.getElementById('view-listing-bathrooms'); 
        const sizeEl = document.getElementById('view-listing-size');
        const parkingEl = document.getElementById('view-listing-parking');

        if (unitEl) unitEl.textContent = data.unitTypeName || '---';
        if (houseEl) houseEl.textContent = data.houseTypeName || '---';
        if (bedsEl) bedsEl.textContent = data.bedroomLabel ? `${data.bedroomLabel} Beds` : '---';
        if (bathsEl) bathsEl.textContent = data.bathroomLabel ? `${data.bathroomLabel} Baths` : '---';
        if (sizeEl) sizeEl.textContent = data.propertySize ? `${data.propertySize} sq ft` : '---';
        if (parkingEl) parkingEl.textContent = parseInt(data.parking) === 1 ? 'Available' : 'None';
    },

    mapFeatures(data) {
        const container = document.getElementById('view-listing-amenities-container');
        if (!container) return;
        container.innerHTML = '';
        let amenities = [];
        try {
            amenities = typeof data.amenitiesCollection === 'string' ? JSON.parse(data.amenitiesCollection) : (data.amenitiesCollection || []);
        } catch (e) { return; }

        if (amenities.length > 0) {
            const badgesDiv = document.createElement('div');
            badgesDiv.className = 'flex flex-wrap gap-2 col-span-full';
            badgesDiv.innerHTML = amenities.map(item => `
                <span class="px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-secondary-900/50 border border-gray-100 dark:border-secondary-800 text-[11px] font-bold text-secondary-900 dark:text-gray-300 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    ${item.name}
                </span>
            `).join('');
            container.appendChild(badgesDiv);
        }
    },

    mapFinancials(data) {
        const priceEl = document.getElementById('view-listing-price');
        const moveInEl = document.getElementById('view-listing-move-in');
        const agreementEl = document.getElementById('view-listing-agreement');
        const petsEl = document.getElementById('view-listing-pets');
        const acEl = document.getElementById('view-listing-is-ac');
        const furnishedEl = document.getElementById('view-listing-is-furnished');

        if (priceEl) priceEl.textContent = data.price || 'Contact for Price';
        if (moveInEl) moveInEl.textContent = data.moveInDate || '---';
        if (agreementEl) agreementEl.textContent = data.agreementTypeName || '---';
        
        if (petsEl) petsEl.textContent = parseInt(data.petsAllowed) === 1 ? 'Allowed' : 'Not Allowed';
        if (acEl) acEl.textContent = parseInt(data.isAc) === 1 ? 'Installed' : 'None';
        if (furnishedEl) furnishedEl.textContent = parseInt(data.isFurnished) === 1 ? 'Yes' : 'No';
    },

    mapLinks(data) {
        const phoneEl = document.getElementById('view-listing-phone');
        const youtubeEl = document.getElementById('view-listing-url');
        if (phoneEl) phoneEl.textContent = data.contactPhone || 'No phone provided';
        if (youtubeEl) {
            if (data.youtubeUrl && data.youtubeUrl !== '#') {
                youtubeEl.href = data.youtubeUrl;
                youtubeEl.textContent = 'Watch Property Video';
                youtubeEl.classList.remove('pointer-events-none', 'opacity-50');
            } else {
                youtubeEl.removeAttribute('href');
                youtubeEl.textContent = 'No video available';
                youtubeEl.classList.add('pointer-events-none', 'opacity-50');
            }
        }
    },

    mapStatus(data) {
        const statusEl = document.getElementById('view-listing-status');
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
        if (document.getElementById('view-listing-created')) document.getElementById('view-listing-created').textContent = data.createdAt || '---';
        if (document.getElementById('view-listing-updated')) document.getElementById('view-listing-updated').textContent = data.updatedAt || '---';
        if (document.getElementById('view-listing-views-count')) document.getElementById('view-listing-views-count').textContent = data.viewsCount || '0';
    },

    syncConnectButton(data) {
        const connectBtn = document.getElementById('view-listing-contact-btn');
        if (connectBtn) {
            connectBtn.dataset.id = data.id || data.encodedId;
            connectBtn.dataset.ownerName = data.ownerName;
            connectBtn.dataset.ownerId = data.ownerId;
            connectBtn.dataset.listingTitle = data.listingTitle;
            connectBtn.dataset.price = data.price;
            connectBtn.dataset.statusId = data.statusId; // Crucial for toggle logic
        }
    },

    syncEditButton(data) {
        const viewEditBtn = document.getElementById('view-listing-edit-btn');
        if (!viewEditBtn) return;
        const ownerId = data.ownerId || data.userId;
        const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
        const isSelf = String(ownerId) === String(currentUserId);
        viewEditBtn.classList.toggle('hidden', !isSelf);
        if (isSelf) Object.assign(viewEditBtn.dataset, data);
    }
};