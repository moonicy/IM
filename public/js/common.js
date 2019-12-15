function isEmpty (val) {
    if (null === val) {
        return true;
    }

    switch (typeof val) {
        case "undefined":
            return true;
        case "string":
            return (val.length === 0 || !val.trim());
        case "number":
            return 0 === val;
        case "boolean":
            return !val;
        default:
            console.log("isEmpty function broke with type is " + typeof val + ", value is " + val);
            return false;
    }
}

let currentEditEntityId = null;

function setUrlParam(urlParams, name, param) {
    if (false !== param && !isEmpty(param)) {
        if (!urlParams.has(name) || param !== urlParams.get(name)) {
            urlParams.set(name, param);
        }
    } else {
        if (urlParams.has(name)) {
            urlParams.delete(name);
        }
    }
}

function pagination(offset) {
    let url = new URL(window.location);

    let urlParams = new URLSearchParams(window.location.search);
    let newUrlParams = Object.assign({}, urlParams);

    setUrlParam(urlParams, 'offset', offset);

    if (newUrlParams.toString() !== urlParams.toString()) {
        url.search = urlParams.toString();

        location.replace(url.href);
    }
}