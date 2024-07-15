<?php
/**
 * @package WordPress
 * @subpackage villenoir
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	$images = _get_field('gg_post_format_gallery');

	if( $images ): ?>
	    <ul class="gg-post-format-gallery gg-slick-carousel" data-mousewheel="false" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "arrows": true, "infinite": true, "adaptiveHeight": true, "rtl": <?php echo is_rtl() ? 'true' : 'false' ?> }'>
	        <?php foreach( $images as $image ): ?>
	            <li>
	                <a href="<?php echo esc_url($image['url']); ?>">
	                     <img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
	                </a>
	                <p><?php echo esc_html($image['caption']); ?></p>
	            </li>
	        <?php endforeach; ?>
	    </ul>
	<?php endif; ?>

	<div class="gg-offset-content">
		<header class="entry-header">
			<?php
				if ( is_single() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
				endif;
			?>
			<?php villenoir_posted_on_summary();	?>
		</header><!-- .entry-header -->

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
		

		<?php if ( is_single() ) : ?>
		<footer class="entry-meta">
			<?php villenoir_entry_meta(); ?>
			<?php edit_post_link( esc_html__( 'Edit', 'villenoir' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
		<?php endif; ?>
			
	</div>


</article><!-- #post -->


