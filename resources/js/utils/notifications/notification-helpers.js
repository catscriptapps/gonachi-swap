// /resources/js/utils/notifications/notification-helpers.js

export const BaseNotificationActions = {
    // Utility to handle the "Confirm?" double-click pattern
    confirmStep(btn, type, successCallback) {
        const originalText = btn.innerHTML;
        btn.innerHTML = `Confirm ${type}?`;
        btn.classList.add('ring-4', type.toLowerCase().includes('accept') || type.toLowerCase().includes('send') ? 'ring-primary-500/30' : 'ring-red-500/30');
        
        const timeout = setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('ring-4', 'ring-primary-500/30', 'ring-red-500/30');
            btn.onclick = () => this.confirmStep(btn, type, successCallback);
        }, 3000);

        btn.onclick = () => {
            clearTimeout(timeout);
            successCallback();
        };
    },

    // Generates the standard response textarea UI
    renderResponseArea(container, type, onBack, onSubmit) {
        container.innerHTML = `
            <div class="animate-in fade-in slide-in-from-bottom-4 duration-300">
                <div class="flex items-center justify-between mb-4">
                    <button id="nt-back-btn" class="text-[10px] font-black uppercase tracking-widest text-primary-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/></svg> Back
                    </button>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">${type}</span>
                </div>
                <textarea id="nt-response-text" placeholder="Type your message..." class="w-full h-32 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 focus:border-primary-500 outline-none text-sm transition-all resize-none shadow-inner"></textarea>
                <p id="nt-val-msg" class="hidden text-[10px] font-bold text-red-500 mt-2 uppercase">* Message required</p>
                <button id="nt-final-submit" class="w-full mt-4 py-4 bg-secondary-900 text-white font-black uppercase rounded-2xl hover:bg-black transition-all shadow-lg">Confirm & Send</button>
            </div>
        `;
        
        document.getElementById('nt-back-btn').onclick = onBack;
        document.getElementById('nt-final-submit').onclick = onSubmit;
        setTimeout(() => document.getElementById('nt-response-text')?.focus(), 50);
    },

    // Standard API submission logic
    async apiSubmit(endpoint, payload, submitBtn, onComplete) {
        const message = document.getElementById('nt-response-text')?.value.trim();
        if (!message) {
            document.getElementById('nt-response-text')?.classList.add('border-red-500', 'animate-shake');
            document.getElementById('nt-val-msg')?.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="animate-spin inline-block w-4 h-4 border-2 border-white/20 border-t-white rounded-full"></span>`;

        try {
            const response = await fetch(`${window.APP_CONFIG.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ...payload, message })
            });

            const result = await response.json();
            if (result.success) {
                submitBtn.innerHTML = `Sent!`;
                submitBtn.classList.replace('bg-secondary-900', 'bg-emerald-500');
                setTimeout(() => {
                    document.getElementById('notification-master-modal')?.classList.add('hidden');
                    if (typeof window.loadPartial === 'function') window.loadPartial(`${window.APP_CONFIG.baseUrl}notifications`);
                }, 1500);
            }
        } catch (err) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `Error - Try Again`;
        }
    }
};