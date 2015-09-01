/**
 * website.js
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
window.document.onreadystatechange = function () {
    if ('complete' == this.readyState) {
        var a1Element = document.getElementById('link1');
        if (typeof a1Element == 'object' && 0 !== a1Element.length) {
            a1Element.onclick = function(e) {
                e.preventDefault();
                if (undefined !== e.target && e.target.href.length > 0) {
                    var targetDiv = document.getElementById('contact');
                    if (typeof targetDiv  == 'object') {
                        var xmlHttp = new XMLHttpRequest();
                        xmlHttp.onreadystatechange = function() {
                            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                                targetDiv.innerHTML = xmlHttp.responseText;
                            }
                        };
                        xmlHttp.open('GET', e.target.href);
                        xmlHttp.setRequestHeader('X-Requested-With', 'xmlhttprequest');
                        xmlHttp.send();
                    }
                }
            };
        }
    }
};
