<?php
/**
 * Search part
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php villenoir_post_thumbnail(); ?>

		<div class="gg-offset-content">

			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<?php villenoir_posted_on_summary();	?>
			</header><!-- .entry-header -->

		</div><!-- .gg-offset-content -->

</article><!-- article -->