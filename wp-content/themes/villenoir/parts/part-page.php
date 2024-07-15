<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if (!is_search()) : ?>
	
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'villenoir' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	
	<?php else: ?>
	
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	
	<?php endif; ?>

</article><!-- #post -->