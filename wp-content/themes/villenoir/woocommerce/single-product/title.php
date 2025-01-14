<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
// Get the year
$wine_year = get_post_meta( get_the_ID(), '_year_field', true );
//Check if year field is hidden from the theme options
if ( _get_field('gg_store_year_field','option', true) !== true ) {
	$wine_year = false;
}

?>

<?php if ( $wine_year ) : ?>
	<span class="year"><?php echo esc_html($wine_year); ?></span>
<?php endif; ?>

<?php the_title( '<h1 class="product_title entry-title">', '</h1>' ); ?>
