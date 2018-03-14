<?php
namespace WPSOCIALJWT;
require_once __DIR__."/config.php";

?>
<html>
<body>
<p><a href="#" onClick="logInWithFacebook()">Log In with the JavaScript SDK</a></p>

<script>
	logInWithFacebook = function() {
		FB.login(function(response) {
			if (response.authResponse) {
				alert("You are logged in & cookie set!");
				// Now you can redirect the user or do an AJAX request to
				// a PHP script that grabs the signed request from the cookie.
                console.log("response",response)
			} else {
				alert("User cancelled login or did not fully authorize.");
			}
		});
		return false;
	};
	window.fbAsyncInit = function() {
		FB.init({
					appId: "<?php echo FB_APP_ID ?>",
					cookie: true, // This is important, it"s not enabled by default
					version: "v2.10"
				});
	};

	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/us_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk"));
</script>
</body>
</html>