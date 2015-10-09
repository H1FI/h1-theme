<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.me/
 *
 * @package H1_Theme
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function h1_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'h1_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function h1_jetpack_setup
add_action( 'after_setup_theme', 'h1_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function h1_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/entry', get_post_format() );
	}
} // end function h1_infinite_scroll_render
