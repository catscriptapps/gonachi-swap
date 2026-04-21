// /resources/js/api/quotation-destinations-api.js

export async function fetchQuotationDestinations() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const url = `${baseUrl}api/quotation-destinations`;

    const res = await fetch(url);
    const json = await res.json();

    return json.success ? json.data : [];
}