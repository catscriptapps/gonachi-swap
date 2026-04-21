// /resources/js/pages/mentors-page.js

import { AnimationEngine } from '../utils/animations';
import { initMentorsModal } from '../modals/mentors-modal.js';
import { initMentorSearch } from '../utils/mentors/search-mentors.js';
import { initViewMentor } from '../utils/mentors/view-mentor.js';
import { initDeleteMentor } from '../utils/mentors/delete-mentor.js';
import { initMentorsConnect } from '../modals/mentors-connect-modal.js';
import { initRegisterNewUser } from '../utils/home/register-new-user.js';

/**
 * Initialize Mentors Hub Events
 */
export function init() {  
    // 1. Initial Load & Animation Refresh
    refreshMentorPageState();

    // 2. Persistent Features
    initMentorsModal();
    initMentorSearch();
    initMentorsConnect(); 
    initDeleteMentor();
    
    initRegisterNewUser();
    triggerViewMentorModal();
}

function triggerViewMentorModal() {
    // Corrected SPA Handoff Logic
    const pendingMentorId = sessionStorage.getItem('trigger_view_mentor_modal');
    if (pendingMentorId) {
        sessionStorage.removeItem('trigger_view_mentor_modal');

        const targetBtn = document.querySelector(`.view-mentor-trigger[data-id="${pendingMentorId}"]`);
        
        if (targetBtn) {
            // Option A: Button is on page, use its dataset
            openMentorModal(targetBtn.dataset);
        } else {
            // Option B: Button not found, fetch from your api-mentors wrapper
            fetch(`${window.APP_CONFIG.baseUrl}api/mentors?id=${pendingMentorId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.mentor) {
                        // data.mentor is now the clean object from the controller above
                        openMentorModal(data.mentor);
                    }
                })
                .catch(err => console.error("Handoff Error:", err));
        }
    }
}

/**
 * Re-binds event listeners to cards (Run on load + after AJAX search)
 */
export function refreshMentorPageState() {
    AnimationEngine.refresh();

    // Re-bind View/Details listeners for the mentor cards
    initViewMentor();
}
