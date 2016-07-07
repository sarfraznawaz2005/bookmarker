// update this variable with domain (without trailing slash) where bookmarker app exists.
var domain = 'http://bookmarker.io';
////////////////////////////////////////////////////////////////////////////////////////

function notify(title, message) {

    var options = {
        body: message,
        icon: domain + '/ChromeExtension/img/48.png'
    }

    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    } else if (Notification.permission === "granted") {
        var notification = new Notification(title, options);
        //setTimeout(notification.close.bind(notification), 5000); 
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function(permission) {
            if (permission === "granted") {
                var notification = new Notification(title, options);
            }
        });
    }
}

function invokeAJAX(tabid, data) {

    data = data || '';

    $.ajax({
        url: domain + '/save_bookmark',
        data: data,
        method: 'post',
        complete: function(result) {
            //console.log(result.responseText);

            notify('Result', result.responseText);

            chrome.tabs.executeScript(
                tabid, {
                    code: "document.body.removeChild(document.getElementById('__wnoverlay__'))"
                });
        }
    });

}