// /resources/js/utils/listings/listing-actions.js

import { showToast } from "../../ui/toast";

/**
 * Handles Marketplace Listing Actions (Deactivate/Reactivate) - Gonachi Swap Style 💎
 */
export function initListingActions() {
    if (window._listingActionsListenerAttached) return;

    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('.deactivate-listing-trigger, .reactivate-listing-trigger');
        if (!trigger) return;

        e.preventDefault();

        const isReactivate = trigger.classList.contains('reactivate-listing-trigger');
        const intent = isReactivate ? 'reactivate' : 'deactivate';

        const listingId = trigger.dataset.encodedId || trigger.dataset.id;

        if (trigger.dataset.confirming === 'true') {
            const isYes = e.target.closest('.confirm-yes');
            const isNo = e.target.closest('.confirm-no');

            if (isYes) {
                const activeIntent = trigger.dataset.intent;
                await performListingAction(trigger, listingId, activeIntent);
            } else if (isNo) {
                resetListingActionButton(trigger);
            }
            return;
        }

        trigger.dataset.intent = intent;
        enterListingConfirmMode(trigger, intent);
    });

    window._listingActionsListenerAttached = true;
}

function enterListingConfirmMode(btn, intent) {
    btn.dataset.originalHtml = btn.innerHTML;
    btn.dataset.originalClasses = btn.className;
    btn.dataset.confirming = 'true';

    const label = intent === 'reactivate' ? 'Reactivate Swap?' : 'End Listing?';
    const confirmColor = intent === 'reactivate' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700';

    btn.classList.remove('bg-gray-100', 'dark:bg-white/5', 'text-secondary-900', 'dark:text-white', 'hover:bg-red-500', 'hover:bg-green-500');
    btn.classList.add('bg-secondary-950', 'text-white', 'hover:bg-secondary-950', 'cursor-default');

    btn.innerHTML = `
        <div class="flex items-center justify-between w-full px-1">
            <span class="mr-3 italic text-[10px] font-black uppercase tracking-widest text-white whitespace-nowrap">${label}</span>
            <div class="flex gap-1.5">
                <button type="button" class="confirm-yes px-3 py-1.5 ${confirmColor} text-white rounded-xl transition-all uppercase text-[10px] font-black border-none">Yes</button>
                <button type="button" class="confirm-no px-3 py-1.5 bg-slate-700 text-white rounded-xl hover:bg-slate-600 transition-all uppercase text-[10px] font-black border-none">No</button>
            </div>
        </div>
    `;
}

async function performListingAction(btn, id, intent) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';

    btn.innerHTML = `<div class="flex justify-center w-full"><svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;
    btn.disabled = true;

    try {
        const response = await fetch(`${baseUrl}api/listings`, {
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
            let cardToReplace = btn.closest('[id^="listing-card-"]');

            // 2. Find card in background grid if from modal
            if (!cardToReplace) {
                cardToReplace = document.querySelector(
                    `[data-encoded-id="${id}"], #listing-card-${id}`
                );
            }

            // 3. Swap the HTML
            if (cardToReplace) {
                cardToReplace.outerHTML = result.cardHtml;
            }

            // 4. Close the modal (if open)
            const modal = document.getElementById('view-listing-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        } else {
            showToast(result.messages?.[0] || 'Operation failed', 'error');
            resetListingActionButton(btn);
        }
    } catch (error) {
        console.error('Action failed:', error);
        resetListingActionButton(btn);
    }
}

export function resetListingActionButton(btn) {
    if (!btn || !btn.dataset.originalHtml) return;

    btn.innerHTML = btn.dataset.originalHtml;
    btn.className = btn.dataset.originalClasses;
    delete btn.dataset.confirming;
    delete btn.dataset.intent;
    btn.disabled = false;
}