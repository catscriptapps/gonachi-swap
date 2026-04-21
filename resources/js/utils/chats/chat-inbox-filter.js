// /resources/js/utils/chats/chat-inbox-filter.js

/**
 * Local Inbox Filtering 🔍
 */
export function initInboxFilter() {
    const searchInput = document.getElementById('inbox-search');
    
    if (!searchInput) return;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();
        // Target all rows currently in the DOM
        const chatRows = document.querySelectorAll('.chat-row-trigger');

        chatRows.forEach(row => {
            // 1. Grab the Name (h3)
            const nameElement = row.querySelector('h3');
            const userName = nameElement ? nameElement.textContent.toLowerCase() : '';

            // 2. Grab the Location
            // We find the flex container for location and get the span text
            // This is safer than using the specific text-[9px] class selector
            const locationElement = row.querySelector('.flex.items-center.gap-1 span');
            const location = locationElement ? locationElement.textContent.toLowerCase() : '';
            
            // 🍊 Search "Stack": Name + Location combined
            const searchStack = `${userName} ${location}`;

            // Check if the query matches either name or location
            if (searchStack.includes(query)) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });

        // Handle "No Results" state
        toggleEmptyState();
    });
}

function toggleEmptyState() {
    const container = document.getElementById('inbox-list-container');
    if (!container) return;

    // Check visibility based on the style we just set
    const activeRows = Array.from(container.querySelectorAll('.chat-row-trigger'))
                            .filter(r => r.style.display !== 'none');
    
    let emptyMsg = document.getElementById('inbox-empty-search');

    if (activeRows.length === 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('div');
            emptyMsg.id = 'inbox-empty-search';
            emptyMsg.className = "p-10 text-center flex flex-col items-center justify-center";
            emptyMsg.innerHTML = `
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 italic">No matches found</p>
                <span class="text-[9px] text-gray-300 mt-2 uppercase">Try searching by name or city</span>
            `;
            container.appendChild(emptyMsg);
        }
    } else if (emptyMsg) {
        emptyMsg.remove();
    }
}