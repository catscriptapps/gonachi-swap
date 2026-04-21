// /resources/js/utils/social-feed/upload-video.js

import { videoUploadModal, createVideoUploadHandler } from '../../modals/video-upload-modal.js';
import { openCreatePostModal } from '../../modals/social-modal.js';

/**
 * Handles Video Upload Intent 🍊
 * Similar to Photo Upload, but specialized for video formats.
 */
export function initSocialVideoUpload() {
    if (window._socialVideoUploadAttached) return;

    document.addEventListener('click', (e) => {
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';
        const videoBtn = e.target.closest('#intent-video-btn');
        
        if (videoBtn) {
            e.preventDefault();     
            videoUploadModal.open();
            
            createVideoUploadHandler(`${baseUrl}api/post-video-upload`, (uploadedFiles, response) => {
                if (uploadedFiles.length > 0) {
                    // 🍊 Extract just the filename (e.g., vid_65b123.mp4) 
                    // Use the response filename if available, otherwise strip the path
                    const filename = response?.filename || uploadedFiles[0].url.split('/').pop();

                    openCreatePostModal({
                        mediaUrl: filename,       // This goes into the form generator
                        mediaType: 'video',       // This tells the DB it's a video
                        mediaFilename: filename   // This triggers your dataset logic
                    });
                }
            });
        }
    });

    window._socialVideoUploadAttached = true;
}