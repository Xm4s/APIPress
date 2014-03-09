<?php

global $format;
if ('JSON' === $format) {
	header('Content-type: application/json');
} else if ('XML' === $format) {
	header('Content-type: application/xml');
}

if (is_archive()) {
	the_current_data(get_query_var('cat'));
} else if (is_single() || is_page()) {
	the_current_post();
}

?>
