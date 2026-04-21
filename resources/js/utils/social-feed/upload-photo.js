// /resources/js/utils/social-feed/upload-photo.js

import { uploadModal, createUploadHandler } from '../../modals/upload-modal.js';
import { openCreatePostModal } from '../../modals/social-modal.js';

export function initSocialPhotoUpload() {
    if (window._socialPhotoUploadAttached) return;

    document.addEventListener('click', (e) => {
        const baseUrl = window.APP_CONFIG.baseUrl;
        const photoBtn = e.target.closest('#intent-image-btn');
        
        if (photoBtn) {
            e.preventDefault();
            uploadModal.open();
            
            setTimeout(() => {
                createUploadHandler(`${baseUrl}api/post-media-upload`, 'posts', (uploadedFiles) => {
    
                    if (uploadedFiles && uploadedFiles.length > 0) {
                        const fileData = uploadedFiles[0];

                        uploadModal.close();

                        // 🍊 FIX: The preview image needs the full URL to show up in the modal
                        // fileData.url usually contains the full path from the server
                        openCreatePostModal({
                            mediaUrl: fileData.url, 
                            mediaFilename: fileData.filename || fileData.url.split('/').pop(),
                            mediaType: 'image'
                        });
                    }
                }, 1, true, { single: true });
            }, 50);
        }
    });

    window._socialPhotoUploadAttached = true;
}