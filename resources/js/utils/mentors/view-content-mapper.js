// /resources/js/utils/mentors/view-content-mapper.js

import { closeTriggerESC } from '../helpers.js';

/**
 * Maps Mentor Profile Data to the View Modal DOM - Gonachi Style 💎
 */
export const ViewContentMapper = {
    
    mapAll(data) {
        this.mapBasic(data);
        this.mapLocation(data);
        this.mapCredentials(data);
        this.mapProfessionalBio(data);
        this.mapSkills(data);
        this.mapDigitalPresence(data); 
        this.syncEditButton(data);
        this.syncConnectButton(data);
        this.handleActionButtons(data);
    },

    /**
     * Logic to disable messaging yourself
     */
    handleActionButtons(data) {
        const messageBtn = document.getElementById('view-mentor-connect-btn');
        if (!messageBtn) return;

        const ownerId = data.ownerId || data.userId; // Depending on your dataset naming
        const isSelf = String(ownerId) === String(window.sessionUserId);
        const svg = `<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>`;

        if (isSelf) {
            messageBtn.disabled = true;
            messageBtn.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
            messageBtn.innerHTML = svg+'Your Profile';
            // Optional: Remove click-to-open-handshake listeners here if needed
        } else {
            messageBtn.disabled = false;
            messageBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');
            messageBtn.innerHTML = svg+'Message Mentor';
        }
    },

    initUIBehaviors() {
        document.addEventListener('click', (e) => {
            const isCloseTrigger = e.target.closest('.close-view-mentor-modal') || e.target.id === 'close-view-mentor-modal-overlay';
            if (isCloseTrigger) this.closeModal();
        });

        closeTriggerESC(this);
    },

    closeModal() {
        const modal = document.getElementById('view-mentor-modal');
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    },

    mapBasic(data) {
        const nameEl = document.getElementById('view-mentor-name');
        const headlineEl = document.getElementById('view-mentor-headline');
        const initialEl = document.getElementById('view-mentor-initial');

        if (nameEl) nameEl.textContent = data.ownerName || 'Professional Mentor';
        if (headlineEl) headlineEl.textContent = data.headline || 'Expert Consultant';

        if (initialEl) {
            const firstLetter = data.ownerName ? data.ownerName.trim().charAt(0).toUpperCase() : 'M';
            initialEl.textContent = firstLetter;
        }
    },

    mapLocation(data) {
        const locationEl = document.getElementById('view-mentor-location');
        const countryDetailEl = document.getElementById('view-mentor-country-detail');
        const regionDetailEl = document.getElementById('view-mentor-region-detail');
        const cityDetailEl = document.getElementById('view-mentor-city-detail');

        // 1. Sidebar Summary (Combines City and Country)
        if (locationEl) {
            const parts = [data.city, data.countryName].filter(val => val && val !== '---' && val !== 'N/A');
            locationEl.textContent = parts.length > 0 ? parts.join(', ') : 'Global / Remote';
        }

        // 2. Specific Detail Fields (Matching Quotation layout style) 💎
        if (countryDetailEl) {
            countryDetailEl.textContent = data.countryName || '---';
        }
        if (regionDetailEl) {
            regionDetailEl.textContent = data.regionName || '---';
        }
        if (cityDetailEl) {
            cityDetailEl.textContent = data.city || '---';
        }
    },

    mapCredentials(data) {
        const expEl = document.getElementById('view-mentor-exp');
        const typeBadge = document.getElementById('view-mentor-type-badge');

        if (expEl) {
            const years = parseInt(data.experienceYears || 0);
            expEl.textContent = years > 0 ? `${years}+ Years` : 'Expert';
        }

        if (typeBadge) {
            typeBadge.textContent = data.targetUserType || 'Expert';
        }
    },

    mapProfessionalBio(data) {
        const bioEl = document.getElementById('view-mentor-bio');
        if (bioEl) bioEl.textContent = data.bio || 'No biography provided.';
    },

    mapSkills(data) {
        const container = document.getElementById('view-mentor-skills-container');
        if (!container) return;

        container.innerHTML = '';
        
        // Handle skills coming in as a comma-string or JSON string
        let skills = [];
        try {
            skills = data.skillsJson ? JSON.parse(data.skillsJson) : (data.skills ? data.skills.split(',') : []);
        } catch (e) {
            skills = data.skills ? data.skills.split(',') : [];
        }

        if (skills.length === 0) {
            container.innerHTML = '<span class="text-xs text-gray-400 italic">No specific skills listed.</span>';
            return;
        }

        skills.forEach(skill => {
            if (!skill.trim()) return;
            const tag = document.createElement('span');
            tag.className = 'px-4 py-2 bg-white dark:bg-gray-800 rounded-xl text-xs font-bold text-secondary-700 dark:text-gray-300 border border-gray-100 dark:border-white/5 shadow-sm';
            tag.textContent = skill.trim();
            container.appendChild(tag);
        });
    },

    mapDigitalPresence(data) {
        const youtubeEl = document.getElementById('view-mentor-youtube-url');
        const websiteEl = document.getElementById('view-mentor-website-url');
        
        // Find the icon wrapper specifically to apply the grayscale/opacity effects
        const youtubeIconWrapper = youtubeEl?.closest('.flex')?.querySelector('.youtube-icon-bg');

        // --- YouTube Specific Logic (Quotation Style) ---
        if (youtubeEl) {
            if (data.youtubeUrl && data.youtubeUrl !== '#' && data.youtubeUrl.trim() !== '') {
                youtubeEl.href = data.youtubeUrl.startsWith('http') ? data.youtubeUrl : `https://${data.youtubeUrl}`;
                youtubeEl.textContent = 'Watch Mentor Video';
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

        // --- Website Logic ---
        if (websiteEl) {
            if (data.websiteUrl && data.websiteUrl !== '#' && data.websiteUrl.trim() !== '') {
                websiteEl.href = data.websiteUrl.startsWith('http') ? data.websiteUrl : `https://${data.websiteUrl}`;
                websiteEl.textContent = 'Visit Website';
                websiteEl.classList.remove('hidden');
            } else {
                websiteEl.classList.add('hidden');
            }
        }
    },

    handleLink(el, url) {
        if (!el) return;
        
        if (url && url !== '#' && url.trim() !== '') {
            el.href = url.startsWith('http') ? url : `https://${url}`;
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    },

    /**
     * Syncs the "Message Mentor" button inside the view modal 🤝
     */
    syncConnectButton(data) {
        const connectBtn = document.getElementById('view-mentor-connect-btn');

        if (connectBtn) {
            // Mapping the dataset so openConnectMentorModal() has all the facts 💎
            connectBtn.dataset.id = data.id || data.encodedId;
            connectBtn.dataset.ownerName = data.ownerName || 'Professional Mentor';
            connectBtn.dataset.ownerId = data.ownerId;
            connectBtn.dataset.targetUserType = data.targetUserType || 'Expert';
            
            // Optional: Hide button if viewing own profile
            const currentUserId = window.APP_CONFIG?.user?.id;
            if (currentUserId && parseInt(currentUserId) === parseInt(data.ownerId)) {
                connectBtn.classList.add('hidden');
            } else {
                connectBtn.classList.remove('hidden');
            }
        }
    },

    syncEditButton(data) {
        const viewEditBtn = document.getElementById('view-mentor-edit-btn');
        if (viewEditBtn) {
            viewEditBtn.dataset.triggerOrigin = 'view-modal';
            Object.assign(viewEditBtn.dataset, data);
        }
    }
};