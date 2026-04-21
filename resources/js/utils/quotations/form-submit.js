// /resources/js/utils/quotations/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { AnimationEngine } from '../../utils/animations.js';

function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    return {
        encoded_id: form.dataset.encodedId || null,
        quotation_title: data.quotation_title?.trim(),
        description_of_work_to_be_done: data.description_of_work_to_be_done?.trim(),
        country_id: parseInt(data.countryId || 0),
        region_id: parseInt(data.regionId || 0),
        city: data.city?.trim(),
        contractor_type_id: parseInt(data.contractor_type_id || 0),
        skilled_trade_id: parseInt(data.skilled_trade_id || 0),
        unit_type_id: parseInt(data.unit_type_id || 0),
        house_type_id: data.unit_type_id == '5' ? parseInt(data.house_type_id || 0) : null,
        start_date: data.start_date || null,
        finish_date: data.finish_date || null,
        start_time: data.start_time || null,
        finish_time: data.finish_time || null,
        quotation_budget: data.quotation_budget?.trim(),
        quotation_type_id: parseInt(data.quotation_type_id || 0),
        quotation_dest_id: parseInt(data.quotation_dest_id || 0),
        youtube_url: data.youtube_url?.trim(),
        contact_phone: data.contact_phone?.trim(),
        notify: data.notify || 'no'
    };
}

export function handleQuoteFormSubmission(form, mode, modalInstance, gridSelector = '#quotes-grid') {
    if (form._quoteFormListenerAttached) return;
    form._quoteFormListenerAttached = true;

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

        // Custom Gonachi Logic: House Type validation
        const unitType = form.querySelector('[name="unit_type_id"]')?.value;
        const houseType = form.querySelector('[name="house_type_id"]')?.value;
        if (unitType == '5' && !houseType) {
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-xs mt-2 uppercase">Please select a House Type.</div>`;
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 

        try {
            const payload = getPayload(form);
            if (mode === 'edit') payload._method = 'PUT';

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/quotations`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success && result.cardHtml) {
                const grid = document.querySelector(gridSelector);
                
                if (mode === 'edit') {
                    // 1. UPDATE EXISTING CARD
                    const existingCard = document.querySelector(`.quotation-card-wrapper[data-encoded-id="${payload.encoded_id}"]`);
                    if (existingCard) {
                        existingCard.outerHTML = result.cardHtml;
                    }
                } else {
                    // 1. FIX: Added # to target the ID correctly
                    const emptyState = document.getElementById('empty-quotes-state');
                    
                    // 2. FIX: Target the correct ID from your PHP (quotes-counter-number)
                    const counter = document.getElementById('quotes-counter-number');
                    const grid = document.querySelector(gridSelector);

                    // Remove the empty state message
                    if (emptyState) {
                        emptyState.remove();
                    }
                    
                    if (grid) {
                        // Ensure grid is visible (un-hides what the delete script hid)
                        grid.classList.remove('hidden');
                        grid.insertAdjacentHTML('afterbegin', result.cardHtml);
                    }

                    // Update the number using the correct ID
                    if (counter) {
                        const currentCount = parseInt(counter.textContent.trim()) || 0;
                        counter.textContent = currentCount + 1;
                    }
                }

                // 3. RE-INITIALIZE LISTENERS (The "Magic" step)
                // We dispatch a custom event so the main page knows to re-bind Edit/Delete buttons
                document.dispatchEvent(new CustomEvent('quotation:updated', { 
                    detail: { mode, encodedId: payload.encoded_id } 
                }));

                if (typeof AnimationEngine !== 'undefined') AnimationEngine.refresh();

                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill text-xl"></i>
                        ${result.messages?.[0] || 'Saved successfully.'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden'; 

                setTimeout(() => {
                    if (modalInstance) modalInstance.close();
                }, 1000);

            } else {
                throw new Error(result.messages?.[0] || 'Save failed');
            }

        } catch (err) {
            console.error('Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">${err.message}</div>`;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalLabel;
        }
    });
}