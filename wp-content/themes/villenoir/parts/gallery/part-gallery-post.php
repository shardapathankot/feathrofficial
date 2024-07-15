<?php
/**
 * @package WordPress
 * @subpackage villenoir
 */
?>

<?php
$gallery_short_desc          = _get_field('gg_gallery_short_description');
$select_gallery_open_type    = _get_field('gg_select_gallery_open_type');

$gallery_item_lightbox_image = _get_field( 'gg_gallery_item_lightbox_image');
$gallery_item_lightbox_video = _get_field('gg_gallery_item_lightbox_video');
$gallery_item_custom_url     = _get_field('gg_gallery_item_custom_url');

switch ($select_gallery_open_type) {
    case "lightbox_image":
        if ($gallery_item_lightbox_image) {
            $gallery_hover_icn = '<a class="lightbox-el link-wrapper" href="'.esc_url($gallery_item_lightbox_image).'"></a><i class="fa fa-search"></i>';
        }
    break;

    case "lightbox_video":
        if ($gallery_item_lightbox_video) {
            $gallery_hover_icn = '<a class="lightbox-el link-wrapper lightbox-video" href="'.esc_url($gallery_item_lightbox_video).'"></a><i class="fa fa-video-camera"></i>';
        }
    break;

    case "custom_url":
        if ($gallery_item_custom_url) {
            $gallery_hover_icn = '<a class="link-wrapper" href="'.esc_url($gallery_item_custom_url).'"></a><i class="fa fa-external-link"></i>';
        }
    break;

    case "separate_page":
        $gallery_hover_icn = '<a class="link-wrapper" href="'.esc_url(get_permalink()).'"></a><i class="fa fa-folder"></i>';
    break;    
}
?>

<figure class="effect-goliath">
    <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) ); ?>
    <figcaption>
        <?php echo wp_kses_post($gallery_hover_icn); ?>
        <h2><?php echo get_the_title(); ?></h2>
        <?php if ($gallery_short_desc) : ?>
        <p><?php echo esc_html($gallery_short_desc); ?></p>
        <?php endif; ?>
    </figcaption>
</figure>