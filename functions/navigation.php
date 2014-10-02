<?php

if ( ! function_exists( 'h1_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function h1_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'h1-theme' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'h1-theme' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'h1-theme' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'h1_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function h1_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'h1-theme' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'h1-theme' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'h1-theme' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/** 
 * Sub navigation, based on code from Simple Section Navigation by jakemgold and thinkoomph, http://wordpress.org/extend/plugins/simple-section-navigation/
 * 
 * 
 * @copyright Daniel Koskinen, Jake Goldman, thinkoomph
 * @author Daniel Koskinen
 * @version 1.2
 **/
function h1_section_nav( $args = array() ) {

		global $post;

		// Set sensible defaults
		$defaults = array(
			'show_all' => false,
			'show_top' => false, // whether to repeat top level parent
			'excluded' => array(), // id's of pages to be excluded
			'exclude_list' => '',
			'menu_class' => 'sidemenu',
			'link_before' => '',
			'link_after' => ''
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// initialise variables
		$show_all = $args['show_all'];
		$show_top = $args['show_top'];
		$excluded = $args['excluded'];
		$exclude_list = $args['exclude_list'];
		$menu_class = $args['menu_class'];
		$link_before = $args['link_before'];
		$link_after = $args['link_after'];
		
		if ( is_search() || is_404() ) return false; //doesn't apply to search or 404 page
				
		$post_ancestors = ( isset($post->ancestors) ) ? $post->ancestors : get_post_ancestors($post); //get the current page's ancestors either from existing value or by executing function
		$top_page = $post_ancestors ? end($post_ancestors) : $post->ID; //get the top page id
		
		$thedepth = 0; //initialize default variables
		
		if( !$show_all ) 
		{	
			$ancestors_me = implode( ',', $post_ancestors ) . ',' . $post->ID;
			
			//exclude pages not in direct hierarchy
			foreach ($post_ancestors as $anc_id) 
			{
				if ( in_array($anc_id,$excluded) ) return false; //if ancestor excluded, and hide on excluded, leave
				
				$pageset = get_pages(array( 'child_of' => $anc_id, 'parent' => $anc_id, 'exclude' => $ancestors_me ));
				foreach ($pageset as $page) {
					$excludeset = get_pages(array( 'child_of' => $page->ID, 'parent' => $page->ID ));
					foreach ($excludeset as $expage) { $exclude_list .= ',' . $expage->ID; }
				}
			}
			
			$thedepth = count($post_ancestors)+1; //prevents improper grandchildren from showing
		}		
		
		//get the list of pages, including only those in our page list
		// uncomment walker if you want custom classes
		$children = wp_list_pages(array( 
			'title_li' => '', 
			'echo' => 0, 
			'depth' => $thedepth, 
			'child_of' => $top_page, 
			'exclude' => $exclude_list,
			'link_before' => $link_before,
			'link_after' => $link_after,
			// 'walker' => new H1_Walker_Page( 'sidemenu__', $top_page ) 
			));

		if( !$children ) return false; 	//if there are no pages in this section, leave the function
		
		$sect_title = apply_filters( 'the_title', get_the_title($top_page), $top_page );
		if ( $show_top ) {
			if ( $link_before != '')
				$sect_title = $link_before . $sect_title;
			if ( $link_after != '' )
				$sect_title = $sect_title . $link_after;

			$headclass = ( $post->ID == $top_page ) ? $menu_class . "__item--current" : $menu_class . "__item--current_ancestor";
			if ( $post->post_parent == $top_page ) $headclass .= $menu_class . "__item--current_ancestor";
			$sect_title = '<a href="' . get_page_link($top_page) . '" id="toppage-' . $top_page . '" class="' . $headclass . '">' . $sect_title . '</a>';	
		}
	  	
				
		if ( $menu_class != '' )
			echo "<ul class=\"". $menu_class ."__navlist\">";
		else echo "<ul>";
		if ($show_top) echo '<li class="'.$headclass.'">'.$sect_title.'</li>';
		echo apply_filters( 'simple_section_page_list', $children );
		echo "</ul>";
}