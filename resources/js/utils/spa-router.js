/**
 * Partial SPA Router — handles navigation, history, and content loading.
 * Links with [data-partial] trigger partial loads.
 * Pages are PHP partials returning valid HTML <title> + content.
 */

import { showSpinner, hideSpinner } from './../ui/spinner.js';

/**
 * Updates active state of navigation links to match the sleek theme.
 */
export function updateActiveLink(url) {
  const allLinks = document.querySelectorAll('nav a[data-partial], #sidebar a[data-partial]');
  const currentPath = new URL(url, window.location.origin).pathname.replace(/\/$/, "") || "/";

  allLinks.forEach(link => {
    const linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/$/, "") || "/";
    const icon = link.querySelector('.nav-icon');
    const isActive = linkPath === currentPath;

    const inactiveClasses = [
      'text-gray-600', 'text-gray-700', 'hover:text-primary-600',
      'hover:bg-primary-50', 'hover:bg-gray-100', 'dark:text-gray-300',
      'dark:text-gray-400', 'dark:hover:bg-gray-800', 'dark:hover:text-primary-400'
    ];

    if (isActive) {
      link.classList.add('bg-primary-600', 'text-white', 'shadow-lg');
      link.classList.remove(...inactiveClasses);
      
      // Ensure hover state is white while background is primary
      link.classList.remove('hover:text-primary-600', 'dark:hover:text-primary-400');
      link.classList.add('hover:text-white');

      if (icon) {
        icon.classList.add('text-white');
        icon.classList.remove('text-gray-400', 'group-hover:text-primary-600');
      }
    } else {
      // 1. CLEANUP: Remove the white background and shadow
      link.classList.remove('bg-primary-600', 'text-white', 'shadow-lg');
      
      // 2. THE FIX: Explicitly remove the white hover state so it doesn't stay white on light backgrounds
      link.classList.remove('hover:text-white'); 

      const isMobileLink = link.id.startsWith('mobile-nav-');
      link.classList.add(isMobileLink ? 'text-gray-600' : 'text-gray-700');
      
      // 3. Re-add the proper hover state (Primary Orange text on light background)
      link.classList.add('hover:text-primary-600', 'dark:hover:text-primary-400', 'dark:hover:bg-gray-800');
      
      if (isMobileLink) {
          link.classList.add('hover:bg-gray-100', 'dark:text-gray-400');
      } else {
          link.classList.add('hover:bg-primary-50', 'dark:text-gray-300');
      }

      if (icon) {
        icon.classList.remove('text-white');
        icon.classList.add('text-gray-400', 'group-hover:text-primary-600');
      }
    }
  });
}

/**
 * Updated loadPartial for spa-router.js
 * Handles content injection and Document Title sync.
 */
export async function loadPartial(url, pushState = true, clickedLink = null) {
  try {
    showSpinner();

    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (!response.ok) throw new Error(`Failed to load ${url}`);

    const html = await response.text();
    const appName = window.APP_CONFIG?.appName || 'Gonachi';
    
    // 1. Title Sync Logic
    // Grab title from clicked link OR find the matching nav link (for popstate/back button)
    const trigger = clickedLink || document.querySelector(`a[data-partial][href*="${url.split('/').pop()}"]`);
    const pageTitle = trigger?.getAttribute('data-title');
    
    if (pageTitle) {
        document.title = `${pageTitle} | ${appName}`;
    }

    // 2. Style Cleanup
    document.querySelectorAll('style[data-page-style]').forEach(tag => tag.remove());
    
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    
    doc.querySelectorAll('style').forEach(style => {
        style.setAttribute('data-page-style', 'true');
        document.head.appendChild(style);
    });

    // 3. Content Injection
    const masterContainer = document.querySelector('#main-content');
    if (masterContainer) {
        masterContainer.style.display = 'none'; 
        masterContainer.innerHTML = html.replace(/<style\b[^>]*>([\s\S]*?)<\/style>/gim, "");
        masterContainer.style.display = 'block';
    }

    // 4. State Management
    updateActiveLink(url);
    if (pushState) history.pushState({ url }, '', url);

    hideSpinner();
    window.scrollTo(0, 0);
    document.body.dispatchEvent(new CustomEvent('partial-load', { detail: { url } }));

  } catch (err) {
    console.error('SPA Load Error:', err);
    hideSpinner();
  }
}

export function bindPartialLinks() {
  document.body.addEventListener('click', (e) => {
    const link = e.target.closest('a[data-partial]');
    if (!link || link.target === '_blank' || e.metaKey || e.ctrlKey) return;

    e.preventDefault();
    loadPartial(link.href, true, link);
  });

  window.addEventListener('popstate', (e) => {
    const url = e.state?.url || window.location.href;
    loadPartial(url, false);
  });

  updateActiveLink(window.location.href);
}

window.loadPartial = loadPartial;