/**
 * AJAX XmlHttp Request
 * @param {object} options 
 */
function send_ajax(options) {
    options = options || {};
    options.method = options.method.toUpperCase() || 'GET';
    options.url = options.url || '';
    options.async = options.async || true;
    options.data = options.data || null;
    options.dataType = options.dataType || 'text';
    options.success = options.success || function () {};
    var xmlHttp = null;
    if (XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }
    else {
        xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    var params = [];
    for (var key in options.data){
        params.push(key + '=' + options.data[key]);
    }
    var postData = params.join('&');
    if (options.method.toUpperCase() === 'POST') {
        xmlHttp.open(options.method, options.url, options.async);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf-8');
        xmlHttp.send(postData);
    }
    else if (options.method.toUpperCase() === 'GET') {
        xmlHttp.open(options.method, options.url + '?' + postData, options.async);
        xmlHttp.send(null);
    } 
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (options.dataType == 'json') {
                options.success(JSON.parse(xmlHttp.responseText));
            }
            else if (options.dataType == 'xml') {
                options.success(xmlHttp.responseXML);
            }
            else {
                options.success(xmlHttp.responseText);
            }
        }
    };
}

/**
 * Get Url Query String
 * @param name
 * @returns {string}
 */
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]); return null;
}