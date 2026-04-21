// /resources/js/utils/mentors/view-mentor.js

import { ViewContentMapper } from './view-content-mapper.js';
import { modalDetailOwner } from "../../ui/modal-detail-owner.js";

/**
 * Direct trigger for the modal using a data object
 */
export function openMentorModal(data) {
    const modal = document.getElementById('view-mentor-modal');
    if (!modal) return console.error("View Mentor Modal not found.");

    // 1. Map professional data & owner details
    ViewContentMapper.mapAll(data);
    modalDetailOwner('mentor', data);
    
    // 2. Set reference ID
    modal.dataset.mentorId = data.id || data.encodedId; 

    // 3. Reveal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
}

export function initViewMentor() {
    if (window._viewMentorListenerAttached) return;

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.view-mentor-trigger');
        if (!trigger || e.target.closest('.edit-mentor-btn, .delete-mentor-btn, .dropdown, .ignore-click, .connect-mentor-trigger')) return;

        // Use the new shared function
        openMentorModal(trigger.dataset);
    });

    ViewContentMapper.initUIBehaviors();
    window._viewMentorListenerAttached = true;
}