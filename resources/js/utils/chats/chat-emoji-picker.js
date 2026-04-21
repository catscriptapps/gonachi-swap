// /resources/js/utils/chats/chat-emoji-picker.js

/**
 * Specialized Emoji Picker for the Chat Modal
 */
export function initChatEmojiPicker() {
    if (window._chatEmojiInitialized) return;

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('#chat-emoji-btn');
        if (!trigger) return;

        e.preventDefault();
        e.stopPropagation();

        const form = trigger.closest('#modal-chat-form');
        const textarea = form ? form.querySelector('textarea') : null;
        if (!textarea) return;

        let picker = form.querySelector('.emoji-picker-popover');
        if (!picker) {
            picker = createChatPickerElement(textarea);
            form.appendChild(picker);
        }

        picker.classList.toggle('hidden');
    });

    // Global click-to-close
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.emoji-picker-popover') && !e.target.closest('#chat-emoji-btn')) {
            document.querySelectorAll('.emoji-picker-popover').forEach(p => p.classList.add('hidden'));
        }
    });

    window._chatEmojiInitialized = true;
}

function createChatPickerElement(textarea) {
    const emojis = [
        '📍', '🙌', '🚀', '✨', '🎉', '👏', '🤔', '😎', '💯', '👍', 
        '💪', '✅', '🙏', '😍', '🤣', '🤩', '😊', '😂', '🔥', '🧡',
        '🏆', '💰', '⚡', '🤝'
    ];
    const picker = document.createElement('div');
    
    // Ensure z-index is higher than the chat modal (z-100)
    picker.className = `emoji-picker-popover hidden absolute bottom-20 left-4 bg-white/95 dark:bg-gray-800/95 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-2xl p-3 grid grid-cols-4 gap-1 z-[999] w-48 backdrop-blur-sm animate-fade-in-up`;
    
    emojis.forEach(emoji => {
        const btn = document.createElement('button');
        btn.type = "button";
        // Ensure the font-family supports emojis (using sans)
        btn.className = "hover:bg-primary-50 dark:hover:bg-primary-900/30 p-2 rounded-xl transition-all text-xl active:scale-90 flex items-center justify-center font-sans pointer-events-auto";
        btn.textContent = emoji;
        
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            
            // Fixed the substring logic
            textarea.value = text.substring(0, start) + emoji + text.substring(end);
            
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
            
            picker.classList.add('hidden');
            textarea.dispatchEvent(new Event('input')); // Trigger resize
        });
        
        picker.appendChild(btn);
    });
    
    return picker;
}