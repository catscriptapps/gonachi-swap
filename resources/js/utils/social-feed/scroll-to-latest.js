// /resources/js/utils/social-feed/scroll-to-latest.js


/**
 * Injects a "Jump to Latest" button that appears when scrolling up
 */
export function initScrollToLatest(container) {
    if (!container) return;

    // Remove existing if any
    const existing = container.parentElement.querySelector('.scroll-latest-btn');
    if (existing) existing.remove();

    const btn = document.createElement('button');
    btn.className = 'scroll-latest-btn hidden-btn bg-primary-500 text-white p-2 rounded-full shadow-lg hover:bg-primary-600 active:scale-95 transition-all';
    btn.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    `;
    
    container.parentElement.appendChild(btn);

    // Show/Hide logic
    container.addEventListener('scroll', () => {
        const scrollPos = container.scrollTop + container.clientHeight;
        const totalHeight = container.scrollHeight;

        // If user is more than 100px from the bottom, show the button
        if (totalHeight - scrollPos > 100) {
            btn.classList.remove('hidden-btn');
        } else {
            btn.classList.add('hidden-btn');
        }
    });

    btn.onclick = () => {
        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    };
}