// /resources/js/utils/listings/deactivate-listing.js

import { showToast } from "../../ui/toast";

/**
 * Handles Inline Actions (Deactivate/Reactivate) - Gonachi Style 💎
 */
export function initListingActions() {
    if (window._listingActionsListenerAttached) return;

    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('.deactivate-listing-trigger, .reactivate-listing-trigger');
        if (!trigger) return;

        e.preventDefault();

        // 💎 THE FIX: Determine intent based on text if classes are stale
        // If the button says "Reactivate", the intent is reactivate. 
        // Otherwise, it's deactivate.
        const btnText = trigger.innerText.toLowerCase();
        const isReactivate = trigger.classList.contains('reactivate-listing-trigger') || btnText.includes('reactivate');
        
        const intent = isReactivate ? 'reactivate' : 'deactivate';
        
        const listingId = trigger.dataset.encodedId || trigger.dataset.id;

        if (trigger.dataset.confirming === 'true') {
            const isYes = e.target.closest('.confirm-yes');
            const isNo = e.target.closest('.confirm-no');

            if (isYes) {
                // IMPORTANT: We re-calculate intent here or pass it from the initial click
                // because the button classes might have changed during "Confirm Mode"
                const activeIntent = trigger.dataset.intent; 
                await performListingAction(trigger, listingId, activeIntent);
            } else if (isNo) {
                resetListingActionButton(trigger);
            }
            return;
        }

        // Store the intent on the dataset so it survives the "Confirm Mode" HTML swap
        trigger.dataset.intent = intent;
        enterActionConfirmMode(trigger, intent);
    });

    window._listingActionsListenerAttached = true;
}

function enterActionConfirmMode(btn, intent) {
    btn.dataset.originalHtml = btn.innerHTML;
    btn.dataset.originalClasses = btn.className;
    btn.dataset.confirming = 'true';

    // 💎 Dynamic labeling based on intent
    const label = intent === 'reactivate' ? 'Reactivate?' : 'End Listing?';
    const confirmColor = intent === 'reactivate' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700';
    
    // Remove all possible "danger" or "success" colors to prevent that pink hover clash
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

async function performListingAction(btn, id, intent) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    
    // UI Feedback
    btn.innerHTML = `<div class="flex justify-center w-full"><svg class="animate-spin h-3 w-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;
    btn.disabled = true;

    try {
        const response = await fetch(`${baseUrl}api/listings`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                id: id, 
                intent: intent, // 💎 Strictly sending 'reactivate' or 'deactivate'
                _method: 'POST' 
            }),
        });

        const result = await response.json();

        if (result.success && result.cardHtml) {
            // 1. Try to find the card by traversing up (if clicked directly on the grid)
            let cardToReplace = btn.closest('.listing-card-wrapper');

            // 2. If not found (clicked from modal), find the card in the background grid
            if (!cardToReplace) {
                // We search the entire document for a card wrapper matching this ID
                // We check both data-id and data-encoded-id to be safe
                cardToReplace = document.querySelector(
                    `.listing-card-wrapper[data-encoded-id="${id}"], .listing-card-wrapper[data-id="${id}"]`
                );
            }

            // 3. Perform the swap
            if (cardToReplace) {
                cardToReplace.outerHTML = result.cardHtml;
                
                // Optional: Add a brief highlight effect so the user sees which one changed
                const newCard = document.querySelector(`.listing-card-wrapper[data-encoded-id="${id}"]`);
                if (newCard) {
                    newCard.classList.add('ring-2', 'ring-primary-400', 'ring-offset-2');
                    setTimeout(() => newCard.classList.remove('ring-2', 'ring-primary-400', 'ring-offset-2'), 2000);
                }
            } else {
                console.warn(`Could not find grid card for ID: ${id} to update UI.`);
            }

            // 4. Close the modal
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
    if (!btn) return;

    // 1. If we have saved state, restore it
    if (btn.dataset.originalHtml) {
        btn.innerHTML = btn.dataset.originalHtml;
    }
    if (btn.dataset.originalClasses) {
        btn.className = btn.dataset.originalClasses;
    }

    // 2. 💎 THE FIX: Delete the "time capsule" data
    // This ensures that the NEXT time the modal opens, 
    // it doesn't try to restore stale text/classes.
    delete btn.dataset.originalHtml;
    delete btn.dataset.originalClasses;
    delete btn.dataset.confirming;
    delete btn.dataset.intent;
    
    btn.disabled = false;
}