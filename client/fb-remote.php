<?php
namespace WPSOCIALJWT;
require_once "C:/xampp/htdocs/wptravel/wp-content/plugins/socialjwt/config.php";

?>
    <html>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <body>
    <p><a href="#" onClick="logInWithFacebook()">Log In with the JavaScript SDK</a></p>

    <script>
        logInWithFacebook = function() {
            FB.login(function(response) {
                if (response.authResponse) {
                    //alert("You are logged in & cookie set!");
                    // Now you can redirect the user or do an AJAX request to
                    // a PHP script that grabs the signed request from the cookie.
                    console.log("response", response)
                    var authResponse = response.authResponse;
                    var token = authResponse.accessToken;
                    //invio il token
                    var url = "http://wptravel.localhost";
                    var endpoint = "/wp-json/wp/v2/facebookjwt/token";
                    jQuery.ajax({
                                    url:url + endpoint + "/" + token
                                }).done(function(data) {
                                    console.log("JQuery data: ", data);
                                });
                }
                else {
                    alert("User cancelled login or did not fully authorize.");
                }
            });
            return false;
        };
        window.fbAsyncInit = function() {
            //FB_REMOTE_APP_ID
			FB.init({
                        appId:  "<?php echo FB_APP_ID ?>",
                        cookie: true, // This is important, it"s not enabled by default
                        version:"v2.10"
                    });
        };
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/us_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, "script", "facebook-jssdk"));
    </script>
    </body>
    </html>