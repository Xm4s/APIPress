<?php

global $format;
if ('JSON' === $format || 'XML' === $format) {
	load_template(locate_template('api.php'));
} else {
	// This show a bare HTML list of available resources and items at the current level
	load_template(locate_template('api.html.php'));
	// You can remove the previous line and put here your archive page HTML
}

?>
