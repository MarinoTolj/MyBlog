/**
 * get browser's locale
 * @returns {string}
 */
export function getLang() {
    let lang = ""
    if (navigator.languages !== undefined)
        lang = navigator.languages[0];
    lang = navigator.language;
    lang = lang.slice(0, 2);
    return lang;
}