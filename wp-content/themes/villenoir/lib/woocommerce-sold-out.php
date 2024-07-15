<?php
/**
 * WooCommerce Sold Out Products
 *
*/
add_action( 'woocommerce_before_single_product_summary', 'villenoir_sold_out_products_flash', 9 );
add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_sold_out_products_flash', 9 );

/**
 * add sold out text to the product image
 */
function villenoir_sold_out_products_flash() {
	global $product, $post;
	
    if ( ! $product->is_in_stock() ) {
		echo apply_filters( 'woocommerce_sale_flash', '<span class="soldout">'.esc_html__( 'Sold Out!', 'villenoir' ).'</span>', $post, $product );
	}
}

remove_action( 'woocommerce_before_single_product_summary', 'villenoir_sold_out_products_flash', 9 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_sold_out_products_flash', 9 );
add_action( 'woocommerce_before_single_product_summary', 'villenoir_sold_out_products_flash_mod', 9 );
add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_sold_out_products_flash_mod', 9 );
/**
 * add sold out text to the product image
 */
function villenoir_sold_out_products_flash_mod() {
    global $post,$product;
    
    if ( ! $product->is_in_stock() ) {
        echo apply_filters( 'woocommerce_sale_flash', '<span class="soldout">'.esc_html__( 'Sold Out!', 'villenoir' ).'</span>', $post,$product );
    }
}


?>