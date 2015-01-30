<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package H1 Theme
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function h1_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'h1_jetpack_setup' );