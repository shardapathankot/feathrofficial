<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {
    class WC_Cashapp_Gateway extends WC_Payment_Gateway
    {
        protected  $CashAppEmailReceiptsKey ;
        // protected $CashAppForwardingEmail;
        // protected $CashAppForwardingFormat;
        public function __construct()
        {
            $this->id = 'cashapp';
            // payment gateway plugin ID
            $this->icon = WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cashapp_35.png';
            // URL of the icon that will be displayed on checkout page near your gateway name
            $this->has_fields = true;
            // in case you need a custom form
            $this->method_title = 'Cash App';
            $this->method_description = '<p>Easily receive Cash App payments.</p>
			<p>If you are a Square merchant, <strong>enable <a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=cash-app-pay' ) ) . '">Cash App Pay</a> instead</p>
			<p>See how the <a href="' . admin_url( 'admin.php?page=wc_cashapp_compared' ) . '">Cash App Pay payment method compares to Cash App Link payment method</a></p>';
            // will be displayed on the options page
            global  $cashapp_fs ;
            $upgrade_url = cashapp_fs()->get_upgrade_url();
            $this->method_description .= '<br><p>Unlock the NEW design for <a href="' . $upgrade_url . '">Cash App Link PRO</a></p>
						<a href="' . $upgrade_url . '"><img class="shadow" src="' . WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cash_app_checkout.jpg' . '" width="auto" height="200" alt="Cash App Link on the checkout page" /></a>';
            $this->init_settings();
            $this->enabled = $this->get_option( 'enabled' );
            $this->title = ( $this->get_option( 'checkout_title' ) ? $this->get_option( 'checkout_title' ) : $this->method_title );
            $this->ReceiverCASHAPPNo = $this->get_option( 'ReceiverCASHAPPNo' );
            $this->ReceiverCashApp = $this->get_option( 'ReceiverCashApp' );
            $this->ReceiverCashAppOwner = $this->get_option( 'ReceiverCashAppOwner' );
            $this->ReceiverCASHAPPEmail = $this->get_option( 'ReceiverCASHAPPEmail' );
            $this->CashAppForwardingURL = wp_kses_post( get_bloginfo( 'url' ) . '/wp-json/wc-cashapp/v1/update-cashapp-order' );
            $this->update_option( 'CashAppForwardingURL', $this->CashAppForwardingURL );
            // $this->CashAppEmailReceiptsKey = $this->get_option( 'CashAppEmailReceiptsKey' );
            $this->display_cashapp = $this->get_option( 'display_cashapp' );
            $this->display_cashapp_logo_button = $this->get_option( 'display_cashapp_logo_button' );
            $this->CashAppStockManagement = $this->get_option( 'CashAppStockManagement' );
            $this->checkout_description = $this->get_option( 'checkout_description' );
            $this->cashapp_notice = $this->get_option( 'cashapp_notice' );
            $this->store_instructions = $this->get_option( 'store_instructions' );
            $this->enableNote = $this->get_option( 'enableNote' );
            $this->order_note = $this->get_option( 'order_note' );
            $this->disableMenu = $this->get_option( 'disableMenu' ) ?? 'no';
            $this->processOrder = $this->get_option( 'processOrder' ) ?? 'no';
            $this->enable_debug = $this->get_option( 'enable_debug' );
            $this->toggleSupport = $this->get_option( 'toggleSupport' );
            $this->toggleTutorial = $this->get_option( 'toggleTutorial' );
            $this->toggleCredits = $this->get_option( 'toggleCredits' );
            // hold stock admin_url('admin.php?page=wc-settings&tab=products&section=inventory)
            $test = ( isset( $this->ReceiverCashApp ) && !empty($this->ReceiverCashApp) ? ' <a href="' . esc_attr( $this->wc_cashapp_payment_url( 1 ) ) . '" target="_blank">Test</a>' : '' );
            $new = ' <sup style="color:#0c0">NEW</sup>';
            $newFeature = " <sup style='color:#c00;'>NEW FEATURE</sup>";
            $improvedFeature = " <sup style='color:#0c0;'>IMPROVED FEATURE</sup>";
            $comingSoon = " <sup style='color:#00c;'>COMING SOON</sup>";
            $emrcpts = ' <a href="' . esc_attr( wp_kses_post( admin_url( 'admin.php?page=wc_cashapp_automated_status' ) ) ) . '" target="_blank">CONNECT</a>';
            $default_checkout_description = '<p>Please <strong>use your Order Number (available once you place order)</strong> as the payment reference.</p>';
            // $default_checkout_description = 'Send money to $cashtag or click the Cash App button below';
            $default_cashapp_notice = "<p>We are checking our systems to confirm that we received the money. If you haven't sent the money already, please make sure to do so now.</p>" . '<p>Once confirmed, we will proceed with the shipping and delivery options you chose.</p>' . '<p>Thank you for doing business with us! You will be updated regarding your order details soon.</p>';
            $default_store_instructions = "Please send the total amount requested to our store if you haven't yet";
            $default_order_note = esc_html__( 'Your order was received!', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '<br><br>' . sprintf( __( 'We are checking our Cash App to confirm that we received the %s you sent so we can start processing your order.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), '<strong>**order_total**</strong>' ) . '<br><br>' . esc_html__( 'Thank you for doing business with us', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '!<br> ' . esc_html__( 'You will be updated regarding your order details soon', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '<br><br>' . esc_html__( 'Kindest Regards', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ',<br>**shop_name**<br>**shop_email**<br>**shop_url**<br>';
            // upgrade display_cashapp
            
            if ( $this->display_cashapp === 'no' ) {
                $this->update_option( 'display_cashapp', '1' );
            } else {
                if ( $this->display_cashapp === 'yes' ) {
                    $this->update_option( 'display_cashapp', '2' );
                }
            }
            
            $pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '"><sup style="color:red">PRO</sup></a>';
            $edit_with_pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '">APPLY CHANGES WITH PRO</a>';
            $this->form_fields = array(
                'enabled'                     => array(
                'title'   => 'Enable CASHAPP' . $test,
                'label'   => 'Check to Enable / Uncheck to Disable',
                'type'    => 'checkbox',
                'default' => 'no',
            ),
                'checkout_title'              => array(
                'title'       => 'Checkout Title',
                'type'        => 'text',
                'description' => 'This is the title which the user sees on the checkout page.',
                'default'     => $this->title,
                'placeholder' => $this->title,
            ),
                'ReceiverCASHAPPNo'           => array(
                'title'       => 'Receiver Cash App No',
                'type'        => 'text',
                'description' => 'This is the phone number associated with your store Cash App account or your receiving Cash App account. Customers will send money to this number',
                'placeholder' => "+1234567890",
            ),
                'ReceiverCashApp'             => array(
                'title'       => 'Receiver Cash App account' . $test,
                'type'        => 'text',
                'description' => 'This is the Cash App account associated with your store Cash App account. Customers will send money to this Cash App account',
                'default'     => '$',
                'placeholder' => '$cashId',
            ),
                'ReceiverCashAppOwner'        => array(
                'title'       => "Receiver Cash App Owner's Name",
                'type'        => 'text',
                'description' => 'This is the name associated with your store Cash App account. Customers will send money to this Cash App account name',
                'placeholder' => 'Jane D',
            ),
                'ReceiverCASHAPPEmail'        => array(
                'title'       => "Receiver Cash App Owner's Email",
                'type'        => 'text',
                'description' => 'This is the email associated with your store Cash App account or your receiving Cash App account. Customers will send money to this email',
                'default'     => "@gmail.com",
                'placeholder' => "email@website.com",
            ),
                'CashAppForwardingURL'        => array(
                'title'       => 'Connect your Email Receipts via emailreceipts.io' . $emrcpts . $new,
                'type'        => 'text',
                'description' => 'This is the URL that will be imported to emailreceipts.io while setting up' . $emrcpts,
                'default'     => $this->CashAppForwardingURL,
                'placeholder' => $this->CashAppForwardingURL,
                'css'         => 'width:80%; pointer-events: none;',
                'class'       => 'disabled',
            ),
                'display_cashapp'             => array(
                'title'       => 'Checkout page design templates' . $improvedFeature . $pro,
                'label'       => 'Choose how you want customers to see the Cash App info on checkout' . $edit_with_pro,
                'type'        => 'select',
                'description' => 'Choose how you want customers to see the Cash App info on checkout.
						<p><strong>PRO designs</strong> are enhanced with extra features such as <strong>copy to clipboard</strong>, <strong>QR code</strong>, <strong>Cash App button/link</strong>, etc to help autofill info when moving to Cash App.</p>
						<p><strong>Design 1:</strong> removes the Cash App info on checkout.</p>
						<p><strong>Design 2:</strong> shows the Cash App info on checkout in full width columns.</p>
						<p><strong>Design 3:</strong> shows the Cash App info on checkout in half width columns.</p>' . $edit_with_pro,
                'default'     => '2',
                'options'     => array(
                '1' => '1: remove the Cash App info on checkout' . $edit_with_pro,
                '2' => '2: show the Cash App info on checkout (full width columns)',
                '3' => '3: show the Cash App info on checkout (half width columns)' . $edit_with_pro,
            ),
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'display_cashapp_logo_button' => array(
                'title'       => 'Cash App info displayed on checkout' . $pro,
                'label'       => 'Check to show the Cash App logo button / Uncheck to remove the Cash App logo button' . $edit_with_pro,
                'description' => 'Display the Cash App logo image and/or QR code button on the checkout page',
                'type'        => 'select',
                'default'     => 'no',
                'options'     => array(
                'no'  => 'Display ONLY the Cash App logo image button on the checkout page',
                'yes' => 'Display BOTH the Cash App logo image and QR code button on the checkout page',
            ),
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'processOrder'                => array(
                'title'       => 'Enable/Disable processing orders automatically' . $new . $pro,
                'label'       => 'Check to enable processing orders / Uncheck to disable processing orders' . $edit_with_pro,
                'type'        => 'checkbox',
                'description' => '<p>When checked, orders will automatically be processed after checkout (whether payment was sent or not).</p>
							<p>When unchecked, orders will be put on-hold until you manually process them or use emailreceipts.io to auto-process them</p>',
                'default'     => 'no',
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'CashAppStockManagement'      => array(
                'title'       => 'Reduce Stock ONLY after payment receipt' . $pro,
                'label'       => 'Check to to reduce stock when order goes to processing / Uncheck to reduce stock when order goes on-hold' . $edit_with_pro,
                'type'        => 'checkbox',
                'description' => 'If you want to reduce stock once payment is received, check this box',
                'default'     => 'no',
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'checkout_description'        => array(
                'title'       => 'Checkout Page Message' . $pro,
                'type'        => 'textarea',
                'description' => "This is the text a customer sees in the payment gateway box on the checkout page. {$edit_with_pro}<br>Default:<br>{$default_checkout_description}",
                'default'     => $default_checkout_description,
                'css'         => 'width:80%; pointer-events: none;',
                'class'       => 'disabled',
            ),
                'cashapp_notice'              => array(
                'title'       => 'Thank You Page Message' . $pro,
                'type'        => 'textarea',
                'description' => "This is the text a customer sees on the thank you/order confirmation page after placing an order. {$edit_with_pro}<br>Default:<br>{$default_cashapp_notice}",
                'default'     => $default_cashapp_notice,
                'css'         => 'width:80%; pointer-events: none;',
                'class'       => 'disabled',
            ),
                'store_instructions'          => array(
                'title'       => 'Thank You Page Store Instructions' . $pro,
                'type'        => 'textarea',
                'description' => "Store Instructions that will be added to the thank you page and emails. {$edit_with_pro}<br>Default:<br>{$default_store_instructions}",
                'default'     => $default_store_instructions,
                'css'         => 'width:80%; pointer-events: none;',
                'class'       => 'disabled',
            ),
                'enableNote'                  => array(
                'title'       => 'Enable/Disable adding a note to orders' . $new . $pro,
                'label'       => 'Check to enable sending note / Uncheck to disable sending note' . $edit_with_pro,
                'type'        => 'checkbox',
                'description' => 'A note will be added to your order and an email about that note will be sent to your email',
                'default'     => 'yes',
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'order_note'                  => array(
                'title'       => 'Admin Order Note' . $new . $pro,
                'type'        => 'textarea',
                'description' => "This is a note added to the order email. You may use available shortcodes as needed like in this default order note below: {$edit_with_pro}<br>{$default_order_note}",
                'default'     => $default_order_note,
                'css'         => 'width:80%; pointer-events: none;',
                'class'       => 'disabled',
            ),
                'enable_debug'                => array(
                'title'       => 'Enable Debug' . $pro,
                'label'       => 'Check to Enable / Uncheck to Disable' . $edit_with_pro,
                'type'        => 'checkbox',
                'description' => 'This will enable debug mode to help you troubleshoot issues. <a href="' . admin_url( 'admin.php?page=wc-status&tab=logs' ) . '" target="_blank">Access Logs here</a>',
                'default'     => 'no',
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'toggleSupport'               => array(
                'title'       => 'Enable Support message' . $pro,
                'label'       => 'Check to Enable / Uncheck to Disable' . $edit_with_pro,
                'type'        => 'checkbox',
                'description' => 'Help your customers checkout with ease by letting them know how to contact you',
                'default'     => 'yes',
                'css'         => 'pointer-events: none;',
                'class'       => 'disabled',
            ),
                'toggleTutorial'              => array(
                'title'       => 'Enable Tutorial to display 1min video link',
                'label'       => 'Check to Enable / Uncheck to Disable',
                'type'        => 'checkbox',
                'description' => 'Help your customers checkout with ease by showing this tutorial link',
                'default'     => 'no',
            ),
                'toggleCredits'               => array(
                'title'       => 'Enable Credits to display Powered by The African Boss',
                'label'       => 'Check to Enable / Uncheck to Disable',
                'type'        => 'checkbox',
                'description' => 'Help us spread the word about this plugin by sharing that we made this plugin',
                'default'     => 'no',
            ),
            );
            // Gateways can support subscriptions, refunds, saved payment methods
            $this->supports = array( 'products' );
            // This action hook saves the settings
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'wc_cashapp_payment_scripts' ) );
            // Thank you page
            add_action( "woocommerce_thankyou_{$this->id}", array( $this, 'wc_cashapp_thankyou_page' ) );
            add_action(
                'woocommerce_checkout_order_processed',
                array( $this, 'wc_cashapp_processed' ),
                10,
                3
            );
            // Customer Emails
            add_action(
                'woocommerce_email_order_details',
                array( $this, 'wc_cashapp_email_instructions' ),
                10,
                3
            );
        }
        
        public function wc_cashapp_payment_url( $amount, $note = '' )
        {
            if ( !isset( $this->ReceiverCashApp ) || empty($this->ReceiverCashApp) ) {
                return '';
            }
            $payment_url = 'https://cash.app/' . $this->ReceiverCashApp;
            if ( floatval( $amount ) > 0 ) {
                $payment_url .= "/{$amount}";
            }
            // if ($note) $payment_url .= '?note=' . $note;
            return esc_attr( $payment_url );
        }
        
        public function wc_cashapp_qrcode_url( $amount, $note = '' )
        {
            $payment_url = $this->wc_cashapp_payment_url( $amount, $note );
            if ( empty($payment_url) ) {
                return '';
            }
            $qr_code_url = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $payment_url );
            return esc_attr( $qr_code_url );
        }
        
        public function wc_cashapp_qrcode_html( $amount, $note = '' )
        {
            $payment_url = $this->wc_cashapp_payment_url( $amount, $note );
            $qr_code_url = $this->wc_cashapp_qrcode_url( $amount, $note );
            if ( empty($qr_code_url) || empty($payment_url) ) {
                return '';
            }
            $qrcode_html = '<p class="wc-cashapp">' . esc_html__( 'Click', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ' >
				<a href="' . $payment_url . '" target="_blank"><img width="150" height="150" class="logo-qr" alt="Cash App Link" src="' . esc_attr( WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cashapp.png' ) . '"></a> ' . esc_html__( 'or Scan', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ' > <a href="' . $payment_url . '" target="_blank"><img width="150" height="150" class="logo-qr" alt="Cash App Link" src="' . $qr_code_url . '"></a></p>';
            return wp_kses_post( $qrcode_html );
        }
        
        // wc_add_notice & log
        // protected function wcc_woo_notice( $message, $status = 'error', $level = 'info' ) {}
        /**
         * Logging method.
         *
         * @param string $message Log message.
         * @param string $level Optional. Default 'info'
         * Possible values: emergency|alert|critical|error|warning|notice|info|debug.
         */
        protected function wcc_log( $message, $level = 'info' )
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
                // // $context may hold arbitrary data.
                // // If you provide a "source", it will be used to group your logs
                $logger->log( $level, wp_strip_all_tags( wp_kses_post( $message ) ), array(
                    'source' => $this->id,
                ) );
            }
        
        }
        
        // Checkout page
        public function payment_fields()
        {
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/pages/checkout.php';
        }
        
        // Payment Custom JS and CSS
        public function wc_cashapp_payment_scripts()
        {
            if ( 'no' === $this->enabled || empty($this->ReceiverCashApp) ) {
                return;
            }
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/functions/payment_scripts.php';
        }
        
        // Thank you page
        public function wc_cashapp_thankyou_page( $order_id )
        {
            if ( !$order_id ) {
                return;
            }
            $order = wc_get_order( $order_id );
            if ( $order && $this->id === $order->get_payment_method() ) {
                require_once WCCASHAPP_PLUGIN_DIR . 'includes/pages/thankyou.php';
            }
        }
        
        public function wc_cashapp_processed( $order_id, $posted_data, $order )
        {
            if ( !$order_id || !$order ) {
                return;
            }
            if ( $this->id === $order->get_payment_method() ) {
                require_once WCCASHAPP_PLUGIN_DIR . 'includes/functions/order_processed.php';
            }
        }
        
        // Add content to the WC emails
        public function wc_cashapp_email_instructions( $order, $sent_to_admin, $plain_text = false )
        {
            
            if ( !$sent_to_admin && 'on-hold' === $order->get_status() && $this->id === $order->get_payment_method() ) {
                $order_id = ( method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id );
                require_once WCCASHAPP_PLUGIN_DIR . 'includes/notifications/email.php';
            }
        
        }
        
        // validate cashtag
        public function validate_fields()
        {
            
            if ( isset( $_POST['cashtag'] ) ) {
                $customer_cashtag = sanitize_text_field( trim( $_POST['cashtag'] ) );
                
                if ( !$customer_cashtag || strlen( $customer_cashtag ) < 3 ) {
                    wc_add_notice( esc_html( __( "Your cashtag {$customer_cashtag} is invalid", WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ), 'error' );
                    $this->wcc_log( "Checkout: A customer cashtag {$customer_cashtag} is invalid", 'error' );
                }
            
            }
            
            
            if ( isset( $_POST['do_not_checkout'] ) ) {
                wc_add_notice( esc_html( __( 'Please try another payment method', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) ), 'error' );
                $this->wcc_log( "Checkout: A customer tried {$this->method_title} while it is not yet fully set up by the admin and was advised to try another payment method", 'error' );
            }
        
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
                
                if ( !$order ) {
                    wc_add_notice( '<p>Something went terribly wrong.</p><p>Order information is missing</p>', 'error' );
                    $this->wcc_log( "Checkout: Order information is missing for order id {$order_id}", 'error' );
                    return;
                }
                
                
                if ( !is_wp_error( $order ) && $this->id === $order->get_payment_method() ) {
                    
                    if ( isset( $_POST['cashtag'] ) ) {
                        $customer_cashtag = sanitize_text_field( trim( $_POST['cashtag'] ) );
                        
                        if ( $customer_cashtag ) {
                            // update_post_meta($order_id, 'customer_cashtag', $customer_cashtag);
                            $order->update_meta_data( 'customer_cashtag', $customer_cashtag );
                            $order->save();
                        }
                    
                    }
                    
                    global  $cashapp_fs ;
                    
                    if ( cashapp_fs()->is_plan__premium_only( 'pro' ) && $this->CashAppStockManagement == 'yes' && $this->CashAppForwardingEmail ) {
                    } else {
                        // reduce inventory
                        $order->reduce_order_stock();
                    }
                    
                    // Mark as on-hold (we're awaiting the payment).
                    
                    if ( cashapp_fs()->is_plan__premium_only( 'pro' ) && $this->processOrder == 'yes' ) {
                        $order->reduce_order_stock();
                        $order->payment_complete();
                    } else {
                        $order->update_status( apply_filters( 'woocommerce_cashapp_process_payment_order_status', 'on-hold', $order ), __( 'Checking for payment', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) );
                    }
                    
                    if ( 'yes' == $this->enableNote ) {
                        require_once WCCASHAPP_PLUGIN_DIR . 'includes/notifications/note.php';
                    }
                    global  $woocommerce ;
                    $woocommerce->cart->empty_cart();
                    // Redirect to the thank you page
                    return array(
                        'result'   => 'success',
                        'redirect' => $this->get_return_url( $order ),
                    );
                } else {
                    $error_message = ( is_wp_error( $order ) ? $order->get_error_message() : null );
                    wc_add_notice( "Something went wrong {$error_message}. Try again", 'error' );
                    $this->wcc_log( "Checkout: WP_Error Something went wrong {$error_message}", 'error' );
                    return;
                }
            
            } catch ( \Throwable $th ) {
                // print_r($th);
                wc_add_notice( " " . $th, 'error' );
                $this->wcc_log( "Checkout error due to " . json_encode( $th ), 'error' );
                return;
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
