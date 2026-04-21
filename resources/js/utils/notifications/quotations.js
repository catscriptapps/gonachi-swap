// /resources/js/utils/notifications/quotations.js

import { QuotationActions } from './quotations-actions.js';
import { initViewQuotation } from '../quotations/view-quotation.js';

export const QuotationHandler = {
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

        const contextTitle = ds.contextTitle || 'Quotation';
        const contextInfo = ds.contextInfo || ''; 
        const message = ds.message || '';
        const requestStatus = (ds.status || 'pending').toLowerCase();
        
        const isActionable = requestStatus === 'pending' && 
                            (ds.type === 'QUOTATION' || subject.includes('quotation') || subject.includes('bid') || subject.includes('inquiry'));

        const modal = document.getElementById('notification-master-modal');
        const body = document.getElementById('nt-modal-body');
        const title = document.getElementById('nt-modal-title');

        title.innerText = isActionable ? 'Action Required' : 'Quotation Detail';

        // 4. Content Generation
        body.innerHTML = `
            <div class="p-8">
                <div class="mb-6 pb-6 border-b border-gray-100 dark:border-white/5">
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-500 mb-1">Quotation Project Reference</div>
                    <div class="text-sm font-bold text-secondary-900 dark:text-white uppercase">${contextTitle}</div>
                    ${contextInfo ? `<div class="text-[10px] text-gray-400 font-bold uppercase mt-1">${contextInfo}</div>` : ''}
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

                <div class="mb-8 p-6 bg-white dark:bg-secondary-900/40 rounded-3xl border-l-4 ${isActionable ? 'border-primary-500' : 'border-gray-300'} italic text-gray-600 dark:text-gray-300">
                    "${message}"
                </div>

                <div class="flex flex-wrap gap-3">
                    ${isActionable ? `
                        <button id="nt-accept-btn" class="px-10 py-4 text-white bg-green-600 hover:bg-green-700 font-black uppercase rounded-2xl transition-all shadow-xl shadow-green-600/20">Accept Quotation Bid</button>
                        <button id="nt-decline-btn" class="px-10 py-4 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 font-black uppercase rounded-2xl transition-all">Decline</button>
                    ` : `
                        <button class="close-notification-modal px-10 py-4 bg-secondary-700 text-white font-black uppercase rounded-2xl hover:bg-black transition-all">Dismiss</button>
                        <a href="javascript:" id="nt-view-full-btn" data-from-notification="true" 
                        class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-primary-500 text-white font-black uppercase rounded-2xl hover:opacity-90 transition-all text-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <span>View Full Quotation</span>
                        </a>
                    `}
                </div>
            </div>
        `;

        const viewBtn = document.getElementById('nt-view-full-btn');
        if (viewBtn) {
            Object.keys(ds).forEach(key => { viewBtn.dataset[key] = ds[key]; });

            viewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.closeNotificationModal(modal);

                const openEvent = new CustomEvent('force-open-quote', { 
                    detail: { dataset: viewBtn.dataset } 
                });
                document.dispatchEvent(openEvent);
            });

            initViewQuotation();
        }

        this.bindModal(modal);

        if (isActionable) {
            setTimeout(() => { QuotationActions.init(targetId, noteId); }, 0); 
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