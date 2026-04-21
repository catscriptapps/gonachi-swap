// /resources/js/utils/mentors/delete-mentor.js

import { createDeleteHandler } from '../../factories/delete-factory.js';
import { showToast } from '../../ui/toast.js';

/**
 * Attaches delete functionality for Mentor Profiles via delegation.
 */
export function initDeleteMentor(containerSelector = '#mentors-grid') {
    // 1. Singleton Check: Prevent multiple global listeners
    if (window._deleteMentorListenerAttached) return;
    
    const grid = document.querySelector(containerSelector);
    if (!grid) return;

    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const deleteHandler = createDeleteHandler(`${baseUrl}api/mentors`, 'Mentor Profile');

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-mentor-btn');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation(); 

        const card = btn.closest('.mentor-card-wrapper');
        const encodedId = btn.dataset.encodedId || card?.dataset.encodedId;

        if (!encodedId || !card) {
            console.error('Delete failed: Missing encoded ID or card element.');
            return;
        }

        deleteHandler.showConfirmation(encodedId, card, (success) => {
            if (!success) return;

            showToast('Mentor profile successfully removed', 'success');

            // Handle UI state after removal
            setTimeout(() => {
                // Update counter if you have one (e.g., .active-mentors-count)
                const counter = document.querySelector('.active-mentors-count');
                if (counter) {
                    const currentCount = parseInt(counter.textContent.replace(/\D/g, '')) || 0;
                    counter.textContent = Math.max(0, currentCount - 1).toLocaleString();
                }
                
                // Handle empty state for "My Mentor Profile" section
                const remainingCards = grid.querySelectorAll('.mentor-card-wrapper').length;
                if (remainingCards === 0) {
                    const container = document.getElementById('my-mentor-container');
                    if (container && !document.getElementById('empty-mentors-state')) {
                        const emptyStateHtml = `
                            <div id="empty-mentors-state" class="p-20 text-center" data-aos="zoom-in">
                                <div class="mb-4 flex justify-center text-gray-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 mb-4 text-sm">You are not currently registered as a mentor.</p>
                                <button class="text-primary-400 font-black hover:underline text-sm register-mentor-trigger uppercase tracking-widest">
                                    Become a Professional Mentor
                                </button>
                            </div>
                        `;
                        container.insertAdjacentHTML('afterbegin', emptyStateHtml);
                    }
                }
            }, 300); 
        });
    });

    window._deleteMentorListenerAttached = true;
}