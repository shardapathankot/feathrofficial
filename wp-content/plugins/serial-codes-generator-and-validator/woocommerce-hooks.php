<?php
include(plugin_dir_path(__FILE__)."init_file.php");
if (!defined('SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER')) define( 'SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER', '4.0' );

/*
 * Add a tab for listing serial code list
 */
add_filter('woocommerce_product_data_tabs', 'sngmbh_woo_product_settings_tabs', 98 );
function sngmbh_woo_product_settings_tabs( $tabs ){

	//unset( $tabs['inventory'] );

	$tabs['sngmbh_serial_code_woo'] = array(
		'label'    => 'Serial Codes',
		'title'    => 'Serial Codes',
		'target'   => 'sngmbh_woo_product_data',
		'class'		=> ['show_if_simple', 'show_if_variable']
	);
	return $tabs;
}

/*
 * Tab content
 */
add_action( 'woocommerce_product_data_panels', 'sngmbh_woo_product_panels' );
function sngmbh_woo_product_panels(){

		global $sngmbhSerialcodesValidator;
		$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();
		$lists = $sngmbhSerialcodesValidator_AdminSettings->getLists();

		$product_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
		$is_serial = !empty(get_post_meta($product_id, 'sngmbh_serial_code_list', true));

		wp_register_script(
			'SngmbhSerialcodesValidator_WC_backend',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . 'wc_backend.js?_v='.$sngmbhSerialcodesValidator->getPluginVersion(),
			array( 'jquery', 'jquery-blockui' ),
			(current_user_can("administrator") ? time() : $sngmbhSerialcodesValidator->getPluginVersion()),
			true );
		wp_localize_script(
 			'SngmbhSerialcodesValidator_WC_backend',
			'Ajax_sngmbhSerialcodesValidator_wc', // name der js variable
 			[
 				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'prefix'=>$sngmbhSerialcodesValidator->getPrefix(),
				'nonce' => wp_create_nonce( $sngmbhSerialcodesValidator->getPrefix() ),
				'action' => $sngmbhSerialcodesValidator->getPrefix().'_executeWCBackend',
				'product_id'=>$product_id,
				'is_serial'=>$is_serial,
				'order_id'=>0,
				'scope'=>'product',
				'_doNotInit'=>true,
            	'_max'=>$sngmbhSerialcodesValidator->getBase()->getMaxValues(),
            	'_isPremium'=>$sngmbhSerialcodesValidator->isPremium(),
            	'_isUserLoggedin'=>is_user_logged_in(),
            	'_backendJS'=>trailingslashit( plugin_dir_url( __FILE__ ) ) . 'backend.js?_v='.$sngmbhSerialcodesValidator->getPluginVersion(),
            	'_premJS'=>$sngmbhSerialcodesValidator->isPremium() ? $sngmbhSerialcodesValidator->getPremiumFunctions()->getJSBackendFile() : '',
            	'_divAreaId'=>'sngmbh_serial_code_list_format_area',
            	'formatterInputFieldDataId'=>'sngmbh_serial_code_list_formatter_values'
 			] // werte in der js variable
 			);
      	wp_enqueue_script('SngmbhSerialcodesValidator_WC_backend');


	echo '<div id="sngmbh_woo_product_data" class="panel woocommerce_options_panel hidden">';

	echo '<div class="options_group">';
	woocommerce_wp_checkbox( array(
		'id'          => 'sngmbh_serial_code_is_ticket',
		'value'       => get_post_meta( get_the_ID(), 'sngmbh_serial_code_is_ticket', true ),
		'label'       => 'Is a ticket sales - DEPRICATED',
		'description' => 'Activate this, to use the serial code as a ticket number. Please be aware, that this feature will be removed. It causes only issues within this plugin and is basically within the serial code domain.'
	) );
	echo "<p><b>Important:</b> You need to choose a code list below, to activate the ticket sale for this product.</p>";

	if (count($lists) == 0) {
		echo "<p><b>You have no code lists created!</b><br>You need to create a code list within the serial code admin area first, to choose a list from.</b></p>";
	}
	woocommerce_wp_select( array(
		'id'          => 'sngmbh_serial_code_list',
		'value'       => get_post_meta( get_the_ID(), 'sngmbh_serial_code_list', true ),
		'label'       => 'Code List',
		'description' => 'Choose a code list to activate auto-generating serial codes for each sold item',
		'desc_tip'    => true,
		'options'     => sngmbh_woo_get_lists()
	) );
	echo '</div>';

	echo '<div class="options_group">';
	woocommerce_wp_text_input([
		'id'				=> 'sngmbh_serial_code_ticket_start_date',
		'value'       		=> get_post_meta( get_the_ID(), 'sngmbh_serial_code_ticket_start_date', true ),
		'label'       		=> 'Start date event - DEPRICATED',
		'type'				=> 'date',
		'custom_attributes'	=> ['data-type'=>'date'],
		'description' 		=> 'Set this to have this printed on the ticket and prevent too early redeemed tickets. Tickets can be redeemed from that day on.',
		'desc_tip'    		=> true
	]);
	woocommerce_wp_text_input([
		'id'				=> 'sngmbh_serial_code_ticket_start_time',
		'value'       		=> get_post_meta( get_the_ID(), 'sngmbh_serial_code_ticket_start_time', true ),
		'label'       		=> 'Start time - DEPRICATED',
		'type'				=> 'time',
		'description' 		=> 'Set this to have this printed on the ticket.',
		'desc_tip'    		=> true
	]);
	woocommerce_wp_text_input([
		'id'				=> 'sngmbh_serial_code_ticket_end_date',
		'value'       		=> get_post_meta( get_the_ID(), 'sngmbh_serial_code_ticket_end_date', true ),
		'label'       		=> 'End date event - DEPRICATED',
		'type'				=> 'date',
		'custom_attributes'	=> ['data-type'=>'date'],
		'description' 		=> 'Set this to have this printed on the ticket and prevent later the ticket to be still valid. Tickets cannot be redeemed after that day.',
		'desc_tip'    		=> true
	]);
	woocommerce_wp_text_input([
		'id'				=> 'sngmbh_serial_code_ticket_end_time',
		'value'       		=> get_post_meta( get_the_ID(), 'sngmbh_serial_code_ticket_end_time', true ),
		'label'       		=> 'End time - DEPRICATED',
		'type'				=> 'time',
		'description' 		=> 'Set this to have this printed on the ticket.',
		'desc_tip'    		=> true
	]);
	woocommerce_wp_textarea_input( array(
		'id'          => 'sngmbh_serial_code_is_ticket_info',
		'value'       => get_post_meta( get_the_ID(), 'sngmbh_serial_code_is_ticket_info', true ),
		'label'       => 'Print this on the ticket - DEPRICATED',
		'description' => 'This optional information will be displayed on the ticket detail page.',
		'desc_tip'    => true
	));
	/*
	woocommerce_wp_checkbox( array(
		'id'          => 'sngmbh_serial_code_is_RTL',
		'value'       => get_post_meta( get_the_ID(), 'sngmbh_serial_code_is_RTL', true ),
		'label'       => 'Text is RTL',
		'description' => 'Activate this, to use language from right to left like on arabic language.'
	));
	*/
	echo '</div>';

	echo '<div class="options_group">';
	$sngmbh_serial_code_amount_per_item = intval(get_post_meta( get_the_ID(), 'sngmbh_serial_code_amount_per_item', true ));
	if ($sngmbh_serial_code_amount_per_item < 1) $sngmbh_serial_code_amount_per_item = 1;
	woocommerce_wp_text_input([
		'id'				=> 'sngmbh_serial_code_amount_per_item',
		'value'       		=> $sngmbh_serial_code_amount_per_item,
		'label'       		=> 'Amount of serial codes per item sale',
		'type'				=> 'number',
		'custom_attributes'	=> ['step'=>'1', 'min'=>'1'],
		'description' 		=> 'How many serial codes to assign if one product is sold?',
		'desc_tip'    		=> true
	]);
	echo '</div>';

	echo '<div class="options_group">';
	woocommerce_wp_checkbox( array(
	    'id'            => 'sngmbh_serial_code_list_formatter',
	    'label'			=> 'Use format settings',
	    'description'   => 'If active, then the format below will be used to generate serials during a purchase of this product.',
	    'value'         => get_post_meta( get_the_ID(), 'sngmbh_serial_code_list_formatter', true )
	) );
	echo '<input data-id="sngmbh_serial_code_list_formatter_values" name="sngmbh_serial_code_list_formatter_values" type="hidden" value="'.esc_js(get_post_meta( get_the_ID(), 'sngmbh_serial_code_list_formatter_values', true )).'">';
	echo '<div style="padding-top:10px;padding-left:10%;padding-right:20px;"><b>The serial code format settings.</b><br><i>This will override an existing and active global "serial code formatter pattern for new sales" and also any format settings from the group.</i><div id="sngmbh_serial_code_list_format_area"></div></div>';
	echo '</div>';

	echo '<div class="options_group">';
	if (version_compare( WC_VERSION, SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER, '<' )) {
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'For the Code List for sale restriction the plugin requires WooCommerce %1$s or greater to be installed and active. WooCommerce %2$s is not supported.', 'sngmbh-serial-codes-validator' ), SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER, WC_VERSION ) . '</strong></p></div>';
		echo '<p><strong>' . sprintf( esc_html__( 'For the Code List for sale restriction the plugin requires WooCommerce %1$s or greater to be installed and active. WooCommerce %2$s is not supported.', 'sngmbh-serial-codes-validator' ), SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER, WC_VERSION ) . '</strong></p>';
	} else {
		woocommerce_wp_select( array(
			'id'          => 'sngmbh_serial_code_list_sale_restriction',
			'value'       => get_post_meta( get_the_ID(), 'sngmbh_serial_code_list_sale_restriction', true ),
			'label'       => 'Code List for sale restriction ',
			'description' => 'Choose a code list to restrict the sale of this product to be done only with a working code from this list',
			'desc_tip'    => true,
			'options'     => sngmbh_woo_get_lists_sales_restriction()
		) );
		if ($sngmbhSerialcodesValidator->isPremium()) {
			woocommerce_wp_text_input([
				'id'				=> 'sngmbh_serial_code_restriction_extend_expiration_days',
				'value'       		=> get_post_meta( get_the_ID(), 'sngmbh_serial_code_restriction_extend_expiration_days', true ),
				'label'       		=> 'Amount of days to extend the serial code expiration',
				'type'				=> 'number',
				'custom_attributes'	=> ['step'=>'1', 'min'=>'0'],
				'description' 		=> 'If a serial is used as a restriction code for this product, then extend the expiration date for the amount of days. It will add them if left overy days are available, otherwise it will extend the serial from the day of the "completed" purchase. If set to zero, nothing will be changed.',
				'desc_tip'    		=> true
			]);
		}
	}
	echo '</div>';

	if ($sngmbhSerialcodesValidator->isPremium() && method_exists($sngmbhSerialcodesValidator->getPremiumFunctions(), 'sngmbh_woo_product_panels')) {
		$sngmbhSerialcodesValidator->getPremiumFunctions()->sngmbh_woo_product_panels(get_the_ID());
	}

	echo '</div>';

}
function sngmbh_woo_get_lists(){
	global $sngmbhSerialcodesValidator;
	$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();
	$lists = $sngmbhSerialcodesValidator_AdminSettings->getLists();
	$dropdown_list = array('' => 'Deactivate auto-generating serial code');
	foreach ($lists as $key => $list) {
		$dropdown_list[$list['id']] = $list['name'];
	}

	return $dropdown_list;
}
function sngmbh_woo_get_lists_sales_restriction() {
	global $sngmbhSerialcodesValidator;
	$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();
	$lists = $sngmbhSerialcodesValidator_AdminSettings->getLists();
	$dropdown_list = array('' => 'No restriction applied', '0' => 'Accept any existing code without limitation to a code list');
	foreach ($lists as $key => $list) {
		$dropdown_list[$list['id']] = $list['name'];
	}

	return $dropdown_list;
}

add_action( 'woocommerce_process_product_meta', 'sngmbh_woo_save_fields', 10, 2 );
function sngmbh_woo_save_fields( $id, $post ){

	if( !empty( $_POST['sngmbh_serial_code_list'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_list', sanitize_text_field($_POST['sngmbh_serial_code_list']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_list' );
	}

	// damit nicht alte Eintragungen gelöscht werden - so kann der kunde upgrade machen und alles ist noch da
	if (version_compare( WC_VERSION, SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER, '>=' )) {
		if( $_POST['sngmbh_serial_code_list_sale_restriction'] == '0' || !empty( $_POST['sngmbh_serial_code_list_sale_restriction'] ) ) {
			update_post_meta( $id, 'sngmbh_serial_code_list_sale_restriction', sanitize_text_field($_POST['sngmbh_serial_code_list_sale_restriction']) );
		} else {
			delete_post_meta( $id, 'sngmbh_serial_code_list_sale_restriction' );
		}
		if( !empty( $_POST['sngmbh_serial_code_restriction_extend_expiration_days'] ) ) {
			update_post_meta( $id, 'sngmbh_serial_code_restriction_extend_expiration_days', intval($_POST['sngmbh_serial_code_restriction_extend_expiration_days']) );
		} else {
			delete_post_meta( $id, 'sngmbh_serial_code_restriction_extend_expiration_days' );
		}
	}

	if( !empty( $_POST['sngmbh_serial_code_is_ticket'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_is_ticket', isset($_POST['sngmbh_serial_code_is_ticket']) ? 'yes' : 'no' );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_is_ticket' );
	}

	if( !empty( $_POST['sngmbh_serial_code_ticket_start_date'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_ticket_start_date', sanitize_text_field($_POST['sngmbh_serial_code_ticket_start_date']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_ticket_start_date' );
	}
	if( !empty( $_POST['sngmbh_serial_code_ticket_start_time'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_ticket_start_time', sanitize_text_field($_POST['sngmbh_serial_code_ticket_start_time']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_ticket_start_time' );
	}
	if( !empty( $_POST['sngmbh_serial_code_ticket_end_date'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_ticket_end_date', sanitize_text_field($_POST['sngmbh_serial_code_ticket_end_date']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_ticket_end_date' );
	}
	if( !empty( $_POST['sngmbh_serial_code_ticket_end_time'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_ticket_end_time', sanitize_text_field($_POST['sngmbh_serial_code_ticket_end_time']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_ticket_end_time' );
	}

	if( !empty( $_POST['sngmbh_serial_code_is_ticket_info'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_is_ticket_info', wp_kses_post($_POST['sngmbh_serial_code_is_ticket_info']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_is_ticket_info' );
	}

	if( isset($_POST['sngmbh_serial_code_amount_per_item']) && !empty( $_POST['sngmbh_serial_code_amount_per_item'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_amount_per_item', intval($_POST['sngmbh_serial_code_amount_per_item']));
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_amount_per_item' );
	}

	if( !empty( $_POST['sngmbh_serial_code_is_RTL'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_is_RTL', isset($_POST['sngmbh_serial_code_is_RTL']) ? 'yes' : 'no' );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_is_RTL' );
	}

	if( !empty( $_POST['sngmbh_serial_code_list_formatter'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_list_formatter', isset($_POST['sngmbh_serial_code_list_formatter']) ? 'yes' : 'no' );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_list_formatter' );
	}

	if( !empty( $_POST['sngmbh_serial_code_list_formatter_values'] ) ) {
		update_post_meta( $id, 'sngmbh_serial_code_list_formatter_values', sanitize_text_field($_POST['sngmbh_serial_code_list_formatter_values']) );
	} else {
		delete_post_meta( $id, 'sngmbh_serial_code_list_formatter_values' );
	}

	global $sngmbhSerialcodesValidator;
	if ($sngmbhSerialcodesValidator->isPremium() && method_exists($sngmbhSerialcodesValidator->getPremiumFunctions(), 'sngmbh_woo_save_fields')) {
		$sngmbhSerialcodesValidator->getPremiumFunctions()->sngmbh_woo_save_fields($id, $post);
	}
}

function sngmbh_add_serialcode_to_order_forItem($order_id, $order, $item_id, $item, $sngmbh_serial_code_list, $codeName, $codeListName) {
	$ret = [];
	if ($sngmbh_serial_code_list) {
		$product_id = $item->get_product_id();
		global $sngmbhSerialcodesValidator;
		$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();

		$product_formatter_values = "";
		if (get_post_meta($product_id, 'sngmbh_serial_code_list_formatter', true) == "yes") {
			$product_formatter_values = get_post_meta( $product_id, 'sngmbh_serial_code_list_formatter_values', true );
		}

		$isTicket = get_post_meta($product_id, 'sngmbh_serial_code_is_ticket', true) == "yes";

		$sngmbh_serial_code_amount_per_item = intval(get_post_meta( $product_id, 'sngmbh_serial_code_amount_per_item', true ));
		if ($sngmbh_serial_code_amount_per_item < 1) {
			$sngmbh_serial_code_amount_per_item = 1;
		}

		$quantity = $item->get_quantity();
		$quantity *= $sngmbh_serial_code_amount_per_item;

		$existingCode = wc_get_order_item_meta($item_id , $codeName, true);
		if (!empty($existingCode)) {
			$codes = explode(",", $existingCode);
			$quantity = $quantity - count($codes);
		}

		if ($quantity > 0) {
			$codes = [];
			for($a=0;$a<$quantity;$a++) {

				try {
					$newcode = $sngmbhSerialcodesValidator_AdminSettings->addCodeFromListForOrder($sngmbh_serial_code_list, $order_id, $product_id, $item_id, $product_formatter_values);

					if ($isTicket) {
						$sngmbhSerialcodesValidator_AdminSettings->setWoocommerceTicketInfoForCode($newcode);
					}

					$codes[] = $newcode;
				} catch(Exception $e) {
					// error handling
					// for now ignoring them
				}

			} // end for quantity
			if (count($codes) > 0) {
				$ret = $codes;
				wc_add_order_item_meta($item_id , $codeName, implode(",", $codes) ) ;
				wc_add_order_item_meta($item_id , $codeListName, $sngmbh_serial_code_list ) ;

				$autoUserRegisterToCodeWithOrder = $sngmbhSerialcodesValidator->getOptions()->isOptionCheckboxActive('autoUserRegisterToCodeWithOrder');
				if ($autoUserRegisterToCodeWithOrder) {
					$user_id = intval($order->get_user_id());
					if ($user_id > 0) {
						foreach($codes as $code) {
							$d = [
								"code"=>$code,
								"reg_userid"=>$user_id
							];
							$sngmbhSerialcodesValidator_AdminSettings->registerUserIdToCode($d);
						}
					}
				}
			}
			if ($isTicket) {
				wc_delete_order_item_meta( $item_id, '_sngmbh_serial_code_is_ticket' );
				wc_add_order_item_meta($item_id , '_sngmbh_serial_code_is_ticket', 1, true ) ;
			}
		}
	}
	return $ret;
}

// damit ich es bei mehreren calls einbinden kann. vor der serial wird geprüft ob nicht schon eine da ist
function sngmbh_add_serialcode_to_order($order_id) {

	if ( ! $order_id )
		return;

	$ret = [];

    // Getting an instance of the order object
	$order = wc_get_order( $order_id );

	if (SNGMBH::isOrderPaid($order)) {
		foreach ( $order->get_items() as $item_id => $item ) {
			if( $item->get_product_id() ){
				// normal product serial code
				$code_list_id = get_post_meta($item->get_product_id(), 'sngmbh_serial_code_list', true);
				if (!empty($code_list_id)) {
					$codes = sngmbh_add_serialcode_to_order_forItem($order_id, $order, $item_id, $item, $code_list_id, '_sngmbh_product_serial_code', '_sngmbh_serial_code_list');
					$ret = array_merge($ret, $codes);
				}
			}
		} // end foreach
	}
	return $ret;
}

add_action('woocommerce_order_status_changed', 'sngmbh_woocommerce_order_status_changed', 10, 3);
function sngmbh_woocommerce_order_status_changed($order_id,$old_status,$new_status) {
	if ($new_status != "refunded" && $new_status != "cancelled" && $new_status != "wc-refunded" && $new_status != "wc-cancelled") {
		sngmbh_add_serialcode_to_order($order_id); // maybe some codes were added manually
	}
	if ($new_status == "cancelled" || $new_status == "wc-cancelled" ) {
		global $sngmbhSerialcodesValidator;
		if ($sngmbhSerialcodesValidator == null) {
			$sngmbhSerialcodesValidator = new sngmbhSerialcodesValidator();
		}
		$order = wc_get_order( $order_id );
		foreach ( $order->get_items() as $item_id => $item ) {
			$sngmbhSerialcodesValidator->getWC()->woocommerce_delete_order_item($item_id);
		}
	} else {
		$ok_order_statuses = wc_get_is_paid_statuses();
		if (in_array($new_status, $ok_order_statuses)) {
			global $sngmbhSerialcodesValidator;
			if ($sngmbhSerialcodesValidator == null) {
				$sngmbhSerialcodesValidator = new sngmbhSerialcodesValidator();
			}
			$sngmbhSerialcodesValidator->getAdmin()->executeRistrictionExpirationExtensionForOrder($order_id);
		}
	}
}

add_action('woocommerce_thankyou', 'sngmbh_product_serial_code_thankyou_hook', 10, 1);
function sngmbh_product_serial_code_thankyou_hook( $order_id ) {
	sngmbh_add_serialcode_to_order($order_id);
}

add_filter('woocommerce_order_item_display_meta_key', 'sngmbh_filter_wc_order_item_display_meta_key', 20, 3 );
function sngmbh_filter_wc_order_item_display_meta_key( $display_key, $meta, $item ) {
	// display within the order

	if ( is_admin() && $item->get_type() === 'line_item'){
		// Change displayed label for specific order item meta key
		if($meta->key === '_sngmbh_product_serial_code' ) {
			$isTicket = $item->get_meta('_sngmbh_serial_code_is_ticket') == 1 ? true : false;
			if ($isTicket) {
				$display_key = __("Ticket Number", "woocommerce" );
			} else {
				$display_key = __("Serial Code", "woocommerce" );
			}
		}
		if($meta->key === '_sngmbh_serial_code_list' ) {
			$display_key = __("Serial Code List ID", "woocommerce" );
		}
		if ($meta->key === "_sngmbh_serial_code_is_ticket") {
			$display_key = __("Is Ticket");
		}

		// label for purchase restriction code
		if($meta->key === '_sngmbh_serial_code_list_sale_restriction' ) {
			global $sngmbhSerialcodesValidator;
			$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();

			$display_key = esc_attr($sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcRestrictPrefixTextCode'));
		}
		if($meta->key === '_sngmbh_serial_code_extend_expiration_days' ) {
			$display_key = esc_attr("Extend used restriction code for days: ");
		}
	}

    return $display_key;
}

add_filter('woocommerce_order_item_display_meta_value', 'sngmbh_woocommerce_order_item_display_meta_value', 20, 3);
function sngmbh_woocommerce_order_item_display_meta_value($meta_value, $meta, $item) {
	// zeigen in der Order den Wert an

    if( is_admin() && $item->get_type() === 'line_item') {
		if ($meta->key === '_sngmbh_product_serial_code' ) {
			$codes = explode(",", $meta_value);
			$codes_ = [];
			foreach($codes as $c) {
				$codes_[] = '<a target="_blank"  href="admin.php?page=sngmbh-serialcodes-validator&code='.urlencode($c).'">'.$c.'</a>';
			}
			$meta_value = implode(", ", $codes_);
		}
		if ($meta->key === '_sngmbh_serial_code_is_ticket' ) {
        	$meta_value = $meta_value == 1 ? "Yes" : "No";
		}
    }

	return $meta_value;
}

add_filter('manage_edit-product_columns', 'sngmbh_code_list_col');
function sngmbh_code_list_col($columns) {
    $new_columns = (is_array($columns)) ? $columns : array();
    $new_columns['SNGMBH_CODE_LIST_COLUMN'] = 'Code List';
    return $new_columns;
}

add_action('manage_product_posts_custom_column', 'sngmbh_code_list_col_data', 2);
function sngmbh_code_list_col_data($column) {
    global $post;

    if ($column == 'SNGMBH_CODE_LIST_COLUMN') {
		$code_list_ids = get_post_meta($post->ID, 'sngmbh_serial_code_list', true);

		global $sngmbhSerialcodesValidator;
		$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();

		$lists = $sngmbhSerialcodesValidator_AdminSettings->getLists();
		$dropdown_list = array('' => '-');
		foreach ($lists as $key => $list) {
			$dropdown_list[$list['id']] = $list['name'];
		}

		if (isset($code_list_ids) && !empty($code_list_ids)) {
            echo !empty( $dropdown_list[$code_list_ids]) ? esc_html($dropdown_list[$code_list_ids]) : '-';
        } else {
            echo "-";
        }
    }
}

add_filter("manage_edit-product_sortable_columns", 'sngmbh_code_list_col_sort');
function sngmbh_code_list_col_sort($columns) {
    $custom = array(
        'SNGMBH_CODE_LIST_COLUMN' => 'sngmbh_serial_code_list'
    );
    return wp_parse_args($custom, $columns);
}

add_action( 'wpo_wcpdf_after_item_meta', 'sngmbh_wpo_wcpdf_show_product_add_serial_code', 20, 3 );
function sngmbh_wpo_wcpdf_show_product_add_serial_code ( $template_type, $item, $order ) {
	$isPaid = SNGMBH::isOrderPaid($order);
	if ($isPaid) {
		$code = wc_get_order_item_meta($item['item_id'] , '_sngmbh_serial_code_list_sale_restriction',true);
		if (!empty($code)) {
			global $sngmbhSerialcodesValidator;
			$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();

			if (!$sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcRestrictDoNotPutOnPDF')) {
				$preText = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcRestrictPrefixTextCode');
				echo '<div class="product-serial-code">'.esc_html($preText).' '. esc_attr($code).'</div>';
			}
		}

		$code = wc_get_order_item_meta($item['item_id'] , '_sngmbh_product_serial_code',true);
		if (!empty($code)) {
			global $sngmbhSerialcodesValidator;
			$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();

			if (!$sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcassignmentDoNotPutOnPDF')) {
				$code_ = explode(",", $code);
				array_walk($code_, "trim");

				$isTicket = wc_get_order_item_meta($item['item_id'] , '_sngmbh_serial_code_is_ticket',true) == 1 ? true : false;
				$key = 'wcassignmentPrefixTextCode';
				if ($isTicket) $key = 'wcTicketPrefixTextCode';
				$preText = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue($key);

				$wcassignmentDoNotPutCVVOnPDF = $sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcassignmentDoNotPutCVVOnPDF');

				if ($isTicket) {
					foreach($code_ as $c) {
						if (!empty($c)) {
							$codeObj = $sngmbhSerialcodesValidator_AdminSettings->getCore()->retrieveCodeByCode($c);
							$metaObj = $sngmbhSerialcodesValidator_AdminSettings->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
							$url = $metaObj['wc_ticket']['_url'];
							echo '<p class="product-serial-code">'.esc_html($preText).' <b>'.$c.'</b>';
							if (!empty($codeObj['cvv']) && !$wcassignmentDoNotPutCVVOnPDF) {
								echo "<br>CVV: ".esc_html($codeObj['cvv']);
							}
							echo '<br><b>Ticket Detail: </b>'.esc_url($url).'</p>';
						}
					}
				} else {
					$sep = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcassignmentDisplayCodeSeperatorPDF');
					$ccodes = [];
					foreach($code_ as $c) {
						if (!$wcassignmentDoNotPutCVVOnPDF) {
							$codeObj = $sngmbhSerialcodesValidator_AdminSettings->getCore()->retrieveCodeByCode($c);
							if (!empty($codeObj['cvv'])) {
								$ccodes[] = esc_html($c." CVV: ".$codeObj['cvv']);
							} else {
								$ccodes[] = esc_html($c);
							}
						} else {
							$ccodes[] = esc_html($c);
						}
					}
					$code_text = implode($sep, $ccodes);
					echo '<div class="product-serial-code">'.esc_html($preText).' '. esc_html($code_text).'</div>';
				}
			}
		}
	} // not paid
}

// always executed for orders
add_action( 'woocommerce_order_item_meta_start', 'sngmbh_woocommerce_order_item_meta_start_always', 201, 4);
function sngmbh_woocommerce_order_item_meta_start_always ($item_id, $item, $order, $plain_text=false) {

	include_once SNGMBH_SERIALCODES_VALIDATOR_PLUGIN_DIR_PATH."sngmbhSerialcodesValidator_AdminSettings.php";

	global $sngmbhSerialcodesValidator;

	sngmbh_add_serialcode_to_order($order->get_id()); // falls noch welche fehlen, dann vor der E-Mail noch hinzufügen

	$isPaid = SNGMBH::isOrderPaid($order);
	if ($isPaid) {
		$sngmbhSerialcodesValidator_AdminSettings = $sngmbhSerialcodesValidator->getAdmin();

		$sale_restriction_code = wc_get_order_item_meta($item_id , '_sngmbh_serial_code_list_sale_restriction',true);
		if (!empty($sale_restriction_code)) {
			$preText = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcRestrictPrefixTextCode');
			if ($plain_text) {
				echo "\n".esc_html($preText).' '. esc_attr($sale_restriction_code);
			} else {
				echo '<div class="product-restriction-serial-code">'.esc_html($preText).' '. esc_attr($sale_restriction_code).'</div>';
			}
		}

		$wcassignmentDoNotPutCVVOnEmail = $sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcassignmentDoNotPutCVVOnEmail');
		$displaySerial = false;
		$code = "";
		$preText = "";
		$isTicket = wc_get_order_item_meta($item_id , '_sngmbh_serial_code_is_ticket',true) == 1 ? true : false;
		if ($isTicket) {
			$code = wc_get_order_item_meta($item_id , '_sngmbh_product_serial_code',true);
			if (!empty($code)) {
				$preText = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcTicketPrefixTextCode');
				$displaySerial = true;
			}
		} else {
			if ($isPaid) {
				$code = wc_get_order_item_meta($item_id , '_sngmbh_product_serial_code',true);
				if (!empty($code)) {
					if (!$sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcassignmentDoNotPutOnEmail')) {
						$preText = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcassignmentPrefixTextCode');
						$displaySerial = true;
					}
				}
			}
		}
		if ($displaySerial) {
			$code_ = explode(",", $code);
			array_walk($code_, "trim");
			if ($isTicket) {
				foreach($code_ as $c) {
					if (!empty($c)) {
						$codeObj = $sngmbhSerialcodesValidator_AdminSettings->getCore()->retrieveCodeByCode($c);
						$metaObj = $sngmbhSerialcodesValidator_AdminSettings->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
						$url = $metaObj['wc_ticket']['_url'];
						if ($plain_text) {
							echo "\n".esc_html($preText).' '.esc_attr($c);
							echo "\nTicket-Detail: ".esc_url($url);
						} else {
							echo '<div class="product-serial-code">'.esc_html($preText).' ';
							if (!empty($codeObj['cvv']) && !$wcassignmentDoNotPutCVVOnEmail) {
								echo "CVV: ".esc_html($codeObj['cvv'])." ";
							}
							if (!$sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcTicketDontDisplayDetailLinkOnMail')) {
								echo '<a target="_blank" href="'.esc_url($url).'">'.esc_html($c).'</a>';
							} else {
								echo $c;
							}
							if (!$sngmbhSerialcodesValidator_AdminSettings->isOptionCheckboxActive('wcTicketDontDisplayPDFButtonOnMail')) {
								$dlnbtnlabel = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcTicketLabelPDFDownload');
								echo ' <a target="_blank" href="'.esc_url($url).'?pdf">'.esc_html($dlnbtnlabel).'</a>';
							}
							echo '</div>';
						}
					}
				}
			} else {
				$sep = $sngmbhSerialcodesValidator_AdminSettings->getOptionValue('wcassignmentDisplayCodeSeperator');
				$ccodes = [];
				foreach($code_ as $c) {
					if (!$wcassignmentDoNotPutCVVOnEmail) {
						$codeObj = $sngmbhSerialcodesValidator_AdminSettings->getCore()->retrieveCodeByCode($c);
						if (!empty($codeObj['cvv'])) {
							$ccodes[] = esc_html($c." CVV: ".$codeObj['cvv']);
						} else {
							$ccodes[] = esc_html($c);
						}
					} else {
						$ccodes[] = esc_html($c);
					}
				}
				$code_text = implode($sep, $ccodes);
				if ($plain_text) {
					echo "\n".esc_html($preText).' '.esc_attr($code_text);
				} else {
					echo '<div class="product-serial-code">'.esc_html($preText).' '.esc_html($code_text).'</div>';
				}
			}
		}
	} // not paid
}

final class sngmbhSerialcodesValidator_WC {
	private $meta_key_codelist_restriction = 'sngmbh_serial_code_list_sale_restriction';
	private $meta_key_codelist_restriction_order_item = '_sngmbh_serial_code_list_sale_restriction';
	private $meta_key_codelist_ticket = 'sngmbh_serial_code_sale_ticket';
	private $_containsProductsWithRestrictions = null;
	protected $_prefix = 'sngmbhSerialcodesValidator';
	private $js_inputType = 'serialcoderestriction';
	private $MAIN = null;

	public static function Instance($MAIN) {
		static $inst = null;
        if ($inst === null) {
            $inst = new sngmbhSerialcodesValidator_WC($MAIN);
        }
        return $inst;
	}

	public function __construct($MAIN) {
		$this->MAIN = $MAIN;
		if ($this->getAdminSettings()->isOptionCheckboxActive('wcRestrictPurchase')) {
			add_action( 'woocommerce_before_cart_table', [$this, 'woocommerce_before_cart_table'], 20, 4 );
			add_action( 'woocommerce_check_cart_items', [$this, 'check_mandatory_coupon_for_specific_items'] );
			add_action( 'woocommerce_checkout_create_order_line_item', [$this, 'woocommerce_checkout_create_order_line_item'], 20, 4 );
			add_action( 'woocommerce_checkout_update_order_meta', [$this, 'woocommerce_checkout_update_order_meta'], 20, 2);

			// erlaube ajax nonpriv und registriere handler
			if (wp_doing_ajax()) {
				add_action('wp_ajax_nopriv_'.$this->_prefix.'_executeWCFrontend', [$this,'executeWCFrontend']); // nicht angemeldete user, sollen eine antwort erhalten
				add_action('wp_ajax_'.$this->_prefix.'_executeWCFrontend', [$this,'executeWCFrontend']); // nicht angemeldete user, sollen eine antwort erhalten
			}
		}
		if (is_admin() && $this->getAdminSettings()->isOptionCheckboxActive('wcRestrictFreeCodeByOrderRefund')) {
			add_action( 'woocommerce_delete_order_item', [$this, 'woocommerce_delete_order_item'], 20, 1);
			add_action( 'woocommerce_delete_order', [$this, 'woocommerce_delete_order'], 10, 1 );
			add_action( 'woocommerce_delete_order_refund', [$this, 'woocommerce_delete_order_refund'], 10, 1 );
		}
		add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
	}

	private function getAdminSettings() {
		return $this->MAIN->getAdmin();
	}
	private function getFrontend() {
		return $this->MAIN->getFrontend();
	}
	private function getCore() {
		return $this->MAIN->getCore();
	}

	public function executeJSON($a, $data=[], $just_ret=false) {
		$ret = "";
		$justJSON = false;
		try {
			switch (trim($a)) {
				case "downloadSerialInfosOfProduct":
					$ret = $this->downloadSerialInfosOfProduct($data);
					break;
				default:
					throw new Exception("#6000 ".sprintf(/* translators: %s: name of called function */esc_html__('function "%s" in wc backend not implemented', 'event-tickets-with-ticket-scanner'), $a));
			}
		} catch(Exception $e) {
			$this->MAIN->getAdmin()->logErrorToDB($e);
			if ($just_ret) throw $e;
			return wp_send_json_error ($e->getMessage());
		}
		if ($just_ret) return $ret;
		if ($justJSON) return wp_send_json($ret);
		else return wp_send_json_success( $ret );
	}

	private function containsProductsWithRestrictions() {
		if ($this->_containsProductsWithRestrictions == null) {
			$this->_containsProductsWithRestrictions = false;
	    	// loop through cart items and check if a restriction is set
		    foreach(WC()->cart->get_cart() as $cart_item ) {
		        // Check cart item for defined product Ids and applied coupon
		        $sngmbh_serial_code_list = get_post_meta($cart_item['product_id'], $this->meta_key_codelist_restriction, true);

		       	if (!empty($sngmbh_serial_code_list) || $sngmbh_serial_code_list == "0") {
					$this->_containsProductsWithRestrictions = true;
					break;
		       	}
		    }
		}
		return $this->_containsProductsWithRestrictions;
	}

	private function downloadSerialInfosOfProduct($data) {
		$product_id = intval($data['product_id']);
		$product = [];
		if ($product_id > 0){
			$daten = $this->getCore()->getCodesByProductId($product_id);
			$productObj = wc_get_product( $product_id );
			if ($productObj != null) {
				$product['name'] = $productObj->get_name();
			}
		}
		return ['serial_infos'=>$daten, 'product'=>$product];
	}

	public function add_meta_boxes() {
		global $post_type;
		global $post;

		if( $post_type == 'product' ) {
			add_meta_box(
				$this->_prefix."_basic_wc_product_webhook", // Unique ID
				'Serial Code Basic',  // Box title
				[$this, 'wc_product_display_side_box'],  // Content callback, must be of type callable
				$post_type,
				'side',
				'high'
			);
		} elseif ($post_type == "shop_order") {
		}
	}

    public function wc_product_display_side_box() {
        ?>
		<p>Display all Serial Infos</p>
		<button disabled data-id="<?php echo esc_attr($this->_prefix."btn_download_serial_infos"); ?>" class="button button-primary">Print Serial Infos</button>
        <?php
		do_action( $this->MAIN->_do_action_prefix.'wc_product_display_side_box', [] );
    }

	// add all filter and actions, if we are displaying the cart, checkout and have products with restrictions
	function woocommerce_before_cart_table() {
		if ($this->containsProductsWithRestrictions()) {
			// add filter und actions
			add_action( 'woocommerce_after_cart_item_name', [$this, 'woocommerce_after_cart_item_name'], 10, 2 );

			$this->addJSFileAndHandler();
		}
	}

	private function addJSFileAndHandler() {
		// erstmal ist diese fkt nur für sales restriction
		if (version_compare( WC_VERSION, SNGMBH_SERIALCODES_VALIDATOR_MIN_WC_VER, '<' )) return;

		wp_register_script(
			'SngmbhSerialcodesValidator_WC_frontend',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . 'wc_frontend.js?_v='.$this->MAIN->getPluginVersion(),
			array( 'jquery', 'jquery-blockui' ),
			(current_user_can("administrator") ? time() : $this->MAIN->getPluginVersion()),
			true );
		wp_localize_script(
 			'SngmbhSerialcodesValidator_WC_frontend',
			'phpObject', // name der js variable
 			[
 				'ajaxurl' => admin_url( 'admin-ajax.php' ),
 				'inputType' => $this->js_inputType,
 				'action' => $this->_prefix.'_executeWCFrontend'
 			] // werte in der js variable
 			);
      	wp_enqueue_script('SngmbhSerialcodesValidator_WC_frontend');
 	}

	public function executeWCFrontend() {
		// Do a nonce check
 		if( ! SNGMBH::issetRPara('security') || ! wp_verify_nonce(SNGMBH::getRequestPara('security'), 'woocommerce-cart') ) {
 			wp_send_json( ['nonce_fail' => 1] );
 			exit;
 		}
		if (!SNGMBH::issetRPara('a')) return wp_send_json_error("a not provided");

		$ret = "";
		$justJSON = false;
		$a = trim(SNGMBH::getRequestPara('a'));
		try {
			switch ($a) {
				case "updateSerialCodeToCartItem":
					$ret = $this->wc_frontend_updateSerialCodeToCartItem();
					break;
				default:
					throw new Exception("function '".$a."' not implemented");
			}
		} catch(Exception $e) {
			return wp_send_json_error (['msg'=>$e->getMessage()]);
		}
		if ($justJSON) return wp_send_json($ret);
		else return wp_send_json_success( $ret );

	}

	private function wc_frontend_updateSerialCodeToCartItem() {
		// Save the code to the cart meta
 		$cart = WC()->cart->cart_contents;
 		$cart_item_id = sanitize_key(SNGMBH::getRequestPara('cart_item_id'));
 		$code = sanitize_key(SNGMBH::getRequestPara('code'));
		$code = strtoupper($code);

 		$cart_item = $cart[$cart_item_id];
 		$cart_item[$this->meta_key_codelist_restriction_order_item] = $code;

 		WC()->cart->cart_contents[$cart_item_id] = $cart_item;
 		WC()->cart->set_session();

		$check_values = [];
		switch($this->check_code_for_cartitem($cart_item, $code)) {
			case 0:
				$check_values['isEmpty'] = true;
				break;
			case 1:
				$check_values['isValid'] = true;
				break;
			case 2:
				$check_values['isUsed'] = true;
				break;
			case 3: // not valid
			case 4: // no code list
			default:
				$check_values['notValid'] = true;
		}

 		wp_send_json( ['success' => 1, 'code'=>esc_attr(strtoupper($code)), 'check_values'=>$check_values] );
 		exit;
	}

	// zeige eingabe maske für das Produkt, wenn es eine purchase restriction mit codes hat
	function woocommerce_after_cart_item_name( $cart_item, $cart_item_key ) {
 		$sngmbh_serial_code_list = get_post_meta($cart_item['product_id'], $this->meta_key_codelist_restriction, true);
 		if (!empty($sngmbh_serial_code_list) || $sngmbh_serial_code_list == "0") {
	 		$code = isset( $cart_item[$this->meta_key_codelist_restriction_order_item] ) ? $cart_item[$this->meta_key_codelist_restriction_order_item] : '';
	 		$infoLabel = $this->getAdminSettings()->getOptionValue('wcRestrictCartInfo');
	 		$fieldPlaceholder = $this->getAdminSettings()->getOptionValue('wcRestrictCartFieldPlaceholder');
	 		$html = '<div><small>'.esc_attr($infoLabel).'<br></small>
	 					<input
	 						type="text"
	 						placeholder="%s"
	 						data-input-type="%s"
	 						data-cart-item-id="%s"
	 						value="%s"
	 						class="input-text text" />';
	 		printf(
	 			str_replace("\n", "", $html),
	 			esc_attr($fieldPlaceholder),
	 			esc_attr($this->js_inputType),
	 			esc_attr($cart_item_key),
	 			wc_clean($code)
	 		);
 		}
	}

	private function check_code_for_cartitem($cart_item, $code) {
		$ret = 0; // empty
		if (!empty($code)) {
	        // Check cart item for defined product Ids and applied coupon
			$sngmbh_serial_code_list_id = get_post_meta($cart_item['product_id'], $this->meta_key_codelist_restriction, true);
			if (!empty($sngmbh_serial_code_list_id) || $sngmbh_serial_code_list_id == "0") {
				try {
					$codeObj = $this->getCore()->retrieveCodeByCode($code);
					if ($codeObj['aktiv'] != 1) throw new Exception("not valid");
					if ($sngmbh_serial_code_list_id != "0" && $codeObj['list_id'] != $sngmbh_serial_code_list_id) throw new Exception("from wrong code list");
					if ($this->getFrontend()->isUsed($codeObj)) {
						return 2; // isUsed
					} else {
						return 1; // ok
					}
				} catch(Exception $e) {
					return 3; // notValid
				}
			} else {
				return 4; // code has no code list -> notValid
			}
		}
		return $ret;
	}

	function check_mandatory_coupon_for_specific_items() {

		if ($this->containsProductsWithRestrictions()) {

		    // loop through cart items and check if a restriction is set
		    foreach(WC()->cart->get_cart() as $cart_item ) {

				$code = isset( $cart_item[$this->meta_key_codelist_restriction_order_item] ) ? $cart_item[$this->meta_key_codelist_restriction_order_item] : '';
				$code = strtoupper($code);
				switch($this->check_code_for_cartitem($cart_item, $code)) {
					case 0:
						wc_add_notice( sprintf( 'The product "%s" requires a restriction code for checkout.', esc_html($cart_item['data']->get_name()) ), 'error' );
						break;
					case 1: // valid
						break;
					case 2:
						wc_add_notice( sprintf( 'The restriction code "%s" for product "%s" is already used.', esc_attr($code), esc_html($cart_item['data']->get_name()) ), 'error' );
						break;
					case 3: // not valid
					case 4: // no code list
					default:
						wc_add_notice( sprintf( 'The restriction code "%s" for product "%s" is not valid.',esc_attr($code), esc_html($cart_item['data']->get_name()) ), 'error' );
				}

		    } // end loop cart item
	 	} // end if containsProductsWithRestrictions
	}

	//The next step is to save the data to the order when it is processed to be paid
	function woocommerce_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
		if ( empty( $values[$this->meta_key_codelist_restriction_order_item] ) ) {
			return;
		}

		// speicher purchase restriction code zum order_item
		$code = $values[$this->meta_key_codelist_restriction_order_item];
		$item->add_meta_data( $this->meta_key_codelist_restriction_order_item, $code );

		$codeObj = null;
		try {
			$codeObj = $this->getCore()->retrieveCodeByCode($code);
		} catch(Exception $e) {
			if(isset($_GET['VollstartValidatorDebug'])) {
				var_dump($e);
			}
		}

		// set as used
		if ($this->getAdminSettings()->isOptionCheckboxActive('oneTimeUseOfRegisterCode')) {
			try {
				if ($codeObj == null) {
					$codeObj = $this->getCore()->retrieveCodeByCode($code);
				}
				$rc_v = $this->getAdminSettings()->getOptionValue('wcRestrictOneTimeUsage');
				if ($rc_v == 1) {
					$codeObj = $this->getFrontend()->markAsUsed($codeObj);
				} else if ($rc_v == 2) {
					$codeObj = $this->getFrontend()->markAsUsed($codeObj, true);
				}
			} catch(Exception $e){
				if(isset($_GET['VollstartValidatorDebug'])) {
					var_dump($e);
				}
			}
		}
		$this->getCore()->triggerWebhooks(16, $codeObj);
	}

	function woocommerce_checkout_update_order_meta($order_id, $address_data) {
		$order = wc_get_order( $order_id );
		foreach ( $order->get_items() as $item_id => $item ) {
			$code = wc_get_order_item_meta($item_id , $this->meta_key_codelist_restriction_order_item, true);
			// speicher orderid und order item id zum code
			if (!empty($code)) {
				$product_id = $item->get_product_id();
				$order_id = $order->get_id();
		        $list_id = get_post_meta($product_id, $this->meta_key_codelist_restriction, true);
				$this->getAdminSettings()->addRetrictionCodeToOrder($code, $list_id, $order_id, $product_id, $item_id);
			}
		}
	}

	function woocommerce_delete_order_item($item_get_id) {
		$code = wc_get_order_item_meta($item_get_id , $this->meta_key_codelist_restriction_order_item, true);
		if (!empty($code)) {
			$data = ['code'=>$code];
			// remove order info
			$this->getAdminSettings()->removeWoocommerceOrderInfoFromCode($data);
			// remove used info
			$this->getAdminSettings()->removeUsedInformationFromCode($data);
			// remove wc_rp info
			$this->getAdminSettings()->removeWoocommerceRstrPurchaseInfoFromCode($data);
			// add note to order
			$order_id = wc_get_order_id_by_order_item_id($item_get_id);
			$order = wc_get_order( $order_id );
			$order->add_order_note( "Order item deleted. Free code: ".esc_attr($code)." for next usage." );
		}
		$code_value = wc_get_order_item_meta($item_get_id , "_sngmbh_product_serial_code", true);
		if (!empty($code_value)) {
			$codes = explode(",", $code_value);
			foreach($codes as $code) {
				$code = trim($code);
				if (!empty($code)) {
					$data = ['code'=>$code];
					// remove used info
					$this->getAdminSettings()->removeUsedInformationFromCode($data);
					$this->getAdminSettings()->removeWoocommerceOrderInfoFromCode($data);
					$this->getAdminSettings()->removeWoocommerceRstrPurchaseInfoFromCode($data);
					// add note to order
					$order_id = wc_get_order_id_by_order_item_id($item_get_id);
					$order = wc_get_order( $order_id );
					$order->add_order_note( "Order item deleted. Free code: ".esc_attr($code)." for next usage." );
				}
			}
		}
	}

	function woocommerce_delete_order( $id ) {
		$order = wc_get_order( $id );
		foreach ( $order->get_items() as $item_id => $item ) {
			$this->woocommerce_delete_order_item($item_id);
		}
	}

	function woocommerce_delete_order_refund( $id ) {
		$order = wc_get_order( $id );
		foreach ( $order->get_items() as $item_id => $item ) {
			$this->woocommerce_delete_order_item($item_id);
		}
	}

}
