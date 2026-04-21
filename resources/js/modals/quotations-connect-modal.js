// /resources/js/modals/quotations-connect-modal.js

import { Modal } from '../factories/modal-factory.js';
import { quotationConnectForm } from '../forms/quotation-connect-form.js';
import { FormValidator } from '../utils/form-validator.js';
import { buttonSpinner } from '../utils/spinner-utils.js';

let quoteConnectModal = null;

/**
 * Handles the Proposal submission with full validation and API feedback 💎
 */
async function handleQuoteSubmission(form, modalInstance) {
    if (form._quoteFormListenerAttached) return;
    form._quoteFormListenerAttached = true;

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
        apiMsg.innerHTML = ''; // Clear previous messages

        // 1. Run FormValidator
        if (!validator.validateForEmptyFields(e)) return;

        // 2. State: Loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner;

        try {
            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/quotations-connect`, {
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
                        ${result.message || 'Proposal submitted successfully!'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden'; 

                // Close modal after a brief success period
                setTimeout(() => {
                    if (modalInstance) modalInstance.close();
                }, 1500);
            } else {
                throw new Error(result.message || 'Unable to submit proposal.');
            }
        } catch (error) {
            console.error('Quotation Proposal Error:', error);
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
 * Opens the Proposal/Connect modal for a specific Quotation 💎
 */
export function openConnectQuotationModal(trigger) {
    const ds = trigger.dataset;
    
    const quoteId = ds.id || ds.encodedId; // The ID of the Quotation project
    const ownerId = ds.ownerId; // The ID of the user who posted the request
    const quoteTitle = ds.title || 'Project';

    // Ensure 0, null, or '0' all result in "Contact for budget"
    const rawBudget = ds.budget;
    const budget = (rawBudget && rawBudget !== '0' && rawBudget !== 0) 
        ? `Budget: ${rawBudget}` 
        : 'Contact for budget';

    // PRE-FLIGHT CHECK: Cannot bid on own quotation
    const currentUserId = window.APP_CONFIG?.user?.id || window.sessionUserId;
    if (currentUserId && ownerId && String(currentUserId) === String(ownerId)) {
        if (typeof showToast === 'function') {
            showToast('Self-Proposal Denied', 'You cannot submit a proposal for your own project.', 'warning');
        }
        return;
    }

    if (quoteConnectModal) quoteConnectModal.destroy();

    quoteConnectModal = new Modal({
        id: 'quotation-connect-modal',
        title: 'Submit Project Proposal',
        content: quotationConnectForm({ quoteTitle, quoteId, ownerId, budget }),
        size: 'lg',
        showFooter: false,
    });

    quoteConnectModal.open();
    
    const form = document.getElementById('quotation-connect-form');
    if (form) handleQuoteSubmission(form, quoteConnectModal);
}

// Logic to attach listeners
let quoteListenersAttached = false;
export function initQuotationsConnect() {
    if (quoteListenersAttached) return;

    document.addEventListener('click', (e) => {
        // Targets both card triggers and the "Submit Quote" button inside the View Modal
        const trigger = e.target.closest('.connect-quotation-trigger') || e.target.closest('#view-quotation-contact-btn');
        
        if (trigger) {
            e.preventDefault();
            openConnectQuotationModal(trigger);
        }
    });

    quoteListenersAttached = true;
}