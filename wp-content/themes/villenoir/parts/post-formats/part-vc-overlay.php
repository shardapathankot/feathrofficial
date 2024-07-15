<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('gg-vc-posts-grid-default-overlay'); ?>>

		<?php villenoir_post_thumbnail(); ?>

		<header class="entry-header">
			<div class="entry-header-wrapper">
				<?php echo '<time class="updated" datetime="'. get_the_time( 'c' ) .'">'. get_the_date() .'</time>'; ?>
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</div>
		</header><!-- .entry-header -->

</article><!-- article -->
