// /resources/js/utils/adverts/admin-tabs.js

/**
 * Admin Adverts Tab Switching (Local Filter Version)
 * /resources/js/utils/adverts/admin-tabs.js
 */
import { updateCount } from '../../components/table-pagination-count.js';

export function initAdminTabs() {
    const tabs = document.querySelectorAll('.advert-tab');
    const rows = document.querySelectorAll('#adverts-tbody tr');
    const emptyState = document.getElementById('adverts-empty-state'); // We'll add this ID to your "No adverts" row

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const filterStatus = tab.dataset.status;

            // 1. UI: Update Active Tab Styles
            tabs.forEach(t => {
                t.classList.remove('bg-white', 'dark:bg-gray-900', 'border-gray-200', 'dark:border-gray-800', 'text-primary-600');
                t.classList.add('bg-gray-100', 'dark:bg-gray-800', 'border-transparent', 'text-gray-500');
            });
            tab.classList.add('bg-white', 'dark:bg-gray-900', 'border-gray-200', 'dark:border-gray-800', 'text-primary-600');
            tab.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'border-transparent', 'text-gray-500');

            // 2. Logic: Filter Rows
            let visibleCount = 0;

            rows.forEach(row => {
                // We look for the data-status attribute on the .view-ad-trigger div inside the row
                const trigger = row.querySelector('.view-ad-trigger');
                const rowStatus = trigger ? trigger.dataset.status : null;

                if (filterStatus === 'all' || rowStatus === filterStatus) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            // 3. Update the "Showing X Adverts" footer count
            const countDisplay = document.querySelector('#adverts-count');
            if (countDisplay) {
                // Determine pluralization
                const label = visibleCount === 1 ? 'advert' : 'adverts';
                
                // Update the text to be descriptive
                countDisplay.textContent = `Showing ${visibleCount} ${label}`;
            }

            // 4. Handle Empty State visibility
            // If we filtered and found 0 results, show the "No adverts found" row
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        });
    });
}