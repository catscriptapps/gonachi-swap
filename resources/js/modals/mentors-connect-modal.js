// /resources/js/modals/mentors-connect.js

import { Modal } from '../factories/modal-factory.js';
import { mentorConnectForm } from '../forms/mentor-connect-form.js';
import { FormValidator } from '../utils/form-validator.js'; // Added validator
import { buttonSpinner } from '../utils/spinner-utils.js'; // Added spinner

let connectModal = null;

/**
 * Handles the Handshake submission with full validation and API feedback 💎
 */
async function handleConnectSubmission(form, modalInstance) {
    if (form._connectFormListenerAttached) return;
    form._connectFormListenerAttached = true;

    const validator = new FormValidator(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Inject or find API message container
    let apiMsg = form.querySelector('.api-message');
    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300';
        form.appendChild(apiMsg);
    }

    const originalContent = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        apiMsg.innerHTML = ''; // Clear previous messages

        // 1. Run your FormValidator
        if (!validator.validateForEmptyFields(e)) return;

        // 2. State: Loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner;

        try {
            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/mentors-connect`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                // Success Feedback
                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2 animate-pulse">
                        <i class="bi bi-send-check-fill text-xl text-primary-500"></i>
                        ${result.message || 'Connection request sent successfully!'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden'; 

                // Close modal after a brief success period
                setTimeout(() => {
                    if (modalInstance) modalInstance.close();
                }, 1500);
            } else {
                throw new Error(result.message || 'Unable to send request.');
            }
        } catch (error) {
            console.error('Handshake Error:', error);
            apiMsg.innerHTML = `
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl font-bold text-sm mt-2 border border-red-100">
                    <i class="bi bi-exclamation-triangle-fill mr-2"></i> ${error.message}
                </div>
            `;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }
    });
}

export function openConnectMentorModal(trigger) {
const ds = trigger.dataset;
    
    // 💎 FIX: Be explicit about the Mentor Card ID vs the Owner User ID
    // We need the Card ID for the MentorRequest table, 
    // but the backend needs to find the Owner for the Message table.
    const mentorId = ds.id; // This is the Card ID (6)
    const ownerId = ds.ownerId;
    const mentorName = ds.ownerName || 'Expert';
    const targetUserType = ds.targetUserType || 'Expert';

    // 💎 PRE-FLIGHT CHECK: Cannot message self
    const currentUserId = window.APP_CONFIG?.user?.id;
    if (currentUserId && mentorId && String(currentUserId) === String(mentorId)) {
        showToast('Self-Handshake Denied', 'You cannot send a connection request to your own profile.', 'warning');
        return; // Kill the process
    }

    if (connectModal) connectModal.destroy();

    connectModal = new Modal({
        id: 'mentor-connect-modal',
        title: 'Send a Message',
        content: mentorConnectForm({ mentorName, mentorId, ownerId, targetUserType }),
        size: 'lg',
        showFooter: false,
    });

    connectModal.open();
    
    const form = document.getElementById('mentor-connect-form');
    if (form) handleConnectSubmission(form, connectModal);
}

// Logic to attach listeners
let connectListenersAttached = false;
export function initMentorsConnect() {
    if (connectListenersAttached) return;

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.connect-mentor-trigger') || e.target.closest('#view-mentor-connect-btn');
        if (trigger) {
            e.preventDefault();
            openConnectMentorModal(trigger);
        }
    });

    connectListenersAttached = true;
}