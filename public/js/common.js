function isEmpty (val) {
    return (val.length === 0 || !val.trim());
}

let currentEditEntityId = null;

function setUrlParam(urlParams, name, param) {
    if (false !== param && !isEmpty(param)) {
        if (!urlParams.has(name) || param != urlParams.get(name)) {
            urlParams.set(name, param);
        }
    } else {
        if (urlParams.has(name)) {
            urlParams.delete(name);
        }
    }
}