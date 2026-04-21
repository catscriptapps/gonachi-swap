// /resources/js/utils/notifications/adverts.js

import { initViewAdvert } from '../adverts/view-advert.js';

/**
 * AdvertHandler 📢
 * Manages the display and interaction logic for Advert-type notifications.
 */
export const AdvertHandler = {
    async process(data) {
        const { noteId, targetId, element } = data;
        const ds = element.dataset;

        // 1. Basic Notification Data
        const senderName = 'Gonachi Team'; // Default sender name for system-generated adverts
        const firstInitial = 'G';
        const senderAvatar = '';
        const senderLocation = ds.senderLocation || 'N/A';
        
        // 2. Advert Specific Context
        const contextTitle = ds.contextTitle || 'Advert Update';
        const contextInfo = ds.contextInfo || ''; 
        const message = ds.message || '';
        const status = (ds.status || 'pending').toLowerCase();
        let userTypes = ['Admin'];

        const modal = document.getElementById('notification-master-modal');
        const body = document.getElementById('nt-modal-body');
        const title = document.getElementById('nt-modal-title');

        // Set Modal Header
        title.innerText = 'Advert Notification';

        // 3. Content Generation
        body.innerHTML = `
            <div class="p-8">
                <div class="mb-6 pb-6 border-b border-gray-100 dark:border-white/5">
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-500 mb-1">Advert Reference</div>
                    <div class="text-sm font-bold text-secondary-900 dark:text-white uppercase">${contextTitle}</div>
                    ${contextInfo ? `<div class="text-[10px] text-gray-400 font-bold uppercase mt-1">${contextInfo}</div>` : ''}
                </div>

                <div class="font-bold mb-2 text-[10px] uppercase tracking-widest text-gray-400">Processed By:</div>
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

                <div class="mb-8 p-6 bg-white dark:bg-secondary-900/40 rounded-3xl border-l-4 border-primary-500 italic text-gray-600 dark:text-gray-300">
                    "${message}"
                </div>

                <div class="flex flex-wrap gap-3">
                    <button class="close-notification-modal px-10 py-4 bg-secondary-700 text-white font-black uppercase rounded-2xl hover:bg-black transition-all">Dismiss</button>
                    
                    <a href="javascript:" id="nt-view-full-btn" data-from-notification="true" 
                    class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-primary-500 text-white font-black uppercase rounded-2xl hover:opacity-90 transition-all text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span>View Full Advert</span>
                    </a>
                </div>
            </div>
        `;

        // 4. Bind "View Full" Logic
        const viewBtn = document.getElementById('nt-view-full-btn');
        if (viewBtn) {
            // Transfer all dataset attributes from the notification item to the button
            Object.keys(ds).forEach(key => { viewBtn.dataset[key] = ds[key]; });

            viewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                this.closeNotificationModal(modal);

                // Dispatch event to open the specific Advert View (Modal or Page)
                const openEvent = new CustomEvent('force-open-advert', { 
                    detail: { dataset: viewBtn.dataset } 
                });
                document.dispatchEvent(openEvent);
            });

            // Initialize the Advert View controller logic
            initViewAdvert();
        }

        this.bindModal(modal);
    },

    closeNotificationModal(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    },

    bindModal(modal) {
        const closeModal = () => this.closeNotificationModal(modal);
        const escPress = (e) => { if (e.key === 'Escape') closeModal(); };
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Lock background scroll

        window.removeEventListener('keydown', escPress); 
        window.addEventListener('keydown', escPress);
        
        modal.querySelectorAll('.close-notification-modal').forEach(btn => btn.onclick = closeModal);
    }
};