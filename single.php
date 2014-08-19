<?php
/**
 * The Template for displaying all single posts.
 *
 * @package H1 Theme
 */

get_header(); ?>

	<?php get_template_part( 'parts/content', 'header' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'parts/entry', 'single' ); ?>

			<?php h1_post_nav(); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // end of the loop. ?>

	<?php get_template_part( 'parts/content', 'footer' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>