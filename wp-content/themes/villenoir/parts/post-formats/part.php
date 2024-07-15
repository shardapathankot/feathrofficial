<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<?php
global $blog_list_thumbnail, $blog_list_content, $blog_layout;
$post_featured_image = _get_field('gg_post_featured_image','','post_body');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		

		<?php villenoir_post_thumbnail($post_featured_image); ?>

		<?php if ( !is_single() ) : ?>
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->
		<?php endif; ?>

		<div class="post-meta">
		<?php villenoir_posted_on_summary(); ?>
		</div>

		<?php if ( ! is_search() && ! has_excerpt() ) : ?>
		<div class="entry-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				esc_html__( 'Continue reading %s', 'villenoir' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'villenoir' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'villenoir' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
		</div><!-- .entry-content -->

		<?php else: ?>

		<div class="entry-summary">
			<?php
			the_excerpt();
			echo '<a class="continue-reading" href="' . get_permalink() . '">Continue Reading</a>';
			?>
			
		</div><!-- .entry-summary -->
		
		<?php endif; ?>

		<?php if ( is_single() ) : ?>
		<footer class="entry-meta">
			<?php villenoir_entry_meta(); ?>
			<?php edit_post_link( esc_html__( 'Edit', 'villenoir' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
		<?php endif; ?>


</article><!-- article -->
