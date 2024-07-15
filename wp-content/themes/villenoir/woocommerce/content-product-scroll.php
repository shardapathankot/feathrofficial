<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

$image_url = wp_get_attachment_url( $product->get_image_id() );
?>

<div class="product-image-wrapper">
	<div class="product-image" style="background-image:url(<?php echo $image_url; ?>);">
		<a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
	</div>
</div>

<div  class="product-meta-wrapper" data-scroll="" data-scroll-speed="1">
	<a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
	<?php villenoir_style_6_year_above_title(); ?>

	<?php if ( $price_html = $product->get_price_html() ) : ?>
		<span class="price"><?php echo $price_html; ?></span>
	<?php endif; ?>

	<?php villenoir_style_6_svg_link_to_product(); ?>
</div>