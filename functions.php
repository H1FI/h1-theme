<?php
/**
 * H1 Theme functions and definitions
 *
 * @package H1 Theme
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'h1_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function h1_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on H1 Theme, use a find and replace
	 * to change 'h1-theme' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'h1-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'h1-theme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	//add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'h1_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // h1_setup
add_action( 'after_setup_theme', 'h1_setup' );

/**
 * Enqueue scripts and styles.
 */
function h1_scripts() {

	/**
	 * Directories for scripts and styles.
	 */
	$css_dir = get_stylesheet_directory_uri() . '/assets/styles/css';
	$js_dir  = get_stylesheet_directory_uri() . '/assets/js/';

	/**
	 * Register scripts.
	 */
	wp_register_script( 'h1-js', $js_dir . '/min/built.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'h1-js-dev', $js_dir . '/dev/built.js', array( 'jquery' ), null, true );

	/**
	 * Register styles.
	 */
	wp_register_style( 'h1-stylesheet', $css_dir . '/app.css' );
	wp_register_style( 'h1-stylesheet-legacy', $css_dir . '/app-no-mq.css' ); // No mediaqueries, px instead of rem

	/**
	 * Enqueue styles.
	 */
	wp_enqueue_style( 'h1-stylesheet' );

	global $wp_styles;
	$wp_styles->registered['h1-stylesheet-legacy']->add_data( 'conditional', 'lt IE 9' ); // Add legacy CSS for IE8 and below.


	/**
	 * Enqueue scripts.
	 */
	wp_enqueue_script( 'jquery' );

	if ( defined( 'WP_ENV' ) && WP_ENV == 'development' ) {
		wp_enqueue_script( 'h1-js-dev' );
	} else {
		wp_enqueue_script( 'h1-js' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'h1_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/functions/custom-header.php';

/**
 * Define widget areas and possible custom widgets.
 */
require get_stylesheet_directory() . '/functions/widgets.php';

/**
 * Load Foundation compatibility file.
 */
require get_template_directory() . '/functions/foundation.php';

/**
 * Navigation-related functions to be used in templates.
 */
require get_template_directory() . '/functions/navigation.php';

/**
 * Load Custom Walkers.
 */
require get_template_directory() . '/functions/walkers.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/functions/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/functions/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/functions/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
// require get_template_directory() . '/functions/jetpack.php';
