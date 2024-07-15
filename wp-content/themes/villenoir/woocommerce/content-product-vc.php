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
?>


<?php
/**
 * woocommerce_before_shop_loop_item hook.
 *
 * @hooked woocommerce_template_loop_product_link_open - 10
 */
do_action( 'woocommerce_before_shop_loop_item' );

/**
 * woocommerce_before_shop_loop_item_title hook.
 *
 * @hooked woocommerce_show_product_loop_sale_flash - 10
 * @hooked woocommerce_template_loop_product_thumbnail - 10
 */
do_action( 'woocommerce_before_shop_loop_item_title' );

/**
 * woocommerce_shop_loop_item_title hook.
 *
 * @hooked woocommerce_template_loop_product_title - 10
 */
do_action( 'woocommerce_shop_loop_item_title' );

/**
 * woocommerce_after_shop_loop_item_title hook.
 *
 * @hooked woocommerce_template_loop_rating - 5
 * @hooked woocommerce_template_loop_price - 10
 */
do_action( 'woocommerce_after_shop_loop_item_title' );

/**
 * woocommerce_after_shop_loop_item hook.
 *
 * @hooked woocommerce_template_loop_product_link_close - 5
 * @hooked woocommerce_template_loop_add_to_cart - 10
 */
do_action( 'woocommerce_after_shop_loop_item' );
?>