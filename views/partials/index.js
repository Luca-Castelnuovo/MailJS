// Utility Functions
const delay = ms => new Promise(res => setTimeout(res, ms));
const disableAll = state => document.querySelectorAll('button, input, textarea').forEach(el => {el.disabled = state;});
const formDataToJSON = data => {
    const object = {};
    [...data].map((item) => object[item[0]] = item[1]);
    return object
}
