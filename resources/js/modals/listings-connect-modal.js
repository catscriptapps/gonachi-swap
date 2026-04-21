// /resources/js/modals/listings-connect-modal.js

import { Modal } from '../factories/modal-factory.js';
import { listingConnectForm } from '../forms/listing-connect-form.js';
import { FormValidator } from '../utils/form-validator.js';
import { buttonSpinner } from '../utils/spinner-utils.js';

let listingConnectModal = null;

/**
 * Handles the Inquiry submission with full validation and API feedback 💎
 */
async function handleInquirySubmission(form, modalInstance) {
    if (form._listingFormListenerAttached) return;
    form._listingFormListenerAttached = true;

    const validator = new FormValidator(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Inject or find API message container
    let apiMsg = form.querySelector('.api-message');
    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300 text-center';
        form.appendChild(apiMsg);
    }

    const originalContent = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        apiMsg.innerHTML = ''; 

        // 1. Validation
        if (!validator.validateForEmptyFields(e)) return;

        // 2. Loading State
        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner;

        try {
            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/listings-connect`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                // Success Feedback 💎
                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2 animate-pulse">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        ${result.message || 'Inquiry sent successfully!'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden'; 

                setTimeout(() => {
                    if (modalInstance) modalInstance.close();
                }, 1500);
            } else {
                throw new Error(result.message || 'Unable to send inquiry.');
            }
        } catch (error) {
            console.error('Listing Inquiry Error:', error);
            apiMsg.innerHTML = `
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl font-bold text-sm mt-2 border border-red-100 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    ${error.message}
                </div>
            `;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }
    });
}

/**
 * Opens the Contact/Inquiry modal for a specific Listing 💎
 */
export function openConnectListingModal(trigger) {
    const ds = trigger.dataset;
    
    const listingId = ds.id || ds.encodedId;
    const ownerId = ds.ownerId;
    const listingTitle = ds.listingTitle || 'Listing';
    // Ensure 0, null, or '0' all result in "Contact for price"
    const rawPrice = ds.price;
    const price = (rawPrice && rawPrice !== '0' && rawPrice !== 0) 
        ? `Price: ${rawPrice}` 
        : 'Contact for price';

    // PRE-FLIGHT CHECK: Cannot message yourself
    const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
    if (currentUserId && ownerId && String(currentUserId) === String(ownerId)) {
        if (typeof showToast === 'function') {
            showToast('Action Denied', 'This is your own listing.', 'info');
        }
        return;
    }

    if (listingConnectModal) listingConnectModal.destroy();

    listingConnectModal = new Modal({
        id: 'listing-connect-modal',
        title: 'Contact Owner',
        content: listingConnectForm({ listingTitle, listingId, ownerId, price }),
        size: 'lg',
        showFooter: false,
    });

    listingConnectModal.open();
    
    const form = document.getElementById('listing-connect-form');
    if (form) handleInquirySubmission(form, listingConnectModal);
}

// Logic to attach listeners
let listingListenersAttached = false;
export function initListingsConnect() {
    if (listingListenersAttached) return;

    document.addEventListener('click', (e) => {
        // Targets the card triggers and the "Contact Agent" button inside the View Modal
        const trigger = e.target.closest('.connect-listing-trigger') || e.target.closest('#view-listing-contact-btn');
        
        if (trigger) {
            e.preventDefault();
            openConnectListingModal(trigger);
        }
    });

    listingListenersAttached = true;
}