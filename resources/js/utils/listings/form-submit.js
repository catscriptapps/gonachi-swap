// /resources/js/utils/listings/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { AnimationEngine } from '../../utils/animations.js';

/**
 * Extract and format payload from listing form
 */
function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    return {
        encoded_id: form.dataset.encodedId || null,
        listing_title: data.listing_title?.trim(),
        listing_description: data.listing_description?.trim(),
        category_id: parseInt(data.category_id || 0),
        type_id: parseInt(data.type_id || 0),
        condition_id: parseInt(data.condition_id || 0),
        price: data.price ? parseFloat(data.price) : null,
        trade_pref: data.trade_pref?.trim(),
        city: data.city?.trim(),
        region_id: parseInt(data.region_id || 0),
        country_id: parseInt(data.country_id || 0),
        youtube_url: data.youtube_url?.trim(),
        contact_phone: data.contact_phone?.trim()
    };
}

/**
 * Handles listing form submission for both Add and Edit modes
 */
export function handleListingFormSubmission(form, mode, modalInstance, gridSelector = '#listings-grid') {
    if (form._listingFormListenerAttached) return;
    form._listingFormListenerAttached = true;

    const validator = new FormValidator(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    let apiMsg = form.querySelector('.api-message');

    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300';
        form.appendChild(apiMsg);
    }

    const originalLabel = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        apiMsg.innerHTML = '';

        if (!validator.validateForEmptyFields(e)) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner;

        try {
            const payload = getPayload(form);
            if (mode === 'edit') payload._method = 'PUT';

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/listings`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success && result.cardHtml) {
                const grid = document.querySelector(gridSelector);

                if (mode === 'edit') {
                    // 1. Update existing card in the grid
                    const existingCard = document.querySelector(`.listing-card-wrapper[data-encoded-id="${payload.encoded_id}"]`);
                    if (existingCard) {
                        existingCard.outerHTML = result.cardHtml;
                    }
                } else {
                    // 1. Remove empty state if it exists
                    const emptyState = document.getElementById('empty-listings-state');
                    if (emptyState) emptyState.remove();

                    if (grid) {
                        grid.classList.remove('hidden');
                        grid.insertAdjacentHTML('afterbegin', result.cardHtml);
                    }
                }

                // 2. RE-INITIALIZE LISTENERS
                // Dispatches event so page controllers can re-bind edit/delete triggers
                document.dispatchEvent(new CustomEvent('listing:updated', {
                    detail: { mode, encodedId: payload.encoded_id }
                }));

                if (typeof AnimationEngine !== 'undefined') AnimationEngine.refresh();

                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ${result.messages?.[0] || 'Listing saved successfully.'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden';
                setTimeout(() => { if (modalInstance) modalInstance.close(); }, 1200);

            } else {
                throw new Error(result.messages?.[0] || 'Save failed');
            }
        } catch (err) {
            console.error('Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2 uppercase text-center">${err.message}</div>`;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalLabel;
        }
    });
}