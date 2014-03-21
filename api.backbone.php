<?php

function add_backbone_body_class($classes) {
	$classes[] = 'apipress-backbone-app';
	return $classes;
};

function load_backbone_app() {
	wp_enqueue_style('backbone-app-style', get_template_directory_uri() . '/backbone-app/css/style.css');

	wp_enqueue_script('backbone-app-lib-jquery', get_template_directory_uri() . '/backbone-app/js/libs/jquery-min.js', array(), false, true);
	wp_enqueue_script('backbone-app-lib-underscore', get_template_directory_uri() . '/backbone-app/js/libs/underscore-min.js', array(), false, true);
	wp_enqueue_script('backbone-app-lib-backbone', get_template_directory_uri() . '/backbone-app/js/libs/backbone-min.js', array(), false, true);
	wp_enqueue_script('backbone-app-lib-handlebars', get_template_directory_uri() . '/backbone-app/js/libs/handlebars.js', array(), false, true);

	wp_enqueue_script('backbone-app', get_template_directory_uri() . '/backbone-app/js/app.js',
		array(
			'backbone-app-lib-jquery',
			'backbone-app-lib-underscore',
			'backbone-app-lib-backbone',
			'backbone-app-lib-handlebars'),
		false, true);
};

function load_backbone_templates() {

	include get_template_directory() . '/backbone-app/handlebars-templates.html';
}

function launch_backbone_app() {

	$api_root_path = parse_url(site_url(get_option('category_base')), PHP_URL_PATH);

	$script_output  = '<script type="text/javascript">';
	$script_output .= "$(function () { window.backboneApp.init({ API_ROOT : '$api_root_path' }); });";
	$script_output .= '</script>';

	echo $script_output;
};

add_filter('body_class', 'add_backbone_body_class');
add_filter('wp_footer', 'load_backbone_templates', 1);
add_action('wp_enqueue_scripts', 'load_backbone_app');
add_filter('wp_footer', 'launch_backbone_app', 100);

get_header();
echo '<div class="content"></div>';
get_footer();

?>
