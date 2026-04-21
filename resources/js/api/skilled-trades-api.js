// /resources/js/api/skilled-trades-api.js

export async function fetchSkilledTrades() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const url = `${baseUrl}api/skilled-trades`;

    const res = await fetch(url);
    const json = await res.json();

    return json.success ? json.data : [];
}