<?php
/**
 * Register widgetized area and update sidebar with default widgets.
 */
function h1_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'h1-theme' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'h1_widgets_init' );
