function getScript() {
    var scripts = document.getElementsByTagName('script');
    return scripts[scripts.length-1];
}

function getParams() {
    var script = getScript();

    return {
        url: script.getAttribute('src'),
        width : script.getAttribute('data-width'),
        height : script.getAttribute('data-height')
    };
}

function getBaseUrl(url) {
    return url.split('/widget.js')[0];
}

var script = getScript();
var iframe = document.createElement('iframe');
var params = getParams();

iframe.src = getBaseUrl(params.url);
iframe.setAttribute('width', params.width);
iframe.setAttribute('height', params.height);
iframe.setAttribute('style', 'border: 0px;');

script.parentNode.insertBefore(iframe, script);
