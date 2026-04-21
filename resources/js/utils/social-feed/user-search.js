// /resources/js/utils/social-feed/user-search.js

/**
 * Handle Sidebar User Search Logic - Gonachi Style
 */
export function initUserSearch() {
    const searchInput = document.getElementById('user-search-input');
    const resultsDropdown = document.getElementById('search-results-dropdown');
    const resultsContent = document.getElementById('search-results-content');

    if (!searchInput || !resultsDropdown) return;

    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();

        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            resultsDropdown.classList.add('hidden');
            resultsContent.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                // Correct Endpoint: No slashes, hitting the users.php file directly
                const baseUrl = window.APP_CONFIG?.baseUrl || '/';
                const endpoint = `${baseUrl}api/users?q=${encodeURIComponent(query)}&mode=social`;
                
                const response = await fetch(endpoint);
                const data = await response.json();

                if (data.success && data.html) {
                    resultsContent.innerHTML = data.html;
                    resultsDropdown.classList.remove('hidden');
                } else {
                    resultsContent.innerHTML = `
                        <div class="p-6 text-center border-b border-gray-50 dark:border-gray-800">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                                No users found
                            </p>
                        </div>`;
                    resultsDropdown.classList.remove('hidden');
                }
            } catch (error) {
                console.error('User search failed:', error);
            }
        }, 300);
    });

    // Close logic
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
            resultsDropdown.classList.add('hidden');
        }
    });
}