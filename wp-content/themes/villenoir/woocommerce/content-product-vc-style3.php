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

// Get the year
$wine_year = get_post_meta( get_the_ID(), '_year_field', true );
$year_field_label = _get_field('gg_store_year_field_label', 'option', esc_html__( 'Year', 'villenoir' ) );

//Check if year field is hidden from the theme options
if ( _get_field('gg_store_year_field','option', true) !== true ) {
    $wine_year = false;
}

?>


<figure class="gg-product-image-wrapper effect-zoe">
	<?php echo woocommerce_get_product_thumbnail(); ?>
	<figcaption class="product-image-overlay">
		<span class="product-overlay-meta">

			<?php if ( $wine_year ) : ?>
				<span class="year"><?php echo esc_html($wine_year); ?></span>
			<?php endif; ?>
			<a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"></a>
			
			<?php echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h2>'; ?>           
			
			<?php if ( $price_html = $product->get_price_html() ) : ?>
			<?php if ( _get_field('gg_store_products_price','option', true) === true ) : ?>    
				<span class="price"><?php echo $price_html; ?></span>
			<?php endif; ?>
			<?php endif; ?>
			
        	<?php echo apply_filters(
				'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
				sprintf(
					'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
					esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
					isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
					esc_html( $product->add_to_cart_text() )
				),
				$product,
				$args
			); ?>
		</span>
	</figcaption>
</figure>