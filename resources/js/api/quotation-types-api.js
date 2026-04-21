// /resources/js/api/quotation-types-api.js

export async function fetchQuotationTypes() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const url = `${baseUrl}api/quotation-types`;

    const res = await fetch(url);
    const json = await res.json();

    return json.success ? json.data : [];
}