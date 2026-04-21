// /resources/js/forms/social-post-form.js

/**
 * Generates the HTML for the social post creation/edit form.
 */
export function socialPostForm({
    formId = 'social-post-form',
    buttonLabel = 'Publish Post',
    content = '',
    mediaUrl = '',
    mediaType = 'none'
}) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    let finalMediaUrl = mediaUrl;

    if (mediaUrl && mediaUrl !== '' && mediaUrl !== '#') {
        if (mediaUrl.startsWith('http')) {
            finalMediaUrl = mediaUrl;
        } 
        else if (baseUrl !== '/' && mediaUrl.startsWith(baseUrl)) {
            finalMediaUrl = mediaUrl;
        }
        else {
            const cleanBase = baseUrl.endsWith('/') ? baseUrl : baseUrl + '/';
            const cleanMedia = mediaUrl.startsWith('/') ? mediaUrl.substring(1) : mediaUrl;
            
            // 🍊 Path Resolution Logic
            if (mediaType === 'video') {
                // Videos are in the /videos/ folder
                finalMediaUrl = `${cleanBase}videos/${cleanMedia}`;
            } else if (!cleanMedia.includes('images/uploads/posts/')) {
                // Images remain in the nested uploads folder
                finalMediaUrl = `${cleanBase}images/uploads/posts/${cleanMedia}`;
            } else {
                finalMediaUrl = `${cleanBase}${cleanMedia}`;
            }
        }
    }

    const inputClasses = `
        block w-full rounded-2xl 
        border border-gray-300 dark:border-gray-700 
        bg-white dark:bg-gray-900 
        text-gray-900 dark:text-white 
        placeholder:text-gray-400 
        focus:border-primary-500 focus:ring-primary-500 
        sm:text-sm transition-all duration-200 py-4 px-5 pr-14
    `.replace(/\s+/g, ' ').trim();

    const labelClasses = "block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1";

    const hasMedia = finalMediaUrl && finalMediaUrl !== '' && finalMediaUrl !== '#';
    const isVideo = mediaType === 'video' || (finalMediaUrl && typeof finalMediaUrl === 'string' && finalMediaUrl.match(/\.(mp4|webm|ogg)$/i));

    return `
    <form id="${formId}" class="w-full max-w-2xl mx-auto space-y-6 p-1 font-sans" novalidate>
        <div class="relative">
            <label for="post-content" class="${labelClasses}">What's on your mind?</label>
            <div class="relative group">
                <textarea id="post-content" name="content" rows="5"
                    placeholder="Share an update with the team..." 
                    class="${inputClasses} resize-none text-base leading-relaxed">${content}</textarea>
                
                <button type="button" id="create-post-emoji-btn" 
                    class="absolute right-4 bottom-4 text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-all p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800"
                    title="Add Emoji">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="media-preview-area" class="${hasMedia ? '' : 'hidden'} relative rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-md bg-gray-50 dark:bg-gray-950">
            <img id="post-image-preview" 
                 src="${!isVideo ? finalMediaUrl : '#'}" 
                 class="${!isVideo && hasMedia ? '' : 'hidden'} w-full max-h-80 object-cover" />
            
            <video id="post-video-preview" 
                    src="${isVideo ? finalMediaUrl : '#'}" 
                    class="${isVideo && hasMedia ? '' : 'hidden'} w-full max-h-80" 
                    controls></video>
            
            <button type="button" id="remove-media-btn" class="absolute top-3 right-3 bg-black/60 hover:bg-red-600 text-white rounded-full p-2 backdrop-blur-md transition-all z-20 shadow-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <input type="hidden" name="media_url" id="post-media-url" value="${mediaUrl}">
        <input type="hidden" name="media_type" id="post-media-type" value="${mediaType}">

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
            <div class="text-xs text-gray-400 italic">
                Markdown is supported
            </div>
            <button type="submit" id="post-submit-btn"
                class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-10 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary-500/20 hover:bg-primary-700 hover:shadow-primary-500/40 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all active:scale-95 disabled:opacity-50">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}