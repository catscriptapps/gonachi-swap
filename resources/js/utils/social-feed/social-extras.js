// /resources/js/utils/social-feed/social-extras.js

import { showToast } from '../../ui/toast.js';

export async function handleSharePost(btn) {
    const postId = btn.dataset.id;
    const shareUrl = `${window.location.origin}/post/${postId}`;
    
    if (navigator.share) {
        try {
            await navigator.share({ title: "Check this out!", url: shareUrl });
        } catch (err) { /* User cancelled */ }
    } else {
        navigator.clipboard.writeText(shareUrl);
        showToast('Link copied to clipboard!', 'success');
    }
}