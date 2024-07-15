<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('gg-vc-posts-grid-default'); ?>>

		<header class="entry-header">
			<?php echo '<time class="updated" datetime="'. get_the_time( 'c' ) .'">'. get_the_date() .'</time>'; ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<?php villenoir_post_thumbnail(); ?>

		<div class="entry-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				esc_html__( 'Continue reading %s', 'villenoir' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );
			?>
		</div><!-- .entry-summary -->

</article><!-- article -->
