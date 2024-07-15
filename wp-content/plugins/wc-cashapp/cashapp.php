<?php

/*
Plugin Name: Checkout with Cash App on WooCommerce
Plugin URI: https://theafricanboss.com/cashapp
Description: The #1 finance app in the App Store now on WordPress. Receive Cash App payments on your website with WooCommerce + Cash App
Author: The African Boss
Author URI: https://theafricanboss.com
Version: 5.3.1
Requires PHP: 5.0
Requires at least: 5.0
Tested up to: 6.3
WC requires at least: 5.0
WC tested up to: 8.0.1
Text Domain: wc-cashapp
Domain Path: languages
Created: 2020
Copyright 2023 theafricanboss.com All rights reserved
*/
// Reach out to The African Boss for website and mobile app development services at theafricanboss@gmail.com
// or at www.TheAfricanBoss.com or download our app at www.TheAfricanBoss.com/app
// If you are using this version, please send us some feedback
// via email at theafricanboss@gmail.com on your thoughts and what you would like improved
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
include_once ABSPATH . 'wp-admin/includes/plugin.php';
$plugin_data = get_plugin_data( __FILE__ );
define( 'WCCASHAPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCCASHAPP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WCCASHAPP_PLUGIN_DIR_URL', plugins_url( '/', __FILE__ ) );
define( 'WCCASHAPP_PLUGIN_SLUG', explode( "/", WCCASHAPP_PLUGIN_BASENAME )[0] );
define( 'WCCASHAPP_PLUGIN_VERSION', WCCASHAPP_PLUGIN_SLUG . '-' . $plugin_data['Version'] );
define( 'WCCASHAPP_PLUGIN_TEXT_DOMAIN', $plugin_data['TextDomain'] );
define( 'WCCASHAPP_UPGRADE_URL', 'https://theafricanboss.com/freemius/wc-cashapp' );

if ( function_exists( 'cashapp_fs' ) ) {
    cashapp_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'cashapp_fs' ) ) {
        // Create a helper function for easy SDK access.
        function cashapp_fs()
        {
            global  $cashapp_fs ;
            
            if ( !isset( $cashapp_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_9194_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_9194_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $cashapp_fs = fs_dynamic_init( array(
                    'id'             => '9194',
                    'slug'           => 'wc-cashapp',
                    'premium_slug'   => 'wc-cashapp-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_a8fbfc29e4da59d166c01047b7dfe',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 3,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'           => 'wc-settings',
                    'override_exact' => true,
                    'first-path'     => 'admin.php?page=wc-settings&tab=checkout&section=cashapp',
                    'support'        => false,
                    'parent'         => array(
                    'slug' => 'wc-settings',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $cashapp_fs;
        }
        
        // Init Freemius.
        cashapp_fs();
        // Signal that SDK was initiated.
        do_action( 'cashapp_fs_loaded' );
        function cashapp_fs_settings_url()
        {
            return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=cashapp' );
        }
        
        cashapp_fs()->add_filter( 'connect_url', 'cashapp_fs_settings_url' );
        cashapp_fs()->add_filter( 'after_skip_url', 'cashapp_fs_settings_url' );
        cashapp_fs()->add_filter( 'after_connect_url', 'cashapp_fs_settings_url' );
        cashapp_fs()->add_filter( 'after_pending_connect_url', 'cashapp_fs_settings_url' );
    }
    
    if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        require_once WCCASHAPP_PLUGIN_DIR . 'includes/notifications/woocommerce.php';
    }
    // translations
    function wc_cashapp_load_textdomain()
    {
        load_plugin_textdomain( WCCASHAPP_PLUGIN_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
    
    add_action( 'plugins_loaded', 'wc_cashapp_load_textdomain' );
    
    if ( is_admin() ) {
        // Plugins Settings Buttons
        add_action( 'plugin_action_links_' . WCCASHAPP_PLUGIN_BASENAME, function ( $links ) {
            $settings_link = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=cashapp' ) . '">Cash App Link</a> | <a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=cash-app-pay' ) . '">Cash App Pay <sup style="color: #39b54a; font-weight: bold;">NEW</sup></a> | <a href="' . admin_url( 'admin.php?page=wc_cashapp_compared' ) . '">Compare Both</a>';
            array_unshift( $links, $settings_link );
            global  $cashapp_fs ;
            $upgrade_url = cashapp_fs()->get_upgrade_url();
            $links['wc_cashapp_pro'] = sprintf( '<a href="' . $upgrade_url . '" style="color: #39b54a; font-weight: bold;">' . esc_html__( 'Upgrade', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '</a>' );
            return $links;
        } );
        add_action( 'admin_enqueue_scripts', function () {
            $currentScreen = get_current_screen();
            // var_dump($currentScreen);
            
            if ( strpos( $currentScreen->id, 'wc_cashapp' ) !== false || strpos( $currentScreen->id, 'wc-cashapp' ) !== false ) {
                wp_register_style( 'wc_cashapp_bootstrap', WCCASHAPP_PLUGIN_DIR_URL . 'assets/css/bootstrap.min.css' );
                wp_enqueue_style( 'wc_cashapp_bootstrap' );
            } else {
                return;
            }
        
        } );
        require_once WCCASHAPP_PLUGIN_DIR . 'includes/admin/dashboard.php';
    }
    
    //This action hook registers our PHP class as a WooCommerce payment gateway
    add_filter( 'woocommerce_payment_gateways', 'cashapp_add_gateway_class' );
    function cashapp_add_gateway_class( $gateways )
    {
        $gateways[] = 'WC_Cashapp_Gateway';
        // your class name is here
        $gateways[] .= 'WC_Cash_App_Pay_Gateway';
        // your class name is here
        return $gateways;
    }
    
    //The class itself, please note that it is inside plugins_loaded action hook
    add_action( 'plugins_loaded', 'cashapp_init_gateway_class' );
    function cashapp_init_gateway_class()
    {
        include_once ABSPATH . 'wp-includes/pluggable.php';
        
        if ( class_exists( 'WC_Payment_Gateway' ) ) {
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/class-wc_cashapp_gateway.php';
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/class-wc_cashapp_update_order.php';
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/class-wc_cash_app_pay_gateway.php';
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/class-wc_cashapp_square.php';
        } else {
            require_once WCCASHAPP_PLUGIN_DIR . 'includes/notifications/woocommerce.php';
        }
    
    }

}
