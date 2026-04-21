// /resources/js/pages/blog-posts-page.js
import { AnimationEngine } from '../utils/animations';

/**
 * Initialize Blog/Insights Module
 */
export function init() {
    AnimationEngine.refresh();

    // Category filtering logic
    const categoryBtns = document.querySelectorAll('button[class*="border-gray-200"]');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            categoryBtns.forEach(b => b.classList.remove('bg-primary-500', 'text-white', 'border-primary-500'));
            e.target.classList.add('bg-primary-500', 'text-white', 'border-primary-500');
            console.log(`Filtering by: ${e.target.innerText}`);
        });
    });
}