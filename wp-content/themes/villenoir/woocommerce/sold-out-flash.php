<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $post;
?>
<?php if ( ! $product->is_in_stock() ) : ?>

	<?php echo apply_filters( 'woocommerce_sold_out_flash', '<span class="soldout">'.esc_html__( 'Sold Out!', 'villenoir' ).'</span>', $post, $product ); ?>

<?php endif; ?>