<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
	<?php
	if ( ! villenoir_is_extension_activated('Woocommerce_German_Market') &&  _get_field('gg_product_products_price','option', true) === true ) {
		echo $product->get_price_html();
	}
	?>
	<?php
	// Get the bottle size
	$bottle_size = get_post_meta( get_the_ID(), '_bottle_size_field', true );
	//Check if bottle size field is hidden from the theme options
	if ( _get_field('gg_store_bottle_size_field','option', true) !== true ) {
		$bottle_size = false;
	}
	?>

	<?php if ( $bottle_size ) : ?>
		<span class="bottle-size"><?php echo esc_html($bottle_size); ?></span>
	<?php endif; ?>
</p>