// /resources/js/pages/verify-account-page.js

/**
 * Verify Account Page Module
 */
export function init() {
    const loader = document.getElementById('verifying-loader');
    const success = document.getElementById('verification-success');
    const error = document.getElementById('verification-error');
    const errorMsg = document.getElementById('error-message');

    // Safety check - make sure we are actually on the right page
    if (!loader) return;

    const params = new URLSearchParams(window.location.search);
    const payload = {
        token: params.get('token'),
        email: params.get('email')
    };

    // Immediate execution
    verifyToken(payload, { loader, success, error, errorMsg });
}

async function verifyToken(payload, ui) {
    try {
        // Use your global config for base URL
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';
        
        const response = await fetch(`${baseUrl}api/verify-account`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        ui.loader.classList.add('hidden');

        if (result.success) {
            ui.success.classList.remove('hidden');
        } else {
            ui.error.classList.remove('hidden');
            ui.errorMsg.innerHTML = result.messages?.[0] || 'Invalid or expired link.';
        }
    } catch (err) {
        ui.loader.classList.add('hidden');
        ui.error.classList.remove('hidden');
        ui.errorMsg.innerText = 'An unexpected error occurred connecting to the server.';
        console.error('Verification Error:', err);
    }
}