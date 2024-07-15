<?php
/**
 * @package WordPress
 * @subpackage villenoir
 */
?>

<div class="col-xs-12 col-md-12">
	
    <?php 
    $gallery_images = _get_field( 'gg_gallery_images');
    if($gallery_images) :
    ?>

	<div class="gallery-images-wrapper">

		<?php foreach ( $gallery_images as $gallery_image ) : ?>
		
		<div class="gallery-image">
			<img class="wp-post-image" src="<?php echo esc_url($gallery_image['url']); ?>" alt="<?php echo esc_html($gallery_image['alt']); ?>" />		
			<p><?php echo esc_html($gallery_image['caption']); ?></p>
		</div>

		<?php endforeach; ?>
	</div><!-- /.gallery-images-wrapper -->
	<?php endif; ?>

</div><!-- /.col-xs-12 .col-md-12 -->


<div class="col-xs-12 col-md-12">
	<div class="entry-content">	
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . esc_html__( 'Pages:', 'villenoir' ), 'after' => '</div>' ) ); ?>
	</div>
</div>
