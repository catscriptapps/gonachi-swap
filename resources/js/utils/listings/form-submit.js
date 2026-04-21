// /resources/js/utils/listings/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { AnimationEngine } from '../../utils/animations.js';
import { showToast } from '../../ui/toast.js';

function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    const catId = parseInt(data.category_id || 0);
    
    const selectedAmenities = Array.from(form.querySelectorAll('input[name="amenities[]"]:checked'))
        .map(el => parseInt(el.value));

    const isChecked = (name) => form.querySelector(`[name="${name}"]`)?.checked ? 1 : 0;

    return {
        encoded_id: form.dataset.encodedId || null,
        listing_title: data.listing_title?.trim(),
        listing_description: data.listing_description?.trim(),
        category_id: catId,
        
        // --- FIXED: Nullify hidden fields based on Category ---
        category_type_id: catId === 3 ? null : parseInt(data.category_type_id || 0),
        
        unit_type_id: (catId === 2 || catId === 3) ? null : parseInt(data.unit_type_id || 0),
        house_type_id: (data.unit_type_id == '5' && catId !== 2 && catId !== 3) ? parseInt(data.house_type_id || 0) : null,
        
        bedroom_id: (catId === 2 || catId === 3) ? 0 : parseInt(data.bedroom_id || 0),
        bathroom_id: (catId === 2 || catId === 3) ? 0 : parseFloat(data.bathroom_id || 0),
        
        country_id: parseInt(data.countryId || 0), 
        region_id: parseInt(data.regionId || 0),
        city: data.city?.trim(),
        address: data.address?.trim(),
        
        // --- FIXED: Nullify financial hidden fields ---
        agreement_type_id: (catId === 2 || catId === 3) ? null : parseInt(data.agreement_type_id || 0),
        price: (catId === 2 || catId === 3) ? "0" : data.price?.trim(),
        move_in_date: (catId === 2 || catId === 3) ? null : data.move_in_date || null,
        
        property_size: data.property_size?.trim(),
        youtube_url: data.youtube_url?.trim(),
        contact_phone: data.contact_phone?.trim(),
        
        is_ac: isChecked('is_ac'),
        is_furnished: isChecked('is_furnished'),
        parking: isChecked('parking'),
        pets_allowed: isChecked('pets_allowed'),
        amenities: (catId === 2 || catId === 3) ? [] : selectedAmenities
    };
}

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

        // --- NEW: Toggle 'required' based on visibility ---
        // This prevents the validator (and browser) from caring about hidden fields
        const allInputs = form.querySelectorAll('input, select, textarea');
        allInputs.forEach(el => {
            // If the element or any of its parents are hidden, it shouldn't be required
            if (el.closest('.hidden')) {
                if (el.hasAttribute('required')) {
                    el.dataset.wasRequired = "true"; // Remember it was required
                    el.removeAttribute('required');
                }
            } else {
                // Restore required status if it was previously removed
                if (el.dataset.wasRequired === "true") {
                    el.setAttribute('required', 'required');
                    delete el.dataset.wasRequired;
                }
            }
        });

        // Now run the validator
        if (!validator.validateForEmptyFields(e)){
            showToast('* All required fields MUST be provided.', 'error');
            return;
        }

        // Validation for House Style
        const unitType = form.querySelector('[name="unit_type_id"]')?.value;
        const houseType = form.querySelector('[name="house_type_id"]')?.value;
        if (unitType == '5' && !houseType) {
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-xs mt-2 uppercase">Please select a House Style.</div>`;
            showToast('* All required fields MUST be provided.', 'error');
            return;
        }

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
                    const existingCard = document.querySelector(`.listing-card-wrapper[data-encoded-id="${payload.encoded_id}"]`);
                    if (existingCard) {
                        existingCard.outerHTML = result.cardHtml;
                    }
                } else {
                    const emptyState = document.getElementById('empty-listings-state');
                    const counter = document.getElementById('listings-counter-number');

                    if (emptyState) emptyState.remove();
                    
                    if (grid) {
                        grid.classList.remove('hidden');
                        grid.insertAdjacentHTML('afterbegin', result.cardHtml);
                    }

                    if (counter) {
                        const currentCount = parseInt(counter.textContent.trim()) || 0;
                        counter.textContent = currentCount + 1;
                    }
                }

                document.dispatchEvent(new CustomEvent('listing:updated', { 
                    detail: { mode, encodedId: payload.encoded_id } 
                }));

                if (typeof AnimationEngine !== 'undefined') AnimationEngine.refresh();

                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill text-xl"></i>
                        ${result.messages?.[0] || 'Listing saved successfully.'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden'; 

                setTimeout(() => {
                    if (modalInstance) modalInstance.close();
                }, 1000);

            } else {
                throw new Error(result.messages?.[0] || 'Failed to save listing');
            }

        } catch (err) {
            console.error('Listing Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">${err.message}</div>`;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalLabel;
        }
    });
}