<?php
/**
 * Custom Menu Walker
 * 
 * Enables creating an completely custom class structure for nav menus, eg.
 * 
 * 	.prefix__list
 *  .prefix__item
 *  .prefix__link
 *     etc...
 * 
 * Use this from the wp_nav_menu walker parameter like this: 
 * 
 * 		'walker' => new H1_Walker_Nav_Menu( 'prefix__' )
 * 
 *
 */
class H1_Walker_Nav_Menu extends Walker_Nav_Menu {

	var $h1_custom_prefix;

	function __construct( $prefix ) {
		$this->h1_custom_prefix = esc_attr( $prefix );
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"{$this->h1_custom_prefix}subnav\">\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		/**
		 * Top menu class modifications
		 */
		$custom_classes = array();

		foreach ( $classes as $class ) {
			/**
			 * Is this a top level item or a submenu item?
			 */
			if ( 'menu-item' == $class && $item->menu_item_parent == 0 ) {
				$custom_classes[] = "{$this->h1_custom_prefix}item";

				if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ) {
					$custom_classes[] = "{$this->h1_custom_prefix}item--current";
				}
			} elseif ( 'menu-item' == $class && $item->menu_item_parent > 0 ) {
				$custom_classes[] = "{$this->h1_custom_prefix}subitem";

				if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ) {
					$custom_classes[] = "{$this->h1_custom_prefix}subitem--current";					
				}				
			}

			if ( 'menu-item-has-children' == $class ) {
				$custom_classes[] = "{$this->h1_custom_prefix}item--has_subnav";
			}

			if ( 'menu-item-home' == $class || "{$this->h1_custom_prefix}item--home" == $class ) {
				$custom_classes[] = "{$this->h1_custom_prefix}item--home";
			}

		}

		$class_names = join( ' ', $custom_classes );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's <li>.
		 *
		 * @since 3.0.1
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string $menu_id The ID that is applied to the menu item's <li>.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 */
		$id = $this->h1_custom_prefix .'item-' . $item->ID;
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$anchor_class = $item->menu_item_parent == 0 ? "{$this->h1_custom_prefix}link" : "{$this->h1_custom_prefix}sublink" ;

		$item_output = $args->before;

		$item_output .= '<a'. $attributes .' class="'. $anchor_class .'">';
		/** This filter is documented in wp-includes/post-template.php */
		$item_output .= $args->link_before . apply_filters( 'h1_nav_menu_item_title', apply_filters( 'the_title', $item->title, $item->ID ), $item, $args ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}	
}

/**
 * Custom walker for HTML list of pages.
 * 
 * Enables creating an completely custom class structure for nav menus, eg.
 * 
 * 	.prefix__list
 *  .prefix__item
 *  .prefix__link
 *     etc...*
 * 
 * @uses Walker_Page
 */
class H1_Walker_Page extends Walker_Page {

	var $h1_custom_prefix;
	var $h1_top_page;

	function __construct( $prefix, $top_page ) {
		$this->h1_custom_prefix = esc_attr( $prefix );
		$this->h1_top_page = $top_page;
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class='{$this->h1_custom_prefix}subnav'>\n";
	}

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';

		extract($args, EXTR_SKIP);
		
		$css_class = array();

		$is_top_level = $page->post_parent == 0 || $page->post_parent == $this->h1_top_page;

		if ( $is_top_level ) {
			$css_class = array( $this->h1_custom_prefix . 'item' );
			if( isset( $args['pages_with_children'][ $page->ID ] ) )
				$css_class[] =  $this->h1_custom_prefix . 'item--has_subnav';						
		}
		else {
			$css_class = array( $this->h1_custom_prefix . 'subitem' );
			if( isset( $args['pages_with_children'][ $page->ID ] ) )
				$css_class[] =  $this->h1_custom_prefix . 'subitem--has_subnav';				
		}

		if ( !empty($current_page) ) {
			$_current_page = get_post( $current_page );

			if ( in_array( $page->ID, $_current_page->ancestors ) )
				$css_class[] = $this->h1_custom_prefix . 'item--current_ancestor';
			if ( $page->ID == $current_page && $is_top_level ) {
				$css_class[] = $this->h1_custom_prefix . 'item--current';
			} elseif ( $page->ID == $current_page ) {
				$css_class[] = $this->h1_custom_prefix . 'subitem--current';
			}
			elseif ( $_current_page && $page->ID == $_current_page->post_parent )
				$css_class[] = $this->h1_custom_prefix . 'item--current_parent';
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = $this->h1_custom_prefix . 'item--current_parent';
		}

		/**
		 * Filter the list of CSS classes to include with each page item in the list.
		 */
		$css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title )
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );

		/**
		 * Set a custom class on the anchor link
		 */
		$anchor_class = $is_top_level ? "{$this->h1_custom_prefix}link" : "{$this->h1_custom_prefix}sublink" ;

		$output .= '<!--';
		$output .= print_r( $page, true );
		$output .= '-->';

		/** This filter is documented in wp-includes/post-template.php */
		$output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '" class="' . $anchor_class .'">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

		if ( !empty($show_date) ) {
			if ( 'modified' == $show_date )
				$time = $page->post_modified;
			else
				$time = $page->post_date;

			$output .= " " . mysql2date($date_format, $time);
		}
	}

}