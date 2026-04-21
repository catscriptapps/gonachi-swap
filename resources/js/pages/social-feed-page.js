// /resources/js/pages/social-feed-page.js

import { initSocialModal } from '../modals/social-modal.js';
import { initSocialActions } from '../utils/social-feed/social-actions.js';
import { initViewPost } from '../utils/social-feed/view-post.js';
import { initSocialPhotoUpload } from '../utils/social-feed/upload-photo.js'; 
import { initSocialEmojiPicker } from '../utils/social-feed/emoji-picker.js';
import { PostManager } from '../utils/social-feed/post-manager.js';
import { initSocialVideoUpload } from '../utils/social-feed/upload-video.js';
import { loadFollowSuggestions } from '../utils/social-feed/suggestions.js';
import { initFollowActions } from '../utils/social-feed/follow-actions.js'; 
import { initRelationshipLists } from '../utils/social-feed/relationship-lists.js';
import { initUserSearch } from '../utils/social-feed/user-search.js';
import { initViewAdvert } from '../utils/adverts/view-advert.js'; 
import { initRegisterNewUser } from '../utils/home/register-new-user.js';

/**
 * Initialize the Social Feed page JS.
 */
export function init() {
    initSocialModal();
    initSocialPhotoUpload();
    initSocialActions();
    initViewPost();
    PostManager.init();
    initSocialVideoUpload();
    initRelationshipLists();

    // 🍊 Sidebar initialization
    loadFollowSuggestions();
    initFollowActions();
    initUserSearch();
    initViewAdvert();
    initRegisterNewUser();

    // Creation form listeners
    document.addEventListener('click', (e) => {
        if (e.target.closest('#open-create-post-btn')) {
            setTimeout(() => initSocialEmojiPicker('social-post-form'), 50);
        }
    });

    initSocialEmojiPicker('view-post-modal'); 
}