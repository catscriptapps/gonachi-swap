// /resources/js/utils/notifications/mentors.js

import { MentorActions } from './mentors-actions.js';

export const MentorHandler = {
    async process(data) {
        const { noteId, targetId, element } = data;
        const ds = element.dataset;

        // 1. Data Prep - Using generalized context 💎
        const senderName = ds.senderName || 'System';
        const firstInitial = senderName.charAt(0).toUpperCase();
        const senderAvatar = ds.senderAvatar || '';
        const senderLocation = ds.senderLocation || 'N/A';
        const subject = (ds.subject || '').toLowerCase(); 
        
        // Generalized Context (Works for Mentors, Quotes, and Listings)
        const contextTitle = ds.contextTitle || 'Profile';
        const contextInfo = ds.contextInfo || '';
        const receiverName = ds.receiverName || 'You';
        const targetUserType = ds.targetUserType || 'Expert';

        const requestStatus = (ds.status || 'pending').toLowerCase();
        const message = ds.message || '';
        
        let userTypes = [];
        try { userTypes = JSON.parse(ds.senderUserTypes || '[]'); } catch(e) {}

        // 2. Identify Condition
        // Mentorships use "request", Quotes/Listings might just be "details"
        const isRequest = subject.includes('request');
        const isActionable = isRequest && requestStatus === 'pending' && ds.type === 'MENTOR';

        const modal = document.getElementById('notification-master-modal');
        const body = document.getElementById('nt-modal-body');
        const title = document.getElementById('nt-modal-title');

        // 3. Set Title
        title.innerText = isActionable ? 'Action Required' : 'Notification Detail';

        // 4. Content Generation
        body.innerHTML = `
            <div class="p-8">
                <div class="mb-6 pb-6 border-b border-gray-100 dark:border-white/5">
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-500 mb-1">Target Context</div>
                    <div class="text-sm font-bold text-secondary-900 dark:text-white">
                        ${receiverName} <span class="text-gray-400 font-medium">for </span> ${contextTitle} 
                        <span class="text-gray-400 font-medium"></span>
                    </div>
                    ${contextInfo ? `
                        <div class="flex items-center gap-1 text-[10px] text-gray-400 font-bold uppercase mt-1">
                            <svg class="w-3 h-3 text-primary-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>${contextInfo}</span>
                        </div>` : ''}
                </div>

                <div class="font-bold mb-2 text-[10px] uppercase tracking-widest text-gray-400">From:</div>
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
                        <button id="nt-accept-btn" class="px-10 py-4 text-white bg-green-600 hover:bg-green-700 font-black uppercase rounded-2xl transition-all shadow-xl shadow-green-600/20">Accept Mentor Handshake</button>
                        <button id="nt-decline-btn" class="px-10 py-4 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 font-black uppercase rounded-2xl transition-all">Decline Mentor Handshake</button>
                    ` : `
                        <button class="close-notification-modal px-10 py-4 bg-secondary-900 text-white font-black uppercase rounded-2xl hover:bg-black transition-all">Close Entry</button>
                    `}
                </div>
            </div>
        `;

        // 5. Modal Utility Functions
        const closeModal = () => {
            modal.classList.add('hidden');
            window.removeEventListener('keydown', escPress);
        };

        const escPress = (e) => {
            if (e.key === 'Escape') closeModal();
        };

        // 6. Open Modal & Bind Basic Events
        modal.classList.remove('hidden');
        window.addEventListener('keydown', escPress);

        modal.querySelectorAll('.close-notification-modal').forEach(btn => {
            btn.onclick = closeModal;
        });

        // 7. Initialize Actions ONLY if actionable (specifically for Mentors)
        if (isActionable) {
            MentorActions.init(targetId, noteId);
        }
    }
};