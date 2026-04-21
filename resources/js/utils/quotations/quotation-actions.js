// /resources/js/utils/quotations/quotation-actions.js

import { showToast } from "../../ui/toast";

/**
 * Handles Inline Actions (Deactivate/Reactivate) - Gonachi Style 💎
 */
export function initQuotationActions() {
    if (window._quotationActionsListenerAttached) return;

    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('.deactivate-quotation-trigger, .reactivate-quotation-trigger');
        if (!trigger) return;

        e.preventDefault();

        const isReactivate = trigger.classList.contains('reactivate-quotation-trigger');
        const intent = isReactivate ? 'reactivate' : 'deactivate';
        
        const quotationId = trigger.dataset.encodedId || trigger.dataset.id;

        if (trigger.dataset.confirming === 'true') {
            const isYes = e.target.closest('.confirm-yes');
            const isNo = e.target.closest('.confirm-no');

            if (isYes) {
                const activeIntent = trigger.dataset.intent; 
                await performQuotationAction(trigger, quotationId, activeIntent);
            } else if (isNo) {
                resetQuotationActionButton(trigger);
            }
            return;
        }

        trigger.dataset.intent = intent;
        enterQuotationConfirmMode(trigger, intent);
    });

    window._quotationActionsListenerAttached = true;
}

function enterQuotationConfirmMode(btn, intent) {
    btn.dataset.originalHtml = btn.innerHTML;
    btn.dataset.originalClasses = btn.className;
    btn.dataset.confirming = 'true';

    const label = intent === 'reactivate' ? 'Reactivate?' : 'Deactivate?';
    const confirmColor = intent === 'reactivate' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700';
    
    btn.classList.remove('bg-red-500/10', 'text-red-600', 'bg-red-500', 'hover:bg-red-600', 'text-green-600', 'bg-green-500/10');
    btn.classList.add('bg-secondary-900', 'text-white', 'hover:bg-secondary-900', 'cursor-default');

    btn.innerHTML = `
        <div class="flex items-center justify-between w-full px-1">
            <span class="mr-3 italic text-[11px] font-medium tracking-tight text-white whitespace-nowrap">${label}</span>
            <div class="flex gap-1.5">
                <button type="button" class="confirm-yes px-2.5 py-1 ${confirmColor} text-white rounded transition-colors uppercase text-[10px] font-bold border-none">Yes</button>
                <button type="button" class="confirm-no px-2.5 py-1 bg-slate-700 text-white rounded hover:bg-slate-600 transition-colors uppercase text-[10px] font-bold border-none">No</button>
            </div>
        </div>
    `;
}

async function performQuotationAction(btn, id, intent) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    
    btn.innerHTML = `<div class="flex justify-center w-full"><svg class="animate-spin h-3 w-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;
    btn.disabled = true;

    try {
        const response = await fetch(`${baseUrl}api/quotations`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                id: id, 
                intent: intent, 
                _method: 'POST' 
            }),
        });

        const result = await response.json();

        if (result.success && result.cardHtml) {
            // 1. Find card by traversing up
            let cardToReplace = btn.closest('.quotation-card-wrapper');

            // 2. Find card in background grid if from modal
            if (!cardToReplace) {
                cardToReplace = document.querySelector(
                    `.quotation-card-wrapper[data-encoded-id="${id}"], .quotation-card-wrapper[data-id="${id}"]`
                );
            }

            // 3. Swap the HTML
            if (cardToReplace) {
                cardToReplace.outerHTML = result.cardHtml;
                
                const newCard = document.querySelector(`.quotation-card-wrapper[data-encoded-id="${id}"]`);
                if (newCard) {
                    newCard.classList.add('ring-2', 'ring-primary-400', 'ring-offset-2');
                    setTimeout(() => newCard.classList.remove('ring-2', 'ring-primary-400', 'ring-offset-2'), 2000);
                }
            } else {
                console.warn(`Could not find grid card for ID: ${id}`);
            }

            // 4. Close the modal
            const modal = document.getElementById('view-quotation-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        } else {
            showToast(result.messages?.[0] || 'Operation failed', 'error');
            resetQuotationActionButton(btn);
        }
    } catch (error) {
        console.error('Action failed:', error);
        resetQuotationActionButton(btn);
    }
}

export function resetQuotationActionButton(btn) {
    if (!btn || !btn.dataset.originalHtml) return; // Safety check
    
    btn.innerHTML = btn.dataset.originalHtml;
    btn.className = btn.dataset.originalClasses;
    delete btn.dataset.confirming;
    delete btn.dataset.intent;
    btn.disabled = false;
}