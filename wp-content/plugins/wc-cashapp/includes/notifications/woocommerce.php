<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_notices', function () {
	echo '<div class="error"><p><strong>Checkout with Cash App on Woocommerce requires WooCommerce to be installed and active.</strong> <a href="' . esc_html(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">Download and Activate WooCommerce here</a></p></div>';
} );

?>