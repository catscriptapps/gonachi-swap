// /resources/js/utils/notifications/system.js

export const SystemHandler = {
    async process(data) {
        const { element } = data;
        const ds = element.dataset;

        // 1. Data Prep 💎
        const subject = ds.subject || 'System Notification';
        const message = ds.message || 'No message content available.';
        
        // Pull context data passed from Controller/View
        const contextTitle = ds.contextTitle || '';
        const contextInfo = ds.contextInfo || '';

        // 2. Identify Mentorship Context via Subject 🔍
        const isHandshake = subject.includes('Handshake Accepted') || subject.includes('Handshake Declined');

        // 3. Select Modal Elements
        const modal = document.getElementById('notification-master-modal');
        const body = document.getElementById('nt-modal-body');
        const title = document.getElementById('nt-modal-title');

        // 4. Set Title & Content
        title.innerText = isHandshake ? 'Handshake Update' : 'System Update';
        
        // Construct Context Section if data exists 🏠🤝💰
        const contextSection = contextTitle ? `
            <div class="mb-6 p-5 bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-[9px] font-black text-primary-500 uppercase tracking-[0.2em] mb-1">Related Record</p>
                        <h5 class="text-sm font-black text-secondary-900 dark:text-white uppercase truncate">${contextTitle}</h5>
                        ${contextInfo ? `<p class="text-[10px] font-medium text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            ${contextInfo}
                        </p>` : ''}
                    </div>
                </div>
            </div>
        ` : '';

        body.innerHTML = `
            <div class="p-8">
                <div class="flex items-center gap-4 mb-6 p-4 bg-primary-500/5 rounded-2xl border border-primary-500/10">
                    <div class="w-12 h-12 rounded-xl bg-primary-500 flex items-center justify-center shrink-0 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-secondary-900 dark:text-white uppercase tracking-tight">${subject}</h4>
                        <p class="text-[10px] font-bold text-primary-500 uppercase tracking-widest">Official Record</p>
                    </div>
                </div>

                ${contextSection}

                <div class="mb-8 p-6 bg-gray-50 dark:bg-secondary-900/40 rounded-3xl border-l-4 border-gray-300 italic text-gray-600 dark:text-gray-400">
                    "${message}"
                </div>

                <div class="flex justify-end">
                    <button class="close-notification-modal px-10 py-4 bg-secondary-900 text-white font-black uppercase rounded-2xl hover:bg-black transition-all">Close Entry</button>
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

        // 6. Open Modal & Bind Close Events
        modal.classList.remove('hidden');
        window.addEventListener('keydown', escPress);

        modal.querySelectorAll('.close-notification-modal').forEach(btn => {
            btn.onclick = closeModal;
        });
    }
};

export default SystemHandler;