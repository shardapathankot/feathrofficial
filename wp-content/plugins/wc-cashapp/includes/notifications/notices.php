<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_notices', function () {
	$gateway = new WC_Cash_App_Pay_Gateway();
	if (empty($gateway->SQ_Access_Token)) { return; }
	echo '<div class="notice wc-cashapp-admin-notice is-dismissible" style="display: block;">
	<p>Thanks for installing Checkout with Cash App on Woocommerce! To get started, <a href="' . esc_attr(admin_url('admin.php?page=wc-settings&tab=checkout&section=cash-app-pay')) . '">finish setting up the plugin »</a></p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button>
	</div>';
});

if( class_exists( 'WooCommerce_Square_Loader', false ) || is_plugin_active( 'WC-square/woocommerce-square.php' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice wc-cashapp-admin-notice is-dismissible" style="display: block;">
		<p>You might run into compatibility issues on the checkout page if Woocommerce Square and Checkout with Cash App on Woocommerce are in different modes since they both need the same Square Web SDK.</p>
		<p>Connect to Square in production with both plugins to fix those issues</p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button>
		</div>';
	});
}

add_action( 'admin_notices', function () {
	$gateway = new WC_Cashapp_Gateway();
	if (empty($gateway->ReceiverCashApp)) { return; }
	echo '<div class="notice wc-cashapp-admin-notice is-dismissible" style="display: block;">
	<p>Thanks for installing Checkout with Cash App on Woocommerce! To get started, <a href="' . esc_attr(admin_url('admin.php?page=wc-settings&tab=checkout&section=cashapp')) . '">finish setting up the plugin »</a></p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button>
	</div>';
});

?>