<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {
    class WC_Cash_App_Pay_Gateway extends WC_Payment_Gateway
    {
        // protected $SQ_Merchant_Id;
        // protected $SQ_Refresh_Token;
        // protected $SQ_Access_Token;
        public function __construct()
        {
            $this->id = 'cash-app-pay';
            // payment gateway plugin ID
            $this->icon = WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cashapp_35.png';
            // URL of the icon that will be displayed on checkout page near your gateway name
            $this->has_fields = true;
            // in case you need a custom form
            $this->method_title = 'Cash App Pay';
            $this->method_description = '<a href="https://cash.app/cash-app-pay" target="_blank">Cash App Pay</a> is the official integration for Square merchants. You need to connect an existing Square merchant account or create one to fully integrate this payment method.<br>
			<p><strong>More details about this gateway can be found at <a href="https://square.theafricanboss.com" target="_blank">square.theafricanboss.com</a></strong></p>
			<p><a href="https://square.theafricanboss.com/signup" target="_blank">Sign up to become a Square merchant using our referral link</a></p>
			<p>You will receive free processing on up to $1,000 in credit card transactions for the first 180 days* and/or whatever their current offer at signup is that will show once you click on the link</p>
			<p>See how the <a href="' . admin_url( 'admin.php?page=wc_cashapp_compared' ) . '">Cash App Pay payment method compares to Cash App Link payment method</a></p>';
            // will be displayed on the options page
            $this->init_settings();
            $this->enabled = $this->get_option( 'enabled' );
            
            if ( 'no' === $this->enabled && !empty($this->SQ_Access_Token) ) {
                $this->SQ_Access_Token = '';
                $this->update_option( 'SQ_Access_Token', $this->SQ_Access_Token );
            }
            
            $this->title = ( $this->get_option( 'checkout_title' ) ? $this->get_option( 'checkout_title' ) : $this->method_title );
            $this->description = ( $this->get_option( 'checkout_description' ) ? $this->get_option( 'checkout_description' ) : 'Click the button below and follow the instructions to pay with Cash App' );
            $this->SQ_Merchant_Id = $this->get_option( 'SQ_Merchant_Id' );
            $this->SQ_Refresh_Token = $this->get_option( 'SQ_Refresh_Token' );
            $this->SQ_Access_Token = $this->get_option( 'SQ_Access_Token' );
            $this->SQ_Locations = $this->get_option( 'SQ_Locations' );
            $this->SQ_Location_Id = $this->get_option( 'SQ_Location_Id' );
            $this->disableMenu = $this->get_option( 'disableMenu' ) ?? 'no';
            $this->enable_debug = $this->get_option( 'enable_debug' );
            $this->toggleTutorial = $this->get_option( 'toggleTutorial' );
            $this->status = $this->get_option( 'status' );
            // // $this->status = !empty($this->SQ_Access_Token ? ( empty($this->SQ_Location_Id) ? 'Not fully connected to Square ⚠️. Save the location ID' : 'Connected to Square ✅' ) : 'Connect to Square';
            
            if ( !empty($this->SQ_Access_Token) ) {
                $status = ( empty($this->SQ_Location_Id) ? 'Not fully connected to Square ⚠️. Save the location ID' : 'Connected to Square ✅' );
                $this->update_option( 'status', $status );
            } else {
                $this->update_option( 'status', 'Connect to Square' );
            }
            
            global  $cashapp_fs ;
            $upgrade_url = cashapp_fs()->get_upgrade_url();
            $pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '"><sup style="color:red">PRO</sup></a>';
            $edit_with_pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '">APPLY CHANGES WITH PRO</a>';
            $square_url = $this->wc_cash_app_pay_square_connect_url();
            $square = ( empty($this->SQ_Access_Token) ? ' <a href="' . $square_url . '">Connect to Square here</a>' : null );
            $new = ' <sup style="color:#0c0">NEW</sup>';
            $newFeature = " <sup style='color:#c00;'>NEW FEATURE</sup>";
            $improvedFeature = " <sup style='color:#0c0;'>IMPROVED FEATURE</sup>";
            $comingSoon = " <sup style='color:#00c;'>COMING SOON</sup>";
            $checkout_message = array(
                'title'       => 'Checkout Page Message',
                'type'        => 'textarea',
                'description' => 'This is the text a customer sees in the payment gateway box on the checkout page.',
                'default'     => 'Click the button below and follow the instructions to pay with Cash App',
                'placeholder' => 'Click the button below and follow the instructions to pay with Cash App',
            );
            $enable_debug = array(
                'title'       => 'Enable Debug',
                'label'       => 'Check to Enable / Uncheck to Disable',
                'type'        => 'checkbox',
                'description' => 'This will enable debug mode to help you troubleshoot issues. <a href="' . admin_url( 'admin.php?page=wc-status&tab=logs' ) . '" target="_blank">Access Logs here</a>',
                'default'     => 'no',
            );
            $location_id = array(
                'title'       => 'Square Location ID<br>',
                'type'        => 'text',
                'description' => 'No locations found. Please add a new business location in your <a href="https://squareup.com/dashboard/locations/new" target="_blank">Square Dashboard > Account & Settins Business > Locations</a>',
                'placeholder' => 'LXXXXXXXXXXXX',
            );
            // $location_id['default'] =  !empty($this->SQ_Location_Id) ? $this->SQ_Location_Id : undefined;
            if ( !empty($this->SQ_Location_Id) ) {
                $location_id['default'] = $this->SQ_Location_Id;
            }
            if ( !empty($this->SQ_Access_Token) ) {
                require WCCASHAPP_PLUGIN_DIR . 'includes/functions/square-locations.php';
            }
            
            if ( !empty($this->SQ_Access_Token) ) {
                $sq_status = array(
                    'title'             => 'Status',
                    'label'             => 'Status',
                    'type'              => 'button',
                    'description'       => '<p><a href="' . admin_url( 'admin.php?page=wc_cashapp_square' ) . '">Refresh Access</a> | <a href="' . admin_url( 'admin.php?page=wc_cashapp_square' ) . '">Revoke Access</a></p>',
                    'default'           => ( empty($this->SQ_Location_Id) ? 'Not fully connected to Square ⚠️. Save the location ID' : 'Connected to Square ✅' ),
                    'custom_attributes' => array(
                    'disabled' => 'disabled',
                    'style'    => 'background-color: #0c0; color: #fff; border: 1px solid #0c0; cursor: not-allowed;',
                ),
                );
            } else {
                $sq_status = array(
                    'title'             => 'Status',
                    'label'             => 'Status',
                    'type'              => 'button',
                    'description'       => 'Disconnected from Square ❌. ' . $square,
                    'default'           => 'Connect to Square',
                    'custom_attributes' => array(
                    'style'   => 'background-color: #ff0000; color: #fff;',
                    'onclick' => 'window.location.href="' . $square_url . '"',
                ),
                );
            }
            
            $pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '"><sup style="color:red">PRO</sup></a>';
            $edit_with_pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '">APPLY CHANGES WITH PRO</a>';
            $checkout_message = array(
                'title'       => 'Checkout Page Message' . $pro,
                'type'        => 'textarea',
                'description' => 'This is the text a customer sees in the payment gateway box on the checkout page.' . $edit_with_pro,
                'default'     => 'Click the button below and follow the instructions to pay with Cash App',
                'placeholder' => 'Click the button below and follow the instructions to pay with Cash App',
                'css'         => 'width:80%; pointer-events: none;',
                'class'       => 'disabled',
            );
            $enable_debug = array(
                'title'       => 'Enable Debug' . $pro,
                'label'       => 'Check to Enable / Uncheck to Disable' . $edit_with_pro,
                'type'        => 'checkbox',
                'description' => 'This will enable debug mode to help you troubleshoot issues. <a href="' . admin_url( 'admin.php?page=wc-status&tab=logs' ) . '" target="_blank">Access Logs here</a>',
                'default'     => 'no',
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            );
            $this->form_fields = array(
                'enabled'              => array(
                'title'   => 'Enable Cash App Pay',
                'label'   => 'Check to Enable / Uncheck to Disable',
                'type'    => 'checkbox',
                'default' => 'no',
            ),
                'status'               => $sq_status,
                'checkout_title'       => array(
                'title'       => 'Checkout Title',
                'type'        => 'text',
                'description' => 'This is the title which the user sees on the checkout page.',
                'default'     => 'Cash App Pay',
                'placeholder' => 'Cash App Pay',
            ),
                'checkout_description' => $checkout_message,
                'SQ_Location_Id'       => $location_id,
                'enable_debug'         => $enable_debug,
                'toggleTutorial'       => array(
                'title'       => 'Enable Tutorial on checkout',
                'label'       => 'Check to Enable / Uncheck to Disable',
                'type'        => 'checkbox',
                'description' => 'Help your customers checkout with ease',
                'default'     => 'no',
            ),
            );
            // Gateways can support subscriptions, refunds, saved payment methods
            // $this->supports = array(
            // 	'products',
            // 	'pre-orders'
            // 	'default_credit_card_form',
            // 	'refunds',
            // 	'subscriptions',
            // 	'subscription_cancellation',
            // 	'subscription_reactivation',
            // 	'subscription_suspension',
            // 	'subscription_amount_changes',
            // 	'subscription_payment_method_change',
            // 	'subscription_date_changes',
            // );
            // $this->supports = array('products');
            $this->supports = array( 'products', 'refunds' );
            // This action hook saves the settings
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'wc_cash_app_pay_payment_scripts' ) );
            // Thank you page
            add_action( "woocommerce_thankyou_{$this->id}", array( $this, 'wc_cash_app_pay_thankyou_page' ) );
            add_action(
                'woocommerce_checkout_order_processed',
                array( $this, 'wc_cash_app_pay_processed' ),
                10,
                3
            );
            add_action( "admin_post_wc_cash_app_pay_connect", array( $this, 'wc_cash_app_pay_square_connect_redirect' ) );
            add_action(
                'woocommerce_email_order_details',
                array( $this, 'wc_cash_app_pay_email_instructions' ),
                10,
                3
            );
        }
        
        public function wc_cash_app_pay_square_url( $string = false, $extension = false )
        {
            $square_url = '';
            if ( !$string ) {
                return $square_url;
            }
            if ( !is_admin() ) {
                return $square_url;
            }
            require WCCASHAPP_PLUGIN_DIR . 'includes/functions/square-url.php';
            return $square_url;
        }
        
        public function wc_cash_app_pay_square_connect_url()
        {
            $square_connect_url = '';
            if ( !is_admin() ) {
                return $square_connect_url;
            }
            require WCCASHAPP_PLUGIN_DIR . 'includes/functions/square-connect.php';
            return $square_connect_url;
        }
        
        public function wc_cash_app_pay_square_connect_redirect()
        {
            require WCCASHAPP_PLUGIN_DIR . 'includes/admin/square-redirect.php';
        }
        
        // wc_add_notice & log
        // protected function wccp_woo_notice( $message, $status = 'error', $level = 'info' ) {}
        /**
         * Logging method.
         *
         * @param string $message Log message.
         * @param string $level Optional. Default 'info'
         * Possible values: emergency|alert|critical|error|warning|notice|info|debug.
         */
        protected function wccp_log( $message, $level = 'info' )
        {
            // logs at admin.php?page=wc-status&tab=logs
            
            if ( !empty($message) && $this->enable_debug == 'yes' && cashapp_fs()->is_plan__premium_only( 'pro' ) ) {
                $logger = wc_get_logger();
                // $logger->debug( 'Detailed debug information', $context );
                // $logger->info( 'Interesting events', $context );
                // $logger->notice( 'Normal but significant events', $context );
                // $logger->warning( 'Exceptional occurrences that are not errors', $context );
                // $logger->error( 'Runtime errors that do not require immediate', $context );
                // $logger->critical( 'Critical conditions', $context );
                // $logger->alert( 'Action must be taken immediately', $context );
                // $logger->emergency( 'System is unusable', $context );
                // // The `log` method accepts any valid level as its first argument.
                // // $context may hold arbitrary data.
                // // If you provide a "source", it will be used to group your logs.
                // $context = array( 'source' => 'my-extension-name' );
                // $logger->log( 'debug', '<- Provide a level', $context );
                $logger->log( $level, wp_strip_all_tags( wp_kses_post( $message ) ), array(
                    'source' => $this->id,
                ) );
            }
        
        }
        
        protected function wc_cash_app_locations_api()
        {
            // https://developer.squareup.com/reference/square/locations-api/list-locations
            $locations = array();
            if ( empty($this->SQ_Access_Token) ) {
                return array(
                    'status'    => false,
                    'message'   => 'Please connect to your Square account first.',
                    'locations' => $locations,
                );
            }
            $locations = ( !empty($this->SQ_Locations) ? json_decode( $this->SQ_Locations, true ) : array(
                'status' => null,
            ) );
            if ( !empty($locations['status']) && !empty($locations['time']) && $locations['time'] > time() - 3 * 24 * 60 * 60 && !empty($locations['options']) ) {
                return $locations;
            }
            $args = array(
                'headers' => array(
                'Square-Version' => '2023-07-20',
                'Authorization'  => "Bearer {$this->SQ_Access_Token}",
                'Content-Type'   => 'application/json',
            ),
            );
            $response = wp_remote_get( 'https://connect.squareup.com/v2/locations', $args );
            
            if ( !is_wp_error( $response ) ) {
                // convert json to array
                $locations_result_array = json_decode( wp_remote_retrieve_body( $response ), true );
                $locations = $locations_result_array['locations'];
                $errors = $locations_result_array['errors'];
                // var_dump($errors);
                
                if ( count( (array) $errors ) > 0 ) {
                    $error_message = '';
                    foreach ( $errors as $error ) {
                        $error_message .= $error['detail'] . ' ';
                    }
                    return array(
                        'status'    => false,
                        'message'   => "Error found while trying to retrieve locations: {$error_message}",
                        'locations' => $locations,
                    );
                } else {
                    
                    if ( count( (array) $locations ) > 0 ) {
                        $options = array();
                        $active_location_ids = array();
                        foreach ( $locations as $location ) {
                            
                            if ( $location['status'] == 'ACTIVE' ) {
                                array_push( $active_location_ids, $location['id'] );
                                $options[$location['id']] = $location['name'] . ' located at ' . $location['address']['address_line_1'] . ", " . $location['address']['locality'] . ", " . $location['address']['administrative_district_level_1'] . ", " . $location['address']['postal_code'] . ", " . $location['address']['country'];
                            }
                        
                        }
                        // if no locations are found, create error message
                        
                        if ( is_array( $options ) && count( $options ) <= 0 ) {
                            $this->wccp_log( 'Locations: No active locations found out of ' . count( $locations ) . ' available locations', 'error' );
                            return array(
                                'status'    => false,
                                'message'   => 'No active locations found. Please add a new business location in your <a href="https://squareup.com/dashboard/locations/new" target="_blank">Square Dashboard > Account & Settins Business > Locations</a>',
                                'locations' => $locations,
                                'options'   => $options,
                                'time'      => time(),
                            );
                        }
                        
                        
                        if ( empty($this->SQ_Location_Id) || !in_array( $this->SQ_Location_Id, $active_location_ids ) ) {
                            $this->SQ_Location_Id = $active_location_ids[0];
                            $this->update_option( 'SQ_Location_Id', $this->SQ_Location_Id );
                        }
                        
                        // echo '<pre>'.print_r($locations).'</pre><pre>'.print_r($options).'</pre>';
                        $result = array(
                            'status'    => true,
                            'message'   => 'Locations found',
                            'locations' => $locations,
                            'options'   => $options,
                            'time'      => time(),
                        );
                        // return locations
                        $this->update_option( 'SQ_Locations', json_encode( $result ) );
                        return $result;
                    } else {
                        return array(
                            'status'    => false,
                            'message'   => 'No locations found. Please add a new business location in your <a href="https://squareup.com/dashboard/locations/new" target="_blank">Square Dashboard > Account & Settins Business > Locations</a>',
                            'locations' => $locations,
                        );
                    }
                
                }
            
            } else {
                $error_message = ( is_wp_error( $response ) ? $response->get_error_message() : null );
                $this->wccp_log( "Locations: Error getting locations: {$error_message}", 'error' );
                return array(
                    'status'    => false,
                    'message'   => "Error getting locations: {$error_message}",
                    'locations' => $locations,
                );
            }
        
        }
        
        // /**
        //  * Check if this gateway is available in the user's country based on currency.
        //  *
        //  * @return bool
        //  */
        // public function is_valid_for_use() {
        // $currency = get_woocommerce_currency();
        // 	return in_array(
        // 		get_woocommerce_currency(),
        // 		apply_filters(
        // 			'woocommerce_cash_app_pay_supported_currencies',
        // 			array( 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB', 'INR' )
        // 		),
        // 		true
        // 	);
        // }
        // /**
        //  * Admin Panel Options.
        //  * - Options for bits like 'title' and availability on a country-by-country basis.
        //  */
        // public function admin_options() {
        // 	if ( $this->is_valid_for_use() ) {
        // 		parent::admin_options();
        // 	} else {
        // echo '<div class="inline error"><p><strong>' . esc_html_e( 'Gateway disabled', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '</strong>:' .
        // 		esc_html_e( 'Cash App Pay does not support your store currency.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) .
        // 		'</p></div>';
        // }
        // }
        // Payment Custom JS and CSS
        public function wc_cash_app_pay_payment_scripts()
        {
            if ( 'no' === $this->enabled || empty($this->SQ_Access_Token) ) {
                return;
            }
            
            if ( is_checkout() ) {
                $square_web = "https://web.squarecdn.com/v1/square.js";
                wp_enqueue_script( 'wc_cash_app_pay_square_web', $square_web );
                // $square_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/square.js' ));
                wp_enqueue_script(
                    'wc_cash_app_pay_square',
                    WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/square.js',
                    array( 'jquery', 'wc_cash_app_pay_square_web' ),
                    null,
                    true
                );
                global  $woocommerce ;
                $cart = $woocommerce->cart;
                $cart_items = $woocommerce->cart->get_cart();
                $amount = $woocommerce->cart->total;
                // https://developer.squareup.com/reference/sdks/web/payments/objects/PaymentRequestOptions
                // [
                // 	{
                // 	  "amount": "22.15",
                // 	  "label": "Item to be purchased",
                // 	  "id": "SKU-12345
                // 	  "imageUrl": "https://url-cdn.com/123ABC"
                // 	  "pending": true
                // 	  "productUrl": "https://my-company.com/product-123ABC"
                // 	}
                // ],
                $lineItems = array();
                foreach ( WC()->cart->get_cart() as $cart_item ) {
                    $lineItems[] = array(
                        'amount'     => $cart_item['line_total'],
                        'label'      => $cart_item['data']->get_name(),
                        'id'         => $cart_item['product_id'],
                        'imageUrl'   => wp_get_attachment_url( $cart_item['data']->get_image_id() ),
                        'productUrl' => get_permalink( $cart_item['product_id'] ),
                    );
                }
                // // get $buyer info
                // $buyer = array(
                // 	"givenName" => "John",
                // 	"familyName" => "Doe",
                // 	"addressLines" => array(
                // 	   "123 East Main Street",
                // 	   "#111"
                // 	),
                // 	"city" => "Seattle",
                // 	"state" => "WA",
                // 	"postalCode" => "98111",
                // 	"countryCode" => "USA",
                // 	"email" => "johndoe@example.com",
                // 	"phone" => "+12065551212"
                // );
                global  $wp ;
                wp_localize_script( 'wc_cash_app_pay_square', 'wc_cash_app_pay_object', array(
                    'isPro'         => ( cashapp_fs()->is_plan__premium_only( 'pro' ) ? true : false ),
                    'checkout_url'  => get_permalink( get_the_ID() ),
                    'checkout_url2' => home_url( $wp->request ),
                    'amount'        => $amount,
                    'cart'          => $cart,
                    'cart_items'    => $cart_items,
                    'lineItems'     => $lineItems,
                ) );
                $spinner_css = 'spinner.css';
                
                if ( !wp_script_is( $spinner_css, 'enqueued' ) ) {
                    wp_register_style( $spinner_css, WCCASHAPP_PLUGIN_DIR_URL . 'assets/css/' . $spinner_css );
                    wp_enqueue_style( $spinner_css );
                    // $spinner_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/spinner.css' ));
                    // wp_register_style( 'wc_cash_app_pay_spinner', WCCASHAPP_PLUGIN_DIR_URL . 'assets/css/spinner.css' );
                    // wp_enqueue_style ( 'wc_cash_app_pay_spinner' );
                }
            
            }
        
        }
        
        // Checkout page
        public function payment_fields()
        {
            global  $woocommerce ;
            $total = $woocommerce->cart->get_total();
            // $1.00
            $amount = $woocommerce->cart->total;
            // 1.00
            $sq_location = $this->SQ_Location_Id;
            echo  '<fieldset id="wc-' . esc_attr( $this->id ) . '-form" data-plugin="' . wp_kses_post( WCCASHAPP_PLUGIN_VERSION ) . '">' ;
            do_action( 'woocommerce_form_start', $this->id );
            
            if ( empty($this->SQ_Access_Token) ) {
                echo  '<p>' . wp_kses_post( __( 'Please finish setting up this payment method or contact the admin to do so.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ) . '</p>' ;
                do_action( 'woocommerce_form_end', $this->id );
                echo  '<input name="do_not_checkout" type="hidden" value="true"><div class="clear"></div></fieldset>' ;
                return;
            }
            
            
            if ( !empty($this->checkout_description) ) {
                echo  '<p>' . wp_kses_post( wpautop( wptexturize( __( $this->checkout_description, WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ) ) ) . '.</p>' ;
            } else {
                echo  '<p id="wc-' . esc_attr( $this->id ) . '-top">' . wp_kses_post( __( 'Click the button below and follow the instructions to pay with Cash App', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ) . '</p>' ;
            }
            
            // // as seen on https://squareup.com/shop/hardware/checkout
            echo  '<div class="d-flex align-items-center">
					<input type="hidden" id="amount" name="amount" value="' . $amount . '">
					<input type="hidden" id="payment_token" name="payment_token" value="">
					<input type="hidden" id="sq_environment" name="sq_environment" value="production">
					<input type="hidden" id="sq_location" name="sq_location" value="' . $sq_location . '">

					<div id="cash-app-payment-form">
						<div id="cash-app-spinner">
						<span class="spinner-grow text-dark" role="status"></span>
						</div>
						<span id="reattach-cashapppay" onclick="reattachCashAppPay()"></span>
						<div class="d-flex justify-content-end" id="cash-app-pay"></div>
					</div>
					<div class="d-flex justify-content-center" id="payment-status-container"></div>
				</div>' ;
            // toggleTutorial
            
            if ( 'yes' === $this->toggleTutorial ) {
                echo  '<h4>Instructions</h4><p>A Cash App Pay button should appear above and once you click it, you can follow the steps by scanning with your camera or inside your Cash App mobile app as seen in the GIF below:</p>' ;
                echo  '<p><img class="tutorial" src="' . WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cash-app-pay-scan.gif" alt="A screenshot showing the dialog box of the linked Square merchant account."></p>' ;
            }
            
            do_action( 'woocommerce_form_end', $this->id );
            echo  '<div class="clear"></div></fieldset>' ;
        }
        
        // validate payment token
        public function validate_fields()
        {
            $sq_payment_token = sanitize_text_field( trim( $_POST['payment_token'] ) );
            
            if ( !$sq_payment_token || strlen( $sq_payment_token ) < 5 ) {
                wc_add_notice( esc_html( __( 'Invalid Cash App Pay Token. Please click again on the Cash App Pay button or refresh the page', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ), 'error' );
                $this->wccp_log( "Checkout: Invalid Cash App Pay Token {$sq_payment_token}", 'error' );
            }
            
            
            if ( isset( $_POST['do_not_checkout'] ) ) {
                wc_add_notice( esc_html( __( 'Please try another payment method', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ), 'error' );
                $this->wccp_log( "Checkout: A customer tried {$this->method_title} while it is not yet fully set up by the admin and was advised to try another payment method", 'error' );
            }
        
        }
        
        // Thank you page
        public function wc_cash_app_pay_thankyou_page( $order_id )
        {
            if ( !$order_id ) {
                return;
            }
            $order = wc_get_order( $order_id );
            if ( !$order instanceof WC_Order ) {
                return;
            }
            
            if ( $this->id === $order->get_payment_method() && cashapp_fs()->is_plan__premium_only( 'pro' ) ) {
                $sqp_receipt = $order->get_meta( 'sqp_receipt' );
                
                if ( !empty($sqp_receipt) ) {
                    echo  '<div id="wc-' . esc_attr( $this->id ) . '-form" data-plugin="' . wp_kses_post( WCCASHAPP_PLUGIN_VERSION ) . '">' ;
                    echo  '<h2>' . sprintf( __( '%s Receipt', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $this->method_title ) . '</h2>' ;
                    echo  wp_kses_post( "<p class='sqp_receipt'>" . sprintf( __( 'Here is your <a href="%s" target="blank">Square receipt</a>', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $sqp_receipt ) . "</p>" ) ;
                    echo  '</div>' ;
                }
            
            }
        
        }
        
        public function wc_cash_app_pay_processed( $order_id, $posted_data, $order )
        {
            if ( !$order_id || !$order ) {
                return;
            }
            if ( $this->id === $order->get_payment_method() ) {
                require_once WCCASHAPP_PLUGIN_DIR . 'includes/functions/order_processed.php';
            }
        }
        
        public function wc_cash_app_pay_email_instructions( $order, $sent_to_admin, $plain_text = false )
        {
            if ( !$order instanceof WC_Order ) {
                return;
            }
            
            if ( !$sent_to_admin && $this->id === $order->get_payment_method() && cashapp_fs()->is_plan__premium_only( 'pro' ) ) {
                $sqp_receipt = $order->get_meta( 'sqp_receipt' );
                
                if ( !empty($sqp_receipt) ) {
                    echo  '<h2>' . sprintf( __( '%s Receipt', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $this->method_title ) . '</h2>' ;
                    echo  wp_kses_post( "<p class='sqp_receipt'>" . sprintf( __( 'Here is your <a href="%s" target="blank">Square receipt</a>', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $sqp_receipt ) . "</p>" ) ;
                }
            
            }
        
        }
        
        // add_action( 'woocommerce_cart_calculate_fees', 'wc_cash_app_pay_checkout_fees' );
        // function wc_cash_app_pay_checkout_fees( $cart ) {
        // 	$discount = $cart->subtotal * 0.1;
        // 	$cart->add_fee( __( 'Gateway Discount', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) , -$discount );
        // 	$fee = $cart->subtotal * 3;
        // 	$cart->add_fee( __( 'Processing Fees', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) , $fee );
        // }
        // // Tested and works for WooCommerce versions 2.6.x, 3.0.x and 3.1.x
        // add_action( 'woocommerce_calculate_totals', 'wc_cash_app_pay_cart_calculate_totals', 10, 1 );
        // function wc_cash_app_pay_cart_calculate_totals( $cart_object ) {
        // 	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
        // 	if ( !WC()->cart->is_empty() ):
        // 		## Displayed subtotal (+10%)
        // 		// $cart_object->subtotal *= 1.1;
        // 		## Displayed TOTAL (+10%)
        // 		// $cart_object->total *= 1.1;
        // 		## Displayed TOTAL CART CONTENT (+10%)
        // 		$cart_object->cart_contents_total *= 1.1;
        // 	endif;
        // }
        protected function wc_cash_app_pay_square_customer( $order )
        {
            $customer = null;
            $customer_id = null;
            // woocommerce customer information https://stackoverflow.com/a/57562904
            // $user = $order->get_user();
            // $nickname = !empty($user) ? $user->display_name : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            // $first_name = $order->get_billing_first_name();
            // $last_name  = $order->get_billing_last_name();
            // $email  = $order->get_billing_email();
            // $phone  = $order->get_billing_phone();
            // $company    = $order->get_billing_company();
            // $address_1  = $order->get_billing_address_1();
            // $address_2  = $order->get_billing_address_2();
            // $city       = $order->get_billing_city();
            // $state      = $order->get_billing_state();
            // $postcode   = $order->get_billing_postcode();
            // $country    = $order->get_billing_country();
            try {
                // https://developer.squareup.com/explorer/square_2022-05-12/customers-api/search-customers?params=N4IgRg9gJgngCgQwE4ILYFMAu6kGcQBcoAjgK44yGgBmAlgDbZJUjqoIMD6CUUS6ufEVYAPBAGNMhEAlmyQAXwA0IAA4ALCADt0nLaVRgcVBaeUgAbjly1t0gIwA6AAyKgA&env=production&v=1
                $body = array(
                    "query" => array(
                    "filter" => array(
                    "email_address" => array(
                    "exact" => $order->get_billing_email(),
                ),
                ),
                ),
                );
                $args = array(
                    'headers' => array(
                    'Content-Type'   => 'application/json',
                    'Authorization'  => "Bearer {$this->SQ_Access_Token}",
                    'Square-Version' => '2022-05-12',
                ),
                    'body'    => json_encode( $body ),
                );
                $response = wp_remote_post( 'https://connect.squareup.com/v2/customers/search', $args );
                
                if ( !is_wp_error( $response ) ) {
                    $response_code = wp_remote_retrieve_response_code( $response );
                    $response_body = wp_remote_retrieve_body( $response );
                    // echo '<pre>';
                    // print_r($response_body);
                    // echo '</pre>';
                    $response_body = json_decode( $response_body );
                    if ( $response_code == 200 ) {
                        
                        if ( !empty($response_body->customers) ) {
                            $customer = $response_body->customers[0];
                            $customer_id = $customer->id;
                            return $customer_id;
                        }
                    
                    }
                }
                
                // https://developer.squareup.com/explorer/square_2022-05-12/customers-api/create-customer?params=N4IgRg9gJgngCgQwE4ILYFMAu6kGcQBco6qCAlgDYD6CUUS6u%2BBIAAgOamUB0AxhKhAAaEP1QAHBADsYVKWnSEQAFXwiAZmkqz5GJQCFFI9mQBu6KXIVKAUsJBSyvANa7FLG%2BgAE%2B%2B1IjYSgDMAKxeAMqY9uIAFhBS6HIArqhgOEoAnAAcAOwAbCEALPa09IzMoKUMTFQUZAlUAIzBIUFBJXTVuLX1iQBMSggdqPVkuJgomGaJUGMTTpi16ObUzSwIG-b8SVITMEoAggCq9hQQvAh1mPvr0RDjl1T8UO4gAwC%2B7yLmeGTxSo1uAAGEDvIA&env=production&v=1
                $body = array(
                    'email_address' => $order->get_billing_email(),
                    'given_name'    => $order->get_billing_first_name(),
                    'family_name'   => $order->get_billing_last_name(),
                    'phone_number'  => $order->get_billing_phone(),
                    'company_name'  => $order->get_billing_company(),
                    'address'       => array(
                    'address_line_1'                  => $order->get_billing_address_1(),
                    'address_line_2'                  => $order->get_billing_address_2(),
                    'administrative_district_level_1' => $order->get_billing_state(),
                    'country'                         => $order->get_billing_country(),
                    'locality'                        => $order->get_billing_city(),
                    'postal_code'                     => $order->get_billing_postcode(),
                ),
                );
                $args = array(
                    'headers' => array(
                    'Content-Type'   => 'application/json',
                    'Authorization'  => "Bearer {$this->SQ_Access_Token}",
                    'Square-Version' => '2022-05-12',
                ),
                    'body'    => json_encode( $body ),
                );
                $response = wp_remote_post( 'https://connect.squareup.com/v2/customers', $args );
                
                if ( !is_wp_error( $response ) ) {
                    $response_code = wp_remote_retrieve_response_code( $response );
                    $response_body = wp_remote_retrieve_body( $response );
                    // echo '<pre>';
                    // print_r($response_body);
                    // echo '</pre>';
                    $response_body = json_decode( $response_body );
                    if ( $response_code == 200 ) {
                        
                        if ( !empty($response_body->customer) ) {
                            $customer = $response_body->customer;
                            $customer_id = $customer->id;
                            return $customer_id;
                        }
                    
                    }
                }
            
            } catch ( \Throwable $th ) {
                // print_r($th);
                // echo 'Error: ' . $th->getMessage();
            }
            return null;
        }
        
        protected function wc_cash_app_set_money( $method, $amount, $currency = 'USD' )
        {
            // "app_fee_money": {
            //   "amount": 1,
            //   "currency": "USD"
            // },
            // $amount_money = (object) new stdClass();
            $amount_money = new stdClass();
            $amount_money->{$method} = (object) [
                "amount"   => $amount,
                "currency" => $currency,
            ];
            // $amount_money->setAmount = $amount;
            // $amount_money->setCurrency = $currency;
            return $amount_money;
        }
        
        protected function unsetEmptyKeysRecursive( &$array )
        {
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $this->unsetEmptyKeysRecursive( $value );
                }
                if ( empty($value) ) {
                    unset( $array[$key] );
                }
            }
        }
        
        protected function wc_cash_app_payment_api( $body, $order )
        {
            // https://developer.squareup.com/reference/square_2022-05-12/payments-api/create-payment
            // $lineItems = array();
            // foreach ( WC()->cart->get_cart() as $cart_item ) {
            // 	$lineItems[] = array(
            // 		'amount' => $cart_item['line_total'],
            // 		'label' => $cart_item['data']->get_name(),
            // 		'id' => $cart_item['product_id'],
            // 		'imageUrl' => wp_get_attachment_url( $cart_item['data']->get_image_id() ),
            // 		// 'pending' => true, // pending boolean | Indicates whether the value in the amount field represents an estimated or unknown cost.
            // 		'productUrl' => get_permalink( $cart_item['product_id'] ),
            // 	);
            // }
            $this->unsetEmptyKeysRecursive( $body );
            // wc_add_notice( json_encode($body), 'notice' );
            $args = array(
                'headers' => array(
                'Content-Type'   => 'application/json',
                'Authorization'  => "Bearer {$this->SQ_Access_Token}",
                'Square-Version' => '2021-06-16',
            ),
                'body'    => json_encode( $body ),
            );
            $response = wp_remote_post( 'https://connect.squareup.com/v2/payments', $args );
            return $response;
        }
        
        // Process Order
        public function process_payment( $order_id )
        {
            try {
                
                if ( !$order_id ) {
                    wc_add_notice( '<p>Something went terribly wrong.</p><p>Order information is missing</p>', 'error' );
                    return;
                }
                
                $order = wc_get_order( $order_id );
                
                if ( !$order instanceof WC_Order ) {
                    wc_add_notice( '<p>Something went terribly wrong.</p><p>Order information is missing</p>', 'error' );
                    $this->wccp_log( "Checkout: Order information is missing for order id {$order_id}", 'error' );
                    return;
                }
                
                
                if ( !is_wp_error( $order ) && $this->id === $order->get_payment_method() ) {
                    $amount = $order->get_total();
                    $currency = $order->get_currency();
                    try {
                        $paymentRequest = array();
                        $amount_money = array(
                            "amount"   => floatval( $amount ) * 100,
                            "currency" => $currency,
                        );
                        $paymentRequest["amount_money"] = $amount_money;
                        $sq_payment_token = sanitize_text_field( trim( $_POST['payment_token'] ) );
                        $paymentRequest["source_id"] = $sq_payment_token;
                        $sq_idempotency = substr( uniqid( "wc-c-{$order_id}" ), 0, 44 );
                        $paymentRequest["idempotency_key"] = $sq_idempotency;
                        $app_fee_money = array(
                            "amount"   => intval( $amount + 1 ),
                            "currency" => $currency,
                        );
                        $paymentRequest["app_fee_money"] = $app_fee_money;
                        $paymentRequest["autocomplete"] = true;
                        $square_customer = $this->wc_cash_app_pay_square_customer( $order );
                        
                        if ( !empty($square_customer) ) {
                            $order->update_meta_data( 'sq_customer_id', $square_customer );
                            $paymentRequest["customer_id"] = $square_customer;
                        }
                        
                        $paymentRequest["reference_id"] = substr( "{$order_id}", 0, 39 );
                        $paymentRequest["buyer_email_address"] = $order->get_billing_email();
                        $paymentRequest["location_id"] = $this->SQ_Location_Id;
                        $full_domain = ( !empty(parse_url( get_bloginfo( 'url' ) )) ? parse_url( get_bloginfo( 'url' ) )['host'] : null );
                        $domain = ( !empty($full_domain) && strlen( $full_domain ) > 15 ? substr( $full_domain, 0, 15 ) : $full_domain );
                        $name = ( !empty(get_bloginfo( 'name' )) && strlen( get_bloginfo( 'name' ) ) > 15 ? substr( get_bloginfo( 'name' ), 0, 15 ) : get_bloginfo( 'name' ) );
                        // Partner name + unique transaction ID and/or invoice ID has to be provided in the note parameter
                        // $paymentRequest["note"] = 'Payment for order #' . $order_id . ' via ' . $this->method_title . '. Powered by The African Boss LLC';
                        // $paymentRequest["note"] = substr("Woocommerce Payment for order #{$order_id} at $full_domain. Powered by The African Boss LLC", 0, 499);
                        $paymentRequest["note"] = substr( "Payment for Woocommerce order #{$order_id} at {$full_domain}", 0, 499 );
                        $paymentRequest["statement_description_identifier"] = substr( ( 'SQ ' . !empty($domain) ? $domain : $name ), 0, 19 );
                        $shipping_address = array(
                            "first_name"     => $order->get_shipping_first_name(),
                            "last_name"      => $order->get_shipping_last_name(),
                            "address_line_1" => $order->get_shipping_address_1(),
                            "address_line_2" => $order->get_shipping_address_2(),
                            "locality"       => $order->get_shipping_city(),
                            "postal_code"    => $order->get_shipping_postcode(),
                            "country"        => $order->get_shipping_country(),
                        );
                        $paymentRequest["shipping_address"] = $shipping_address;
                        // http://localhost:10004/wordpress/checkout/?cash_request_id=GRR_q5pad8wf4cy934435vd85ghn
                        $api_response = $this->wc_cash_app_payment_api( $paymentRequest, $order );
                        
                        if ( !is_wp_error( $api_response ) ) {
                            $response = wp_remote_retrieve_body( $api_response );
                            // wc_add_notice( $response, 'success' );
                            $result = json_decode( $response, true );
                            // {
                            // 	"payment": {
                            // 		"id": "***ZY",
                            // 		"created_at": "2023-05-19T06:07:17.089Z",
                            // 		"updated_at": "2023-05-19T06:07:17.872Z",
                            // 		"amount_money": { "amount": 117, "currency": "USD" },
                            // 		"app_fee_money": { "amount": 2, "currency": "USD" },
                            // 		"status": "COMPLETED",
                            // 		"delay_duration": "**H",
                            // 		"source_type": "WALLET",
                            // 		"location_id": "**Z0SR",
                            // 		"order_id": "**dZY",
                            // 		"reference_id": "100",
                            // 		"note": "Payment for order #100",
                            // 		"total_money": { "amount": 117, "currency": "USD" },
                            // 		"approved_money": { "amount": 117, "currency": "USD" },
                            // 		"receipt_number": "**5",
                            // 		"receipt_url": "https://squareup.com/receipt/preview/***ZY",
                            // 		"delay_action": "CANCEL",
                            // 		"delayed_until": "2023-05-26T06:07:17.089Z",
                            // 		"wallet_details": {
                            // 			"status": "CAPTURED",
                            // 			"brand": "CASH_APP",
                            // 			"cash_app_details": { "buyer_cashtag": "$" }
                            // 		},
                            // 		"application_details": {
                            // 			"square_product": "ECOMMERCE_API",
                            // 			"application_id": "sq0idp-**RzvQ"
                            // 		},
                            // 		"version_token": "**Y35o"
                            // 	}
                            // }
                            $payment_result = $result['payment'];
                            
                            if ( !empty($payment_result) && $payment_result['status'] == 'COMPLETED' ) {
                                // wc_cash_app_pay || $payment_result['status'] == 'APPROVED' || $payment_result['status'] == 'PENDING'
                                $sqp_receipt = $payment_result['receipt_url'];
                                wc_add_notice( $payment_result['status'] . " {$sqp_receipt}", 'success' );
                                $note = wp_kses_post( "<p>" . sprintf( __( 'Here is your <a href="%s" target="blank">Square receipt</a>', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $sqp_receipt ) . "</p>" );
                                $order->add_order_note( $note, 1 );
                                $note = wp_kses_post( '<p>Check your <a href="https://squareup.com/dashboard/sales/transactions" target="blank">Square sales transactions</a> for details</p>' );
                                $order->add_order_note( $note );
                                $order->update_meta_data( 'sq_payment_token', $sq_payment_token );
                                $order->update_meta_data( 'sqp_idempotency', $sq_idempotency );
                                $order->update_meta_data( 'sqp_id', $payment_result['id'] );
                                $order->update_meta_data( 'sqp_status', $payment_result['status'] );
                                $order->update_meta_data( 'sqp_order_id', $payment_result['order_id'] );
                                $order->update_meta_data( 'sqp_receipt', $sqp_receipt );
                                $order->save();
                                $order->payment_complete();
                                $order->reduce_order_stock();
                                // Empty cart
                                global  $woocommerce ;
                                $woocommerce->cart->empty_cart();
                                // Redirect to the thank you page
                                return array(
                                    'result'   => 'success',
                                    'redirect' => $this->get_return_url( $order ),
                                );
                            } else {
                                // {
                                // 	"errors": [
                                // 		{
                                // 			"code": "REFUND_AMOUNT_INVALID",
                                // 			"detail": "The requested refund amount exceeds the amount available to refund.",
                                // 			"field": "amount_money.amount",
                                // 			"category": "REFUND_ERROR"
                                // 		}
                                // 	]
                                // }
                                $errors_result = $result['errors'];
                                
                                if ( !empty($errors_result) ) {
                                    $error_list = "<ul>";
                                    foreach ( $errors_result as $error ) {
                                        $error_list .= '<li>' . $error['category'] . ' ' . $error['code'] . ': ' . $error['detail'] . ' - ' . $error['field'] . '</li>';
                                        // $error_list .= '<li>' . $error['code'] . ': ' . $error['detail'] . '</li>';
                                    }
                                    $error_list .= '</ul>';
                                    wc_add_notice( " {$error_list}", 'error' );
                                    $this->wccp_log( "Checkout: Square API errors: " . var_dump( $errors_result ), 'error' );
                                } else {
                                    wc_add_notice( json_encode( $result ), 'error' );
                                    $this->wccp_log( "Checkout error due to " . json_encode( $result ), 'error' );
                                }
                            
                            }
                        
                        } else {
                            $error_message = $api_response->get_error_message();
                            wc_add_notice( " Something went wrong {$error_message}", 'error' );
                            $this->wccp_log( "Checkout: WP_Error Something went wrong {$error_message}", 'error' );
                            // throw new Exception( $error_message );
                        }
                    
                    } catch ( \Throwable $th ) {
                        // // Executed only in PHP 7, will not match in PHP 5.x
                        // print_r($th);
                        // wc_add_notice( " " . $th, 'error' );
                        wc_add_notice( " " . $th->getMessage(), 'error' );
                        $this->wccp_log( "Checkout: " . $th->getMessage(), 'error' );
                        return;
                    } catch ( \Exception $e ) {
                        // // Executed only in PHP 5.x, will not be reached in PHP 7
                        wc_add_notice( " " . $e->getMessage(), 'error' );
                        $this->wccp_log( "Checkout: " . $e->getMessage(), 'error' );
                        // $errors = $e->getErrors();
                        // // print_r($errors);
                        // // wc_add_notice( " " . json_encode($errors), 'error' );
                        // $error_list = '<ul>';
                        // foreach ($errors as $error) {
                        // 	$error_list .= '<li>' . $error->getDetail() . '</li>';
                        // }
                        // $error_list .= '</ul>';
                        // wc_add_notice( " $error_list", 'error' );
                        // wc_add_notice( "Square Error. Please try again", 'error' );
                        return;
                    }
                } else {
                    $error_message = ( is_wp_error( $order ) ? $order->get_error_message() : null );
                    wc_add_notice( "Something went wrong {$error_message}. Try again", 'error' );
                    $this->wccp_log( "Checkout: WP_Error Something went wrong {$error_message}", 'error' );
                    return;
                }
            
            } catch ( \Throwable $th ) {
                // // Executed only in PHP 7, will not match in PHP 5.x
                // print_r($th);
                // wc_add_notice( " " . $th, 'error' );
                wc_add_notice( " " . $th->getMessage(), 'error' );
                $this->wccp_log( "Checkout: " . $th->getMessage(), 'error' );
                return;
            } catch ( \Exception $e ) {
                // // Executed only in PHP 5.x, will not be reached in PHP 7
                wc_add_notice( " " . $e->getMessage(), 'error' );
                $this->wccp_log( "Checkout: " . $e->getMessage(), 'error' );
                return;
            }
        }
        
        // /**
        //  * Add payment and transaction information as class members of WC_Order
        //  * instance.  The standard information that can be added includes:
        //  *
        //  * $order->payment_total           - the payment total
        //  * $order->customer_id             - optional payment gateway customer id (useful for tokenized payments, etc)
        //  * $order->payment->type           - one of 'credit_card' or 'check'
        //  * $order->description             - an order description based on the order
        //  * $order->unique_transaction_ref  - a combination of order number + retry count, should provide a unique value for each transaction attempt
        //  *
        //  * Note that not all gateways will necessarily pass or require all of the
        //  * above.  These represent the most common attributes used among a variety
        //  * of gateways, it's up to the specific gateway implementation to make use
        //  * of, or ignore them, or add custom ones by overridding this method.
        //  *
        //  * The returned order is expected to be used in a transaction request.
        //  * @param int|\WC_Order $order the order or order ID being processed
        //  * @return \WC_Order object with payment and transaction information attached
        //  */
        // public function get_order( $order ) {
        // 	if ( is_numeric( $order ) ) { $order = wc_get_order( $order ); }
        // 	if ( ! $order instanceof WC_Order ) { return; }
        // 	// set payment total here so it can be modified for later by add-ons like subscriptions which may need to charge an amount different than the get_total()
        // 	$order->payment_total = number_format( $order->get_total(), 2, '.', '' );
        // 	// $order->customer_id = '';
        // 	// logged in customer?
        // 	if ( 0 != $order->get_user_id() && false !== ( $customer_id = $this->get_customer_id( $order->get_user_id(), array( 'order' => $order ) ) ) ) {
        // 		$order->customer_id = $customer_id;
        // 	}
        // 	// add payment info
        // 	$order->payment = new stdClass();
        // 	// payment type (credit_card/check/etc)
        // 	$order->payment->type = 'wallet';
        // 	/* translators: Placeholders: %1$s - site title, %2$s - order number */
        // 	//  wp_specialchars_decode( Square_Helper::get_site_name(), ENT_QUOTES )
        // 	$order->description = sprintf( esc_html__( '%1$s - Order %2$s', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), get_bloginfo('name'), $order->get_order_number() );
        // 	$order = $this->get_order_with_unique_transaction_ref( $order );
        // 	/**
        // 	 * Filters the base order for a payment transaction.
        // 	 *
        // 	 * Actors can use this filter to adjust or add additional information to
        // 	 * the order object that gateways use for processing transactions.
        // 	 *
        // 	 * @param \WC_Order $order order object
        // 	 * @param Payment_Gateway $this payment gateway instance
        // 	 */
        // 	return apply_filters( 'wc_payment_gateway_' . $this->id . '_get_order_base', $order, $this );
        // }
        // /**
        //  * Return the order information in a version independent way
        //  *
        //  * @param WC_Order $order
        //  * @return array
        //  */
        // public function get_order_info($order) {
        //     $data = array(
        //         "id" => '',
        //         "payment_method" => '',
        //         "billing_company" => '',
        //         "billing_first_name" => '',
        //         "billing_last_name" => '',
        //         "billing_email" => '',
        //         "billing_phone" => '',
        //         "billing_address_1" => '',
        //         "billing_address_2" => '',
        //         "billing_city" => '',
        //         "billing_state" => '',
        //         "billing_postcode" => '',
        //         "billing_country" => '',
        //         "order_total" => ''
        //     );
        //     if (version_compare(WC_VERSION, '3.0', '<')) {
        //         //Do it the old school way
        //         $data["id"] = sanitize_text_field($order->id);
        //         $data["payment_method"] = sanitize_text_field($order->payment_method);
        //         $data["billing_company"] = sanitize_text_field($order->billing_company);
        //         $data["billing_first_name"] = sanitize_text_field($order->billing_first_name);
        //         $data["billing_last_name"] = sanitize_text_field($order->billing_last_name);
        //         $data["billing_email"] = sanitize_text_field($order->billing_email);
        //         $data["billing_phone"] = sanitize_text_field($order->billing_phone);
        //         $data["billing_address_1"] = sanitize_text_field($order->billing_address_1);
        //         $data["billing_address_2"] = sanitize_text_field($order->billing_address_2);
        //         $data["billing_city"] = sanitize_text_field($order->billing_city);
        //         $data["billing_state"] = sanitize_text_field($order->billing_state);
        //         $data["billing_postcode"] = sanitize_text_field($order->billing_postcode);
        //         $data["billing_country"] = sanitize_text_field($order->billing_country);
        //         $data["order_total"] = sanitize_text_field($order->order_total);
        //     } else {
        //         //New school
        //         $data["id"] = sanitize_text_field($order->get_id());
        //         $data["payment_method"] = sanitize_text_field($order->get_payment_method());
        //         $data["billing_company"] = sanitize_text_field($order->get_billing_company());
        //         $data["billing_first_name"] = sanitize_text_field($order->get_billing_first_name());
        //         $data["billing_last_name"] = sanitize_text_field($order->get_billing_last_name());
        //         $data["billing_email"] = sanitize_text_field($order->get_billing_email());
        //         $data["billing_phone"] = sanitize_text_field($order->get_billing_phone());
        //         $data["billing_address_1"] = sanitize_text_field($order->get_billing_address_1());
        //         $data["billing_address_2"] = sanitize_text_field($order->get_billing_address_2());
        //         $data["billing_city"] = sanitize_text_field($order->get_billing_city());
        //         $data["billing_state"] = sanitize_text_field($order->get_billing_state());
        //         $data["billing_postcode"] = sanitize_text_field($order->get_billing_postcode());
        //         $data["billing_country"] = sanitize_text_field($order->get_billing_country());
        //         $data["order_total"] = sanitize_text_field($order->get_total());
        //     }
        //     return $data;
        // }
        // /**
        //  * @param \WC_Order|int $order the order being processed
        //  * @param float|null $amount amount to capture or null for the full order amount
        //  * @return \WC_Order
        //  */
        // public function get_order_for_capture( $order, $amount = null ) {
        // 	if ( is_numeric( $order ) ) {
        // 		$order = wc_get_order( $order );
        // 	}
        // 	// add capture info
        // 	$order->capture = new \stdClass();
        // 	$total_captured = $this->get_order_meta( $order, 'capture_total' );
        // 	// if no amount is specified, as in a bulk capture situation, always use the amount remaining
        // 	if ( ! $amount ) {
        // 		$amount = (float) $order->get_total() - (float) $total_captured;
        // 	}
        // 	$order->capture->amount = Square_Helper::number_format( $amount );
        // 	/* translators: Placeholders: %1$s - site title, %2$s - order number. Definitions: Capture as in capture funds from a credit card. */
        // 	$order->capture->description = sprintf( esc_html__( '%1$s - Capture for Order %2$s', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), wp_specialchars_decode( Square_Helper::get_site_name() ), $order->get_order_number() );
        // 	$order->capture->trans_id    = $this->get_order_meta( Order_Compatibility::get_prop( $order, 'id' ), 'trans_id' );
        // 	/**
        // 	 * Direct Gateway Capture Get Order Filter.
        // 	 *
        // 	 * Allow actors to modify the order object used for performing charge captures.
        // 	 * @param \WC_Order $order order object
        // 	 * @param Payment_Gateway $this instance
        // 	 */
        // 	return apply_filters( 'wc_payment_gateway_' . $this->id . '_get_order_for_capture', $order, $this );
        // }
        protected function wc_cash_app_refund_api( $body )
        {
            // curl https://connect.squareupsandbox.com/v2/refunds \
            // -X POST \
            // -H 'Square-Version: 2023-04-19' \
            // -H 'Authorization: Bearer {ACCESS_TOKEN}' \
            // -H 'Content-Type: application/json' \
            // -d '{
            //   "idempotency_key": "{UNIQUE_KEY}",
            //   "payment_id": "{PAYMENT_ID}",
            //   "amount_money": {
            // 	"amount": $amount * 100,
            // 	"currency": $currency
            //   }
            // }'
            $args = array(
                'headers' => array(
                'Content-Type'   => 'application/json',
                'Authorization'  => "Bearer {$this->SQ_Access_Token}",
                'Square-Version' => '2021-06-16',
            ),
                'body'    => json_encode( $body ),
            );
            $response = wp_remote_post( 'https://connect.squareup.com/v2/refunds', $args );
            return $response;
        }
        
        /**
         * Processes a refund.
         * @return bool|\WP_Error true on success, or a WP_Error object on failure/error
         */
        public function process_refund( $order_id, $amount = null, $reason = '' )
        {
            if ( !$order_id ) {
                return $this->wc_cash_app_pay_get_refund_error( __( 'Invalid order', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) );
            }
            $order = $this->wc_cash_app_pay_get_refund_order( $order_id, $amount, $reason );
            // let implementations/actors error out early (e.g. order is missing required data for refund, etc)
            if ( is_wp_error( $order ) ) {
                return $order;
            }
            if ( !$order instanceof WC_Order ) {
                return $this->wc_cash_app_pay_get_refund_error( __( 'Invalid order', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) );
            }
            if ( $this->id === $order->get_payment_method() ) {
                // // if captures are supported and the order has an authorized, but not captured charge, void it instead
                // if ( $this->supports_voids() && ! $this->get_capture_handler()->is_order_captured( $order ) ) {
                // 	return $this->process_void( $order );
                // }
                try {
                    // when full amount is refunded, update status to refunded
                    if ( $order->get_total() == $order->get_total_refunded() ) {
                        $this->wc_cash_app_pay_mark_order_as_refunded( $order );
                    }
                    if ( !(floatval( $amount ) > 0) ) {
                        $amount = $order->get_total();
                    }
                    $currency = $order->get_currency();
                    $amount_money = $this->wc_cash_app_set_money( 'amount_money', floatval( $amount ) * 100 );
                    $app_fee_money = $this->wc_cash_app_set_money( 'app_fee_money', intval( $amount ) + 1 );
                    $sq_payment_token = $order->get_meta( 'sq_payment_token' );
                    $sq_idempotency = $order->get_meta( 'sqp_idempotency' );
                    $sq_idempotency = uniqid( $sq_idempotency );
                    $sqp_id = $order->get_meta( 'sqp_id' );
                    $sqp_order_id = $order->get_meta( 'sqp_order_id' );
                    $sqp_receipt = $order->get_meta( 'sqp_receipt' );
                    $body = array(
                        'idempotency_key' => $sq_idempotency,
                        'payment_id'      => $sqp_id,
                        'amount_money'    => array(
                        'amount'   => floatval( $amount ) * 100,
                        'currency' => $currency,
                    ),
                    );
                    // $RefundRequest = new RefundPaymentRequest($sq_payment_token, $sq_idempotency);
                    // $api_response = $client->getRefundsApi()->refundPayment($RefundRequest);
                    $api_response = $this->wc_cash_app_refund_api( $body );
                    
                    if ( !is_wp_error( $api_response ) ) {
                        $response = wp_remote_retrieve_body( $api_response );
                        // {
                        // 	"refund": {
                        // 		"id": "18T***Y_ZzYcg***yPL",
                        // 		"status": "PENDING",
                        // 		"amount_money": { "amount": 117, "currency": "USD" },
                        // 		"payment_id": "18T***Y",
                        // 		"order_id": "4my***6YY",
                        // 		"created_at": "2023-05-23T16:45:08.193Z",
                        // 		"updated_at": "2023-05-23T16:45:08.193Z",
                        // 		"app_fee_money": { "amount": 2, "currency": "USD" },
                        // 		"location_id": "L**SR",
                        // 		"destination_type": "WALLET"
                        // 	}
                        // }
                        $result = json_decode( $response, true );
                        // echo '<pre>'; var_dump($result); echo '</pre>';
                        $refund_result = $result['refund'];
                        $errors_result = $result['errors'];
                        
                        if ( !empty($errors_result) ) {
                            // {
                            // 	"errors": [
                            // 		{
                            // 			"code": "REFUND_AMOUNT_INVALID",
                            // 			"detail": "The requested refund amount exceeds the amount available to refund.",
                            // 			"field": "amount_money.amount",
                            // 			"category": "REFUND_ERROR"
                            // 		}
                            // 	]
                            // }
                            $error_list = "<p>Errors regarding your {$amount} refund for {$reason}:</p><ul>";
                            foreach ( $errors_result as $error ) {
                                $error_list .= '<li>' . $error['code'] . ': ' . $error['detail'] . '</li>';
                            }
                            $error_list .= '</ul>';
                            if ( $sqp_receipt ) {
                                $error_list .= '<p>Check your <a href="' . $sqp_receipt . '" target="blank">Square receipt</a> or your <a href="https://squareup.com/dashboard/sales/transactions" target="blank">Square sales transactions</a> for details</p>';
                            }
                            $error = $this->wc_cash_app_pay_get_refund_error( $error_list, $order );
                            if ( !is_wp_error( $error ) ) {
                                $error = new WP_Error( "wc_{$this->id}_refund_failed", $error_list );
                            }
                            // throw new Exception( $error );
                            return $error;
                        } else {
                            
                            if ( !empty($refund_result) && ($refund_result['status'] == 'COMPLETED' || $refund_result['status'] == 'PENDING') ) {
                                $sqr_id = $refund_result['id'];
                                $order->update_meta_data( "sqr_idempotency_{$sqr_id}", $sq_idempotency );
                                // $order->update_meta_data( "sqr_id_$sqr_id", $sqr_id );
                                $order->update_meta_data( "sqr_id", $sqr_id );
                                $order->update_meta_data( "sqr_status_{$sqr_id}", $refund_result['status'] );
                                $order->update_meta_data( "sqr_order_id_{$sqr_id}", $refund_result['order_id'] );
                                // $order->update_meta_data( "sqr_refund_amount_$sqr_id", $order->refund->amount );
                                $order->save();
                                // add order note
                                $this->wc_cash_app_pay_add_refund_note( $order, $refund_result['id'], $refund_result['status'] );
                                if ( $refund_result['status'] == 'COMPLETED' ) {
                                    // // Fires after a refund is successfully processed.
                                    // do_action( 'wc_payment_gateway_' . $this->id . '_refund_processed', $order, $this );
                                }
                                return true;
                            } else {
                                // // throw new Exception( $api_response );
                                // return $api_response;
                                $error = $this->wc_cash_app_pay_get_refund_error( $api_response->get_status_message(), $order, $api_response->get_status_code() );
                                // throw new Exception( $error );
                                return $error;
                            }
                        
                        }
                    
                    } else {
                        // $error_message = $api_response->get_error_message();
                        return $api_response;
                    }
                    
                    // // $body = new \Square\Models\RefundPaymentRequest();
                    // // $api_response = $client->getRefundsApi()->refundPayment($body);
                    // // if ($api_response->isSuccess()) {
                    // // 	$result = $api_response->getResult();
                    // // } else {
                    // // 	$errors = $api_response->getErrors();
                    // // }
                    // // // allow gateways to void an order in response to a refund attempt
                    // // if ( $this->supports_voids() && $this->maybe_void_instead_of_refund( $order, $response ) ) {
                    // // 		return $this->process_void( $order );
                    // // }
                    // if ( $response->transaction_approved() ) {
                    // 	// add order note -- get refund id from response
                    // 	// $this->wc_cash_app_pay_add_refund_note( $order, $refund_result['id'], $refund_result['status'] );
                    // 	// when full amount is refunded, update status to refunded
                    // 	if ( $order->get_total() == $order->get_total_refunded() ) {
                    // 		$this->wc_cash_app_pay_mark_order_as_refunded( $order );
                    // 	}
                    // 	/**
                    // 	 * Fires after a refund is successfully processed.
                    // 	 *
                    // 	 * @param $order order object
                    // 	 * @param Payment_Gateway $gateway payment gateway instance
                    // 	 */
                    // 	do_action( 'wc_payment_gateway_' . $this->id . '_refund_processed', $order, $this );
                    // 	return true;
                    // } else {
                    // 	$error = $this->wc_cash_app_pay_get_refund_error( $response->get_status_message(), $order, $response->get_status_code() );
                    // 	$order->add_order_note( $error->get_error_message() );
                    // 	return $error;
                    // }
                    // } catch (\ApiException $e) {
                    // 	$error = $this->wc_cash_app_pay_get_refund_error( json_encode($e->getResponseBody()) . "<br>" . json_encode($e->getContext()), $order );
                    // 	return $error;
                } catch ( \Throwable $th ) {
                    $error = $this->wc_cash_app_pay_get_refund_error( $th->getMessage(), $order, $th->getCode() );
                    return $error;
                } catch ( \Exception $e ) {
                    $error = $this->wc_cash_app_pay_get_refund_error( $e->getMessage(), $order, $e->getCode() );
                    return $error;
                }
            }
        }
        
        /**
         * Adds an order note with the amount and (optional) refund transaction ID.
         *
         * @param $order order object
         * @param Payment_Gateway_API_Response $response transaction response
         */
        protected function wc_cash_app_pay_add_refund_note( $order, $refund_id = null, $status = null )
        {
            
            if ( $status == 'COMPLETED' ) {
                $status = 'Approved';
            } else {
                
                if ( $status == 'PENDING' ) {
                    $status = 'Processing';
                } else {
                    $status = 'Failed';
                }
            
            }
            
            $message = sprintf( esc_html__( $status . ' %1$s Refund in the amount of %2$s.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $this->method_title, wc_price( $order->refund->amount, array(
                'currency' => $order->get_currency(),
            ) ) );
            // adds the transaction id (if any) to the order note
            if ( $refund_id ) {
                $message .= ' ' . sprintf( esc_html__( '(Transaction ID %s)', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $refund_id );
            }
            $this->wccp_log( $message );
            $order->add_order_note( $message, 1 );
        }
        
        protected function wc_cash_app_pay_get_refund_error( $error_message, $order = null, $error_code = null )
        {
            
            if ( $error_code ) {
                $message = sprintf(
                    esc_html__( '%1$s Refund Failed: %2$s - %3$s', WCCASHAPP_PLUGIN_TEXT_DOMAIN ),
                    $this->method_title,
                    $error_code,
                    $error_message
                );
            } else {
                $message = sprintf( esc_html__( '%1$s Refund Failed: %2$s', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $this->method_title, $error_message );
            }
            
            $this->wccp_log( $message, 'error' );
            $error = new WP_Error( "wc_{$this->id}_refund_failed", wp_strip_all_tags( wp_kses_post( $message ) ) );
            
            if ( is_wp_error( $error ) ) {
                if ( $order ) {
                    // $order->add_order_note( $error->get_error_message() );
                    $order->add_order_note( $message );
                }
                return $error;
            } else {
                return null;
            }
        
        }
        
        /**
         * Mark an order as refunded.
         * This should only be used when the full order amount has been refunded.
         */
        protected function wc_cash_app_pay_mark_order_as_refunded( $order )
        {
            $order_note = sprintf( esc_html__( '%1s Order %2s completely refunded.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), $this->method_title, $order->get_order_number() );
            // Mark order as refunded if not already set
            
            if ( !$order->has_status( 'refunded' ) ) {
                $order->update_status( apply_filters( 'woocommerce_cashapp_process_payment_order_status', 'refunded', $order ), $order_note );
                // $order->update_status( 'refunded', $order_note );
                $this->wccp_log( $order_note );
            } else {
                $order->add_order_note( $order_note, 1 );
            }
        
        }
        
        /**
         * Add refund information as class members of WC_Order
         *
         * $order->refund->amount = refund amount
         * $order->refund->reason = user-entered reason text for the refund
         * $order->refund->trans_id = the ID of the original payment transaction for the order
         *
         * @param \WC_Order|int $order_info order being processed
         * @return \WC_Order|\WP_Error object with refund information attached
         */
        protected function wc_cash_app_pay_get_refund_order( $order_info, $amount, $reason )
        {
            
            if ( is_numeric( $order_info ) ) {
                $order = wc_get_order( $order_info );
            } else {
                $order = $order_info;
            }
            
            if ( !$order instanceof WC_Order ) {
                return;
            }
            
            if ( $this->id === $order->get_payment_method() ) {
                try {
                    // add refund info
                    $order->refund = new \stdClass();
                    $order->refund->amount = number_format(
                        $amount,
                        2,
                        '.',
                        ''
                    );
                    $order->refund->reason = ( $reason ? $reason : sprintf( esc_html__( '%1$s - Refund for Order %2$s', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), get_bloginfo( 'name' ), $order->get_order_number() ) );
                    $order->refund->trans_id = $order->get_meta( 'sqp_id' );
                } catch ( \Throwable $th ) {
                    // wp_die("$th " . json_encode($th));
                }
                return $order;
            } else {
                return new WP_Error( "wc_{$this->id}_invalid_order", __( 'Invalid order.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) );
            }
        
        }
        
        // Webhook
        public function webhook()
        {
            return;
            // $order = wc_get_order( $_GET['id'] );
            // $order->payment_complete();
            // update_option('webhook_debug', $_GET);
        }
    
    }
} else {
    require_once WCCASHAPP_PLUGIN_DIR . 'includes/notifications/woocommerce.php';
}
