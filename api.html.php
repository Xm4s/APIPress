<?php

if (is_archive()) {
	$data = get_current_data(get_query_var('cat'));
} else if (is_single() || is_page()) {
	$data = get_current_post();
}

$resources = $data['resources'];
$items = $data['items'];
$html_output = '';

if (0 !== count($resources)) {
	$html_output .= '<h2 class="api-title">Resources</h2>';
	$html_output .= '<ul class="api-list api-resources-list">';
	foreach($resources as $resource) {
		$html_output .= '<li class="api-list-item api-resource">';
		$html_output .= '<h3><a href="' . $resource['link'] . '">' . $resource['name'] . '</a></h3>';
		$html_output .= '<dl>';
		$html_output .= '<dt>ID :</dt><dd>' . $resource['ID'] . '</dd>';
		$html_output .= '<dt>Parent ID :</dt><dd>' . $resource['parent'] . '</dd>';
		$html_output .= '<dt>Slug :</dt><dd>' . $resource['slug'] . '</dd>';
		$html_output .= '<dt>Description :</dt><dd>' . $resource['description'] . '</dd>';
		$html_output .= '<dt>Items count :</dt><dd>' . $resource['count'] . '</dd>';
		$html_output .= '</dl>';
		$html_output .= '</li>';
	}
	$html_output .= '</ul>';
}

if (0 !== count($items)) {
	$html_output .= '<h2 class="api-title">Items</h2>';
	$html_output .= '<ul class="api-list api-items-list">';
	foreach($items as $item) {
		$html_output .= '<li class="api-list-item api-item">';
		$html_output .= '<h3><a href="' . $item['link'] . '">' . $item['title'] . '</a></h3>';
		$html_output .= '<dl>';
		$html_output .= '<dt>ID :</dt><dd>' . $item['ID'] . '</dd>';
		$html_output .= '<dt>Slug :</dt><dd>' . $item['slug'] . '</dd>';

		if (null !== $item['author'])     $html_output .= '<dt>Author :</dt><dd>' . $item['author'] . '</dd>';
		if (null !== $item['date'])       $html_output .= '<dt>Date :</dt><dd>' . $item['date'] . '</dd>';
		if (null !== $item['tags'])       $html_output .= '<dt>Tags :</dt><dd>' . $item['tags'] . '</dd>';
		if (null !== $item['categories']) $html_output .= '<dt>Categories :</dt><dd>' . $item['categories'] . '</dd>';
		if (null !== $item['excerpt'])    $html_output .= '<dt>Excerpt :</dt><dd>' . $item['excerpt'] . '</dd>';
		if (null !== $item['content'])    $html_output .= '<dt>Content :</dt><dd>' . $item['content'] . '</dd>';

		if (null !== $item['custom-fields']) {
			$html_output .= '<dt>Custom fields =></dt><dd><dl>';
			foreach($item['custom-fields'] as $custom) {
				$html_output .= '<dt>' . $custom['key'] . ' :</dt><dd>' . join(', ', $custom['values']) . '</dd>';
			}
			$html_output .= '</dl></dd>';
		}

		if (null !== $item['attachments']) {
			$html_output .= '<dt>Attachments =></dt><dd><ul class="api-sublist">';
			foreach($item['attachments'] as $attachment) {
				$html_output .= '<li class="api-sublist-item">';
				$html_output .= '<a href="' . $attachment['link'] . '">' . $attachment['title'] . '</a>';
				$html_output .= '<dl>';
				$html_output .= '<dt>ID :</dt><dd>' . $attachment['ID'] . '</dd>';
				$html_output .= '<dt>Caption :</dt><dd>' . $attachment['caption'] . '</dd>';
				$html_output .= '<dt>Description :</dt><dd>' . $attachment['description'] . '</dd>';
				$html_output .= '</dl></li>';
			}
			$html_output .= '</ul></dd>';
		}

		if (null !== $item['comments']) {
			$html_output .= '<dt>Comments =></dt><dd><ul class="api-sublist">';
			foreach($item['comments'] as $comment) {
				$html_output .= '<li class="api-sublist-item"><dl>';
				$html_output .= '<dt>ID :</dt><dd>' . $comment['ID'] . '</dd>';
				$html_output .= '<dt>Author :</dt><dd>' . $comment['author'] . '</dd>';
				$html_output .= '<dt>Author email :</dt><dd>' . $comment['author-email'] . '</dd>';
				$html_output .= '<dt>Date :</dt><dd>' . $comment['date'] . '</dd>';
				$html_output .= '<dt>Content :</dt><dd>' . $comment['content'] . '</dd>';
				$html_output .= '</dl></li>';
			}
			$html_output .= '</ul></dd>';
		}

		$html_output .= '</dl>';
		$html_output .= '</li>';
	}
	$html_output .= '</ul>';
}

if ('' === $html_output) $html_output = '<h2 class="api-title">No elements found</h2>';

get_header();
echo $html_output;
get_footer();

?>
