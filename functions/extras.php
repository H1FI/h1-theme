<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package H1 Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
// add_filter( 'body_class', 'h1_body_classes' );
function h1_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}

/**
 * Remove Heading 1 from TinyMCE.
 *
 * Remove H1 from the available headings, because it is usually reserved to the post title.
 */
add_filter( 'tiny_mce_before_init', 'h1_remove_heading_1_tinymce' );
function h1_remove_heading_1_tinymce( $init ) {
	$init['block_formats'] = "Paragraph=p;Address=address;Pre=pre;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6";
	return $init;
}