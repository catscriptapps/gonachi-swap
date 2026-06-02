// /resources/js/utils/notifications/listings.js

import { ListingActions } from './listings-actions.js';
import { initViewListing } from '../listings/view-listing.js';

export const ListingHandler = {
    async process(data) {
        const { noteId, targetId, element } = data;
        const ds = element.dataset;

        // 1. Data Prep
        const senderName = ds.senderName || 'System';
        const firstInitial = senderName.charAt(0).toUpperCase();
        const senderAvatar = ds.senderAvatar || '';
        const senderLocation = ds.senderLocation || 'N/A';
        const subject = (ds.subject || '').toLowerCase(); 
        let userTypes = [];
        try { userTypes = JSON.parse(ds.senderUserTypes || '[]'); } catch(e) {}

        const contextTitle = ds.contextTitle || 'Listing Entry';
        const contextInfo = ds.contextInfo || ''; 
        const message = ds.message || '';
        const listingImage = ds.contextImage || '';
        const requestStatus = (ds.status || 'pending').toLowerCase();

        // 2. Identify Condition
        const isActionable = requestStatus === 'pending' && 
                            (ds.type === 'LISTING' || subject.includes('listing') || subject.includes('inquiry'));

        const modal = document.getElementById('notification-master-modal');
        const body = document.getElementById('nt-modal-body');
        const title = document.getElementById('nt-modal-title');

        title.innerText = isActionable ? 'Inquiry Received' : 'Listing Detail';

        // 4. Content Generation
        body.innerHTML = `
            <div class="p-8">
                ${listingImage ? `
                    <div class="w-full h-48 rounded-3xl overflow-hidden mb-6 shadow-lg border border-gray-100 dark:border-white/5">
                        <img src="${listingImage}" class="w-full h-full object-cover">
                    </div>
                ` : ''}

                <div class="mb-6 pb-6 border-b border-gray-100 dark:border-white/5">
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-500 mb-1">Property / Asset</div>
                    <div class="text-lg font-black text-secondary-900 dark:text-white uppercase leading-tight">${contextTitle}</div>
                    <div class="flex items-center gap-1 text-[10px] text-gray-400 font-bold uppercase mt-1">
                        <span>${contextInfo}</span>
                    </div>
                </div>

                <div class="font-bold mb-2 text-[10px] uppercase tracking-widest text-gray-400">Sent By:</div>
                <div class="flex items-start gap-4 mb-6 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                    <div class="w-14 h-14 rounded-2xl bg-secondary-900 flex items-center justify-center overflow-hidden shrink-0 shadow-lg border border-white/5">
                        ${senderAvatar 
                            ? `<img src="${senderAvatar}" class="w-full h-full object-cover">` 
                            : `<span class="text-primary-400 text-xl font-black uppercase">${firstInitial}</span>`
                        }
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-secondary-900 dark:text-white uppercase">${senderName}</h4>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">${senderLocation}</p>
                        <div class="flex flex-wrap gap-1">
                            ${userTypes.map(t => `<span class="px-2 py-0.5 rounded-md bg-primary-500/10 border border-primary-500/20 text-primary-500 text-[9px] font-black uppercase">${t}</span>`).join('')}
                        </div>
                    </div>
                </div>

                <div class="mb-8 p-6 bg-gray-50 dark:bg-white/5 rounded-3xl border-l-4 ${isActionable ? 'border-primary-500' : 'border-gray-300'} text-gray-600 dark:text-gray-300 italic">
                    "${message}"
                </div>

                <div class="flex flex-wrap gap-3">
                    ${isActionable ? `
                        <button id="nt-accept-btn" class="px-10 py-4 text-white bg-green-600 hover:bg-green-700 font-black uppercase rounded-2xl transition-all shadow-xl shadow-green-600/20">Accept Inquiry</button>
                        <button id="nt-decline-btn" class="px-10 py-4 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 font-black uppercase rounded-2xl transition-all">Decline</button>
                    ` : `
                        <button class="close-notification-modal px-10 py-4 border-2 border-secondary-900 bg-secondary-700 text-white dark:border-white/20 font-black uppercase rounded-2xl hover:bg-secondary-900 transition-all">Close</button>
                        <a href="javascript:" id="nt-view-listing-btn" data-from-notification="true" 
                        class="view-listing-trigger inline-flex items-center justify-center gap-2 px-10 py-4 bg-primary-500 text-white font-black uppercase rounded-2xl hover:bg-black transition-all text-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>

                            <span>Open Listing</span>
                        </a>
                    `}
                </div>
            </div>
        `;

        const viewBtn = document.getElementById('nt-view-listing-btn');
        if (viewBtn) {
            Object.keys(ds).forEach(key => { viewBtn.dataset[key] = ds[key]; });

            viewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); // 💎 Stop bubbling right here
                
                this.closeNotificationModal(modal);

                const openEvent = new CustomEvent('force-open-listing', { 
                    detail: { dataset: viewBtn.dataset } 
                });
                document.dispatchEvent(openEvent);
            });

            initViewListing();
        }

        this.bindModal(modal);

        if (isActionable) {
            setTimeout(() => { ListingActions.init(targetId, noteId); }, 0); 
        }
    },

    closeNotificationModal(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    },

    bindModal(modal) {
        const closeModal = () => this.closeNotificationModal(modal);
        const escPress = (e) => { if (e.key === 'Escape') closeModal(); };

        modal.classList.remove('hidden');
        window.removeEventListener('keydown', escPress);
        window.addEventListener('keydown', escPress);
        modal.querySelectorAll('.close-notification-modal').forEach(btn => btn.onclick = closeModal);
    }
};