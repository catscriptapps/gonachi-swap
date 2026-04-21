// /resources/js/utils/adverts/view-content-mapper.js

import { openMediaUpload } from '../media-manager.js';
import { viewMedia } from './view-media.js';
import { closeTriggerESC } from '../helpers.js';

export const ViewContentMapper = {
    /**
     * Call all mapping functions
     * Now handles the Admin view toggle seamlessly.
     */
    mapAll(data, isAdmin = false) {
        this.mapBasic(data);
        this.mapTargeting(data);
        this.mapUrl(data.landingPageUrl);
        this.mapStatus(data.status);
        
        // Always map the button data (keeps the DOM state consistent)
        this.mapEditButton(data); 

        // Handle the visibility toggle for Admin vs User
        this.toggleAdminMode(isAdmin, data.status);
        this.mapMediaUI(isAdmin);
    },

    /**
     * Handles the visibility of the "Add Photos" button
     */
    mapMediaUI(isAdmin) {
        const addPhotosBtn = document.getElementById('trigger-ad-pic-upload');
        if (!addPhotosBtn) return;

        if (isAdmin) {
            // Admin shouldn't be adding photos to user ads
            addPhotosBtn.classList.add('hidden');
        } else {
            // User needs to see this to manage their media
            addPhotosBtn.classList.remove('hidden');
        }
    },

    /**
     * Specifically handles showing/hiding buttons based on context
     */
    toggleAdminMode(isAdmin, status = 'pending') {
        const adminBox = document.getElementById('view-ad-admin-actions');
        const approveBtn = document.getElementById('admin-approve-ad-btn');
        const deactivateBtn = document.getElementById('admin-deactivate-ad-btn');
        const rejectBtn = document.getElementById('admin-reject-ad-btn');
        const editBtn = document.getElementById('view-ad-edit-btn');

        if (!isAdmin) {
            if (adminBox) adminBox.classList.add('hidden');
            if (editBtn) editBtn.classList.remove('hidden');
            return;
        }

        // Admin Mode active
        if (adminBox) adminBox.classList.remove('hidden');
        if (editBtn) editBtn.classList.add('hidden');

        const normalizedStatus = status.toLowerCase();

        // Logic for button visibility based on current status
        if (normalizedStatus === 'active') {
            approveBtn?.classList.add('hidden');
            deactivateBtn?.classList.remove('hidden');
            rejectBtn?.classList.remove('hidden');

        } else if (normalizedStatus === 'rejected') {
            approveBtn?.classList.remove('hidden');
            deactivateBtn?.classList.remove('hidden');
            rejectBtn?.classList.add('hidden');

        } else if (normalizedStatus === 'inactive') {
            approveBtn?.classList.remove('hidden');
            deactivateBtn?.classList.add('hidden');
            rejectBtn?.classList.remove('hidden');
            
        } else { 
            // Default (Pending)
            approveBtn?.classList.remove('left');
            approveBtn?.classList.remove('hidden');
            deactivateBtn?.classList.remove('hidden');
            rejectBtn?.classList.remove('hidden');
        }
    },

    /**
     * Handles the "Shouts" from the Media Manager
     */
    initMediaListeners() {
        document.addEventListener('advert:pics-updated', (e) => {
            const modal = document.getElementById('view-ad-modal');
            if (modal && modal.dataset.adId == e.detail.id) {
                viewMedia(e.detail.id);
            }
        });
    },

    /**
     * Handles Upload Button and Close triggers
     */
    initUIBehaviors() {
        // --- Upload Trigger ---
        document.addEventListener('click', (e) => {
            const uploadBtn = e.target.closest('#trigger-ad-pic-upload');
            if (!uploadBtn || uploadBtn.classList.contains('hidden')) return;

            e.preventDefault();
            const modal = document.getElementById('view-ad-modal');
            const adId = modal?.dataset.adId;
            if (!adId) return;

            openMediaUpload({
                type: 'advert', 
                id: adId,
                gridId: '#ad-pics-wrapper'
            });
        });

        // --- Close Trigger (Click) ---
        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-ad-modal') || e.target.id === 'close-view-ad-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });

        // --- Close Trigger (ESC) ---
        closeTriggerESC(this);
    },

    closeModal() {
        const modal = document.getElementById('view-ad-modal');
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    },

    mapBasic(data) {
        const fields = {
            'view-ad-title': data.title,
            'view-ad-description': data.description || 'No description provided.',
            'view-ad-keywords': data.keywords || 'None set',
            'view-ad-joined': data.joined && data.joined !== 'N/A' ? data.joined : 'Pending',
            'view-ad-updated': data.updated && data.updated !== 'N/A' ? data.updated : 'Never',
            'view-ad-cta-text': data.callToAction || 'Learn More',
            'view-ad-views-count': data.viewsCount || '0'
        };

        Object.entries(fields).forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        });
        
        // Initial circle
        const initialEl = document.getElementById('view-ad-initial');
        if (initialEl) initialEl.textContent = data.title?.trim().charAt(0).toUpperCase() || 'A';

        // Package Badge
        const packageSubEl = document.getElementById('view-ad-package-sub');
        if (packageSubEl && data.advertPackage) {
            packageSubEl.innerHTML = `
                <div class="flex items-center gap-2 border-t border-gray-50 dark:border-gray-800/50 pt-1">
                    <div class="text-orange-600 dark:text-orange-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="${data.advertPackageIcon}"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-secondary-900 dark:text-gray-300 uppercase tracking-[0.15em]">
                        ${data.advertPackage}
                    </span>
                </div>`;
        }
    },

    mapTargeting(data) {
        const countriesContainer = document.getElementById('view-ad-countries-container');
        const typesContainer = document.getElementById('view-ad-types-container');

        const render = (container, idsJson, namesJson, fallback, isAllEnabled = false) => {
            if (!container) return;
            try {
                const ids = JSON.parse(idsJson || '[]');
                const names = JSON.parse(namesJson || '[]');

                if (isAllEnabled && ids.includes('ALL')) {
                    container.innerHTML = `
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-orange-50 text-orange-600 border border-orange-100 animate-pulse">
                            ALL COUNTRIES
                        </span>`;
                    return;
                }

                if (names.length > 0) {
                    container.innerHTML = names.map(name => `
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-secondary-50 dark:bg-secondary-900/40 text-secondary-600 dark:text-secondary-300 border border-secondary-100 dark:border-gray-800">
                            ${name}
                        </span>`).join('');
                } else {
                    container.innerHTML = `<span class="text-gray-400 text-[10px] italic">${fallback}</span>`;
                }
            } catch (err) {
                container.innerHTML = `<span class="text-gray-400 text-[10px] italic">${fallback}</span>`;
            }
        };

        render(countriesContainer, data.selectedCountries, data.countryNames, 'Global Targeting', true);
        render(typesContainer, data.selectedUserTypes, data.userTypeNames, 'All User Types');
    },

    mapUrl(rawUrl) {
        const urlEl = document.getElementById('view-ad-url');
        if (!urlEl) return;
        if (rawUrl) {
            const hasProtocol = /^https?:\/\//i.test(rawUrl);
            urlEl.href = hasProtocol ? rawUrl : `https://${rawUrl}`;
            urlEl.textContent = rawUrl;
            urlEl.classList.remove('pointer-events-none', 'opacity-50');
        } else {
            urlEl.textContent = 'No Link Provided';
            urlEl.href = '#';
            urlEl.classList.add('pointer-events-none', 'opacity-50');
        }
    },

    mapStatus(status) {
        const statusEl = document.getElementById('view-ad-status');
        if (!statusEl) return;
        const normalizedStatus = (status || 'pending').toLowerCase();
        statusEl.textContent = normalizedStatus.toUpperCase();
        
        const config = {
            active: 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border-green-100',
            pending: 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 border-yellow-100',
            expired: 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100'
        };
        
        statusEl.className = `px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border ${config[normalizedStatus] || ''}`;
    },

    mapEditButton(data) {
        const viewEditBtn = document.getElementById('view-ad-edit-btn');
        if (viewEditBtn) {
            viewEditBtn.dataset.triggerOrigin = 'view-modal';
            Object.assign(viewEditBtn.dataset, data);
        }
    },

    // Add this inside the ViewContentMapper object in view-content-mapper.js
    initAdminUIBehaviors() {
        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-ad-modal') || e.target.id === 'close-view-ad-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });
        closeTriggerESC(this);
    },
};