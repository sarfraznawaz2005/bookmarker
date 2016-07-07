// update this variable with domain (without trailing slash) where bookmarker app exists.
var domain = 'http://bookmarker.io';
////////////////////////////////////////////////////////////////////////////////////////

document.addEventListener("DOMContentLoaded", function() {

    var $loader = $('#loader');

    // send data to server for saving
    $('#btn').click(function() {
        var $this = $(this);

        //Proper Query Formation    
        chrome.tabs.query({
            "active": true,
            "status": "complete",
            "currentWindow": true,
            "windowType": "normal"
        }, function(tab) { //Get current tab
            //DO Ajax call
            //tab is an array so we need to access its first index
            chrome.tabs.executeScript(
                tab[0].id, {
                    "code": 'var __a=document.createElement("DIV");__a.id="__wnoverlay__";__a.style.background="#333";__a.style.width="300px";__a.style.height="80px";__a.style.position="fixed";__a.style.top="50%";__a.style.left="50%";__a.style.color="#fff";__a.style.zIndex=999999999999;__a.style.opacity=0.7;__a.style.textAlign="center";__a.style.padding="10px";__a.style.border="12px solid #cccccc";__a.style.marginLeft="-150px";__a.style.marginTop="-40px";__a.style.fontWeight="bold";__a.style.fontSize="17px";__a.style.borderRadius="10px";__a.innerHTML="Working, please wait...";document.body.appendChild(__a);'
                });

            $('#url').val(tab[0].url);
            $('#title').val(tab[0].title);
            $loader.hide();

            // load email and password from local storage
            if (typeof(Storage) !== "undefined") {
                localStorage.__bookmarker_email = $('#email').val();
                localStorage.__bookmarker_password = $('#password').val();
            }

            if (!$('#folder').val()) {
                alert('Please choose a folder first.');
            }

            chrome.extension.getBackgroundPage().invokeAJAX(tab[0].id, $this.closest('form').serialize());
        });

    });

    // called when extension button is clicked from browser
    chrome.tabs.query({
        "currentWindow": true, //Filters tabs in current window
        "status": "complete", //The Page is completely loaded
        "active": true, // The tab or web page is browsed at this state,
        "windowType": "normal" // Filters normal web pages, eliminates g-talk notifications etc
    }, function(tab) { //It returns an array

        var $loader = $('#loader'),
            $ediv = $('#error'),
            folders = null,
            total_folders = 0,
            email = '',
            password = '',
            options = [];

        $loader.show();

        if (typeof tab[0] !== 'undefined') {
            $('#url').val(tab[0].url);
            $('#title').val(tab[0].title);
        }

        // load email and password from local storage
        if (typeof(Storage) !== "undefined") {
            if (localStorage.__bookmarker_email) {
                email = localStorage.__bookmarker_email;
                $('#email').val(localStorage.__bookmarker_email);
            }

            if (localStorage.__bookmarker_password) {
                password = localStorage.__bookmarker_password;
                $('#password').val(localStorage.__bookmarker_password);
            }
        }

        if (folders === null || (folders && total_folders > folders.length)) {

            $.getJSON(domain + '/get_folders/' + email + '/' + password, function(r) {

                if (r) {
                    folders = r;

                    for (var i = 0, result = r; i < result.length; i++) {
                        options.push('<option value="', result[i].id, '">', result[i].name, '</option>');
                    }

                    total_folders = result.length;
                    $('#folder').html(options.join(''));

                } else {
                    if (r.error) {
                        $ediv.text(r.error).fadeIn('fast');
                    } else {
                        $ediv.text('Unknown error, please try again later.').fadeIn('fast');
                    }
                }

                $loader.hide();
            });
        } else {
            for (var i = 0, result = folders.success; i < result.length; i++) {
                options.push('<option value="', result[i].id, '">', result[i].name, '</option>');
            }

            $('#folder').html(options.join(''));

            $loader.hide();
        }


        $loader.hide();
    });

});