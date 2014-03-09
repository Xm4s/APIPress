<?php

global $format;
if ('JSON' === $format || 'XML' === $format) {
	load_template(locate_template('api.php'));
} else {
	// This show a bare HTML of the current item
	load_template(locate_template('api.html.php'));
	// You can remove the previous line and put here your single page HTML
}

?>
