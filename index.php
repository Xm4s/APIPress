<script>
	/**
	* Fancy way to let PHP know if there's a "#" in the URL
	* We read the URL with JavaScript and set a "hash" cookie "true" or "false"
	* If our cookie was not set or its previous value was different we force a reload
	*
	* Maybe not the cleanest way to do it, but it's fast, simple and it works
	*/

	var cookies, tempCookie, cookieHash, currentHash;

	cookies = document.cookie.split(';');
	for (var index = 0; index < cookies.length; index++) {
		tempCookie = cookies[index].split('=');
		if (tempCookie[0].trim() === 'hash') {
			cookieHash = tempCookie[1].trim();
			break;
		}
	}

	currentHash = window.location.href.match(/\/#/);
	if (currentHash) {
		currentHash = 'true';
	} else {
		currentHash = 'false';
	}

	if (!cookieHash || cookieHash !== currentHash) {
		// Set or update the "hash" cookie
		document.cookie = "hash=" + currentHash;
		// Force window reload to let PHP read its correct value
		window.location.reload();
	}
</script>

<?php

if ('true' === $_COOKIE['hash']) {
	// There's a "#" in the URL --> load the Backbone app
	load_template(locate_template('api.backbone.php'));
} else {
	// Put here your index page HTML
}

?>
