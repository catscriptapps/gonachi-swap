// /resources/js/utils/social-feed/emoji-picker.js

/**
 * Attaches emoji picker logic using event delegation.
 * This handles both the Main Feed post creator and the Comment Modal.
 */
export function initSocialEmojiPicker() {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('#create-post-emoji-btn') || e.target.closest('#comment-emoji-btn');
        if (!trigger) return;

        e.preventDefault();
        e.stopPropagation();

        const container = trigger.closest('.relative') || trigger.closest('form');
        const textarea = container ? container.querySelector('textarea') : null;
        
        if (!textarea) return;

        let picker = container.querySelector('.emoji-picker-popover');
        
        if (!picker) {
            // 🍊 Passing the trigger ID to determine the best position
            picker = createPickerElement(textarea, trigger.id);
            trigger.parentElement.appendChild(picker);
        }

        picker.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.emoji-picker-popover') && 
            !e.target.closest('#create-post-emoji-btn') && 
            !e.target.closest('#comment-emoji-btn')) {
            document.querySelectorAll('.emoji-picker-popover').forEach(p => p.classList.add('hidden'));
        }
    });
}

/**
 * Internal helper to build the picker UI 🍊
 */
function createPickerElement(textarea, triggerId) {
    const emojis = [
        '📍', '🙌', '🚀', '✨', '🎉', '👏', '🤔', '😎', '💯', '👍', 
        '💪', '✅', '🙏', '😍', '🤣', '🤩', '😊', '😂', '🔥', '🧡',
        '🏆', '💰', '⚡', '🤝'
    ];
    const picker = document.createElement('div');
    
    // 🍊 THE DUAL-LOGIC FIX:
    // If it's the main post creator, drop DOWN (top-full).
    // If it's the comment modal, pop UP (bottom-full) to avoid the modal floor.
    const isComment = triggerId === 'comment-emoji-btn';
    const positionClass = isComment ? "bottom-full mb-2 origin-bottom" : "top-full mt-2 origin-top";
    const animationClass = isComment ? "animate-fade-in-up" : "animate-fade-in-down";

    picker.className = `emoji-picker-popover hidden absolute ${positionClass} right-0 bg-white/95 dark:bg-gray-800/95 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-2xl p-3 grid grid-cols-4 gap-1 z-[999] w-48 backdrop-blur-sm ${animationClass}`;
    
    emojis.forEach(emoji => {
        const btn = document.createElement('button');
        btn.type = "button";
        btn.className = "hover:bg-primary-50 dark:hover:bg-primary-900/30 p-2 rounded-xl transition-all text-xl active:scale-90 flex items-center justify-center font-sans";
        btn.textContent = emoji;
        btn.onclick = (e) => {
            e.preventDefault();
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
            picker.classList.add('hidden');
        };
        picker.appendChild(btn);
    });
    
    return picker;
}