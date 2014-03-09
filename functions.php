<?php

$format    = null;
$fullitems = false;

add_action('init', 'set_api_permalink_structure', 0);
add_action('init', 'set_globals', 0);
add_action('template_redirect', 'handle_api_path');

function set_api_permalink_structure() {

    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%category%/%postname%/');
    $wp_rewrite->flush_rules();
}

function set_globals() {

    global $format;
    $format = strtoupper($_GET['format']);
    if ('JSON' !== $format && 'XML' !== $format) {
        $format = null;
    }

    global $fullitems;
    $fullitems = false;
        if ('1' === $_GET['fullitems'] || 'true' === $_GET['fullitems']) {
        $fullitems = true;
    }
}

function handle_api_path() {

    if (is_404()) {
        $current_path  = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $category_base = get_option('category_base');
        if ('' === $category_base) $category_base = 'category';

        if (check_if_api_root($current_path, $category_base)) {
            global $wp_query;
            $wp_query->is_404 = false;
            $wp_query->is_archive = true;
            status_header(200);
        } else {
            $current_post = check_if_valid_item_path($current_path, $category_base);
            if (is_object($current_post)) {
                global $query_string;
                query_posts($query_string . "&p=$current_post->ID");
                global $wp_query;
                $wp_query->is_404 = false;
                $wp_query->is_single = true;
                status_header(200);
            }
        }
    }
}

function check_if_api_root($current_path, $api_base) {

    $api_root_path = parse_url(site_url($api_base), PHP_URL_PATH);
    return ($current_path === $api_root_path);
}

function check_if_valid_item_path($current_path, $api_base) {

    $path_chunks = explode('/', $current_path);
    $api_index   = -1;
    foreach ($path_chunks as $index => $chunk) {
        if ($api_base === $chunk) {
            $api_index = $index;
            break;
        }
    }

    if (-1 === $api_index) return false;

    $chunks_max_index = count($path_chunks) - 1;
    $item_output = null;
    for ($index = $api_index + 1; $index < $chunks_max_index; $index++) {
        $item_output = check_api_chunks_relation($path_chunks[$index], $path_chunks[$index + 1], $api_index + 1 === $index);
        if (!$item_output) return $item_output;
    }

    return $item_output;
}

function check_api_chunks_relation($parent_chunk, $child_chunk, $is_first_resource) {
    $parent_resource = get_category_by_slug($parent_chunk);
    $child_resource  = get_category_by_slug($child_chunk);

    if (!$parent_resource || ($is_first_resource && 0 !== $parent_resource->parent)) return false;

    if (!$child_resource) {
        $child_item = get_posts(array('name' => $child_chunk, 'category' => $parent_chunk));
        if (0 === count($child_item)) return false;
        return $child_item[0];
    }

    if ($child_resource->parent !== $parent_resource->cat_ID) return false;

    return true;
}

function the_current_data($parent_ID) {

    echo get_current_data($parent_ID);
}

function get_current_data($parent_ID = 0) {

    if ('' == $parent_ID) $parent_ID = 0;

    $categories = prepare_categories_data(get_categories(array(
        'parent'     => $parent_ID,
        'hide_empty' => 0,
        'exclude'    => 1
    )));

    global $fullitems;
    $data = array('resources' => $categories, 'items' => array());
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            $data['items'][] = prepare_post_data($fullitems);
        }
    }

    return format_current_data($data);
}

function the_current_post() {

    echo get_current_post();
}

function get_current_post() {

    $data = array('items' => array());
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            $data['items'][] = prepare_post_data(true);
        }
    }

    return format_current_data($data);
}

function prepare_categories_data($categories) {

    $categories_output = array();
    foreach($categories as $category) {
        $id = $category->cat_ID;
        $categories_output[] = array(
            'ID'          => $id,
            'parent'      => $category->parent,
            'name'        => $category->name,
            'slug'        => $category->slug,
            'description' => $category->description,
            'count'       => $category->count,
            'link'        => get_category_link($id)
        );
    }

    return $categories_output;
}

function prepare_post_data($fullitems) {

    $id      = get_the_ID();
    $title   = get_the_title();
    $link    = get_permalink();
    $slug    = basename($link);
    $excerpt = get_the_excerpt();

    if ($fullitems) {
        $content = get_the_content();
        $post_output = array(
            'ID'            => $id,
            'link'          => $link,
            'slug'          => $slug,
            'title'         => $title,
            'author'        => get_the_author(),
            'date'          => mysql2date('c', get_the_date()),
            'tags'          => implode(', ', wp_get_post_tags($id, array('fields' => 'names'))),
            'categories'    => implode(', ', wp_get_post_categories($id, array('fields' => 'names'))),
            'excerpt'       => $excerpt,
            'content'       => strip_tags($content),
            'html-content'  => apply_filters('the_content', $content),
            'custom-fields' => prepare_post_customs(),
            'attachments'   => prepare_post_attachments(),
            'comments'      => prepare_post_comments(),
        );
    } else {
        $post_output = array(
            'ID'      => $id,
            'link'    => $link,
            'slug'    => $slug,
            'title'   => $title,
            'excerpt' => $excerpt
        );
    }

    return $post_output;
}

function prepare_post_customs() {

    $customs_output = array();
    foreach (get_post_custom() as $key => $value) {
        if('_' != $key{0}) {
            $customs_output[] = array('key' => $key, 'values' => $value);
        }
    }

    return $customs_output;
}

function prepare_post_attachments() {

    $attachments = get_children(array(
        'post_type'   => 'attachment',
        'post_parent' => get_the_ID()
    ));

    $attachments_output = array();
    foreach($attachments as $attachment) {
        $id = $attachment->ID;
        $content = $attachment->post_content;

        $useful_data = array(
            'ID'               => $id,
            'title'            => $attachment->post_title,
            'caption'          => $attachment->post_excerpt,
            'description'      => strip_tags($content),
            'html-description' => apply_filters('the_content', $content),
            'mime-type'        => get_post_mime_type($id),
            'link'             => wp_get_attachment_url($id),
        );

        $image_metadata = wp_get_attachment_metadata($id);
        if (!empty($image_metadata)) {
            $useful_data['width']  = $image_metadata['width'];
            $useful_data['height'] = $image_metadata['height'];
            foreach ($image_metadata['sizes'] as $size => $value) {
                $src = wp_get_attachment_image_src($id, $size);
                $value['link'] = $src[0];
                unset($value['file']);

                $useful_data['sizes'][$size] = $value;
            }
        }

        $attachments_output[] = $useful_data;
    }

    return $attachments_output;
}

function prepare_post_comments() {

    $comments = get_approved_comments(get_the_ID());

    $comments_ouput = array();
    foreach($comments as $comment) {
        $content = $comment->comment_content;
        $comments_ouput[] = array(
            'ID'           => $comment->comment_ID,
            'author'       => $comment->comment_author,
            'author-email' => $comment->comment_author_email,
            'date'         => mysql2date('c', get_the_date()),
            'content'      => strip_tags($content),
            'html-content' => apply_filters('the_content', $content),
        );
    }

    return $comments_ouput;
}

function format_current_data($data) {

    global $format;
    switch ($format) {
        case "XML":
            return get_current_data_as_XML($data);
            break;
        case "JSON":
            return get_current_data_as_JSON($data);
            break;
        default:
            add_action('wp_enqueue_scripts', 'load_api_html_styles');
            add_filter('body_class', 'add_api_html_body_class');
            return $data;
    }
}

function load_api_html_styles() {

    wp_enqueue_style('api-html-style', get_template_directory_uri() . '/style.api.html.css');
}

function add_api_html_body_class($classes) {

    $classes[] = 'apipress-html';
    return $classes;
}

function get_current_data_as_XML($data) {

    $xml_output = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
    $data = add_xml_cdata($data);
    get_xml_from_array($data, $xml_output);
    return $xml_output->asXML();
}

function add_xml_cdata($data, $ancestor = 'ancestor') {

    $cdata_keys = array(
        'name', 'title', 'author', 'tags', 'categories', 'excerpt', 'content', 'html-content',
        'key', 'values', 'caption', 'description', 'html-description');

    foreach($data as $key => $value) {
        if (is_array($value)) {
            $value = add_xml_cdata($value, $key);
        } else {
            if ((is_numeric($key) && in_array($ancestor, $cdata_keys)) || in_array($key, $cdata_keys)) {
                $value = '<![CDATA[' . $value . ']]>';
            }
        }

        $data[$key] = $value;
    }

    return $data;
}

function get_xml_from_array($array, &$xml, $subnode_name = 'subnode') {

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                $subnode = $xml->addChild("$key");
                get_xml_from_array($value, $subnode, get_xml_collections_subnodes_names($key));
            } else {
                $subnode = $xml->addChild("$subnode_name");
                $subnode->addAttribute("key", "$key");
                get_xml_from_array($value, $subnode);
            }
        } else {
            if (!is_numeric($key)) {
                $xml->addChild("$key", htmlspecialchars("$value"));
            } else {
                $subnode = $xml->addChild("$subnode_name", htmlspecialchars("$value"));
                $subnode->addAttribute("key", "$key");
            }
        }
    }
}

function get_xml_collections_subnodes_names($collection_name) {

    switch ($collection_name) {
        case "resources":
            $subnode_name = 'resource';
            break;
        case "items":
            $subnode_name = 'item';
            break;
        case "custom-fields":
            $subnode_name = 'custom-field';
            break;
        case "values":
            $subnode_name = 'value';
            break;
        case "attachments":
            $subnode_name = 'attachment';
            break;
        case "comments":
            $subnode_name = 'comment';
            break;
    }

    return $subnode_name;
}

function get_current_data_as_JSON($data) {

    return json_encode($data);
}

?>
