<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package H1 Theme
 */

if ( ! function_exists( 'h1_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
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
 *
 * @return void
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

			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'h1-theme' ) ); ?>
			<?php next_post_link(     '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'h1-theme' ) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'h1_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function h1_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'h1-theme' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'h1-theme' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) { echo get_avatar( $comment, $args['avatar_size'] ); } ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'h1-theme' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'h1-theme' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit', 'h1-theme' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'h1-theme' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
				comment_reply_link( array_merge( $args, array(
					'add_below' => 'div-comment',
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<div class="reply">',
					'after'     => '</div>',
				) ) );
			?>
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for h1_comment()

if ( ! function_exists( 'h1_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function h1_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'h1-theme' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 */
function h1_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so h1_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so h1_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in h1_categorized_blog.
 */
function h1_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'h1_category_transient_flusher' );
add_action( 'save_post',     'h1_category_transient_flusher' );

/** 
 * Sub navigation, based on code from Simple Section Navigation by jakemgold and thinkoomph, http://wordpress.org/extend/plugins/simple-section-navigation/
 * 
 * @copyright Daniel Koskinen, Jake Goldman, thinkoomph
 * @author Daniel Koskinen
 * @version 1.1
 **/
function h1_section_nav( $args = array() ) {

		global $post;

		// Set sensible defaults
		$defaults = array(
			'show_all' => false,
			'show_top' => false, // whether to repeat top level parent
			'excluded' => array(), // id's of pages to be excluded
			'exclude_list' => '',
			'menu_class' => '',
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
		$children = wp_list_pages(array( 
			'title_li' => '', 
			'echo' => 0, 
			'depth' => $thedepth, 
			'child_of' => $top_page, 
			'exclude' => $exclude_list,
			'link_before' => $link_before,
			'link_after' => $link_after
			));

		if( !$children ) return false; 	//if there are no pages in this section, leave the function
		
		$sect_title = apply_filters( 'the_title', get_the_title($top_page), $top_page );
		if ( $show_top ) {
			if ( $link_before != '')
				$sect_title = $link_before . $sect_title;
			if ( $link_after != '' )
				$sect_title = $sect_title . $link_after;

			$headclass = ( $post->ID == $top_page ) ? "current_page_item" : "current_page_ancestor";
			if ( $post->post_parent == $top_page ) $headclass .= " current_page_parent";
			$sect_title = '<a href="' . get_page_link($top_page) . '" id="toppage-' . $top_page . '" class="' . $headclass . '">' . $sect_title . '</a>';	
		}
	  	
				
		if ( $menu_class != '' )
			echo "<ul class=\"". $menu_class ."\">";
		else echo "<ul>";
		if ($show_top) echo '<li class="'.$headclass.'">'.$sect_title.'</li>';
		echo apply_filters( 'simple_section_page_list', $children );
		echo "</ul>";
}
