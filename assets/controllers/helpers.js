/**
 * get browser's locale
 * @returns {string}
 */
export function getLang() {
    return window.location.pathname.slice(1, 3);
}