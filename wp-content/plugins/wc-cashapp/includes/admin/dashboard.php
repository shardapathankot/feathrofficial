<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
function wc_cashapp_admin_menu()
{
    $capability = 'manage_options';
    $contact_url = "mailto:info@theafricanboss.com?subject=WC%20Cashapp%20Plugin%20Support&body=Hello%2C%0D%0A%0D%0A";
    $cashapp_receipts = admin_url( 'edit.php?post_type=cashapp-receipts' );
    global  $cashapp_fs ;
    $account_url = cashapp_fs()->get_account_url();
    $cashapp_parent_slug = 'wc-settings&tab=checkout&section=cashapp';
    $new = ' <sup style="color:#9f9">NEW</sup>';
    $improved = ' <sup style="color: #39b54a; font-weight: bold;">IMPROVED</sup>';
    add_submenu_page(
        'woocommerce',
        'Cash App for Woocommerce',
        'Cash App Link',
        'manage_woocommerce',
        $cashapp_parent_slug,
        null
    );
    add_submenu_page(
        'woocommerce',
        'Setup Automated Order Updates',
        'Automated Cash App Order Updates' . $improved,
        $capability,
        'wc_cashapp_automated_status',
        'wc_cashapp_email_receipts_menu_page',
        null
    );
    $upgrade_url = cashapp_fs()->get_upgrade_url();
    add_menu_page(
        null,
        'Cash App Link',
        $capability,
        $cashapp_parent_slug,
        'wc_cashapp_admin_menu',
        'dashicons-money-alt',
        56
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Setup Automated Order Updates',
        '<span style="color:#aaffaa">Automated Order Updates</span>',
        $capability,
        'wc_cashapp_automated_status',
        'wc_cashapp_email_receipts_menu_page',
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Cash App Receipts',
        'Receipts',
        $capability,
        $cashapp_receipts,
        null,
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Compared',
        'Cash App Link vs Cash App Pay',
        $capability,
        'wc_cashapp_compared',
        'wc_cashapp_compared_menu_page',
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Account',
        'Account',
        $capability,
        $account_url,
        null,
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Feature my store',
        'Get Featured',
        $capability,
        'https://theafricanboss.com/cashapp#feature',
        null,
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Review CASH APP',
        'Review',
        $capability,
        'https://wordpress.org/support/plugin/wc-cashapp/reviews/?filter=5',
        null,
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Recommended',
        'Recommended',
        $capability,
        'wc_cashapp_recommended',
        'wc_cashapp_recommended_menu_page',
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Help',
        'Help',
        $capability,
        'wc_cashapp_help',
        'wc_cashapp_help_menu_page',
        null
    );
    add_submenu_page(
        $cashapp_parent_slug,
        'Upgrade CASH APP',
        '<span style="color:#99FFAA">Go Pro >> </span>',
        $capability,
        $upgrade_url,
        null,
        null
    );
}

add_action( 'admin_menu', 'wc_cashapp_admin_menu' );
function wc_cashapp_email_receipts_menu_page()
{
    require_once WCCASHAPP_PLUGIN_DIR . 'includes/admin/email-receipts.php';
}

function wc_cashapp_recommended_menu_page()
{
    require_once WCCASHAPP_PLUGIN_DIR . 'includes/admin/recommended.php';
}

function wc_cashapp_help_menu_page()
{
    require_once WCCASHAPP_PLUGIN_DIR . 'includes/admin/help.php';
}

function wc_cashapp_compared_menu_page()
{
    require_once WCCASHAPP_PLUGIN_DIR . 'includes/admin/compared.php';
}

function wc_cash_app_pay_admin_menu()
{
    $capability = 'manage_options';
    $contact_url = "mailto:info@theafricanboss.com?subject=WC%20Cashapp%20Plugin%20Support&body=Hello%2C%0D%0A%0D%0A";
    $cashapp_receipts = admin_url( 'edit.php?post_type=cashapp-receipts' );
    global  $cashapp_fs ;
    $account_url = cashapp_fs()->get_account_url();
    $new = ' <sup style="color:#9f9">NEW</sup>';
    $improved = ' <sup style="color: #39b54a; font-weight: bold;">IMPROVED</sup>';
    $square_parent_slug = 'wc-settings&tab=checkout&section=cash-app-pay';
    add_submenu_page(
        'woocommerce',
        'Cash App Pay',
        'Cash App Pay',
        'manage_woocommerce',
        $square_parent_slug,
        null
    );
    $gateway = ( class_exists( 'WC_Cash_App_Pay_Gateway' ) ? new WC_Cash_App_Pay_Gateway() : null );
    $request_tokens = ( class_exists( 'WC_Cash_App_Pay_Gateway' ) ? $gateway->wc_cash_app_pay_square_connect_url() : null );
    // $refresh_tokens = class_exists( 'WC_Cash_App_Pay_Gateway' ) ? $gateway->wc_cash_app_pay_square_url('refresh', true) : null;
    // $revoke_tokens = class_exists( 'WC_Cash_App_Pay_Gateway' ) ? $gateway->wc_cash_app_pay_square_url('revoke', true) : null;
    $SQ_Access_Token = ( class_exists( 'WC_Cash_App_Pay_Gateway' ) ? $gateway->SQ_Access_Token : null );
    add_menu_page(
        null,
        'Cash App Pay',
        $capability,
        $square_parent_slug,
        'wc_cash_app_pay_admin_menu',
        'dashicons-money-alt',
        56
    );
    add_submenu_page(
        $square_parent_slug,
        'Square',
        'Square Tokens',
        $capability,
        'wc_cashapp_square',
        'wc_cashapp_square_menu_page',
        null
    );
    
    if ( class_exists( 'WC_Cash_App_Pay_Gateway' ) && empty($SQ_Access_Token) ) {
        add_submenu_page(
            $square_parent_slug,
            'Square',
            '<span style="color:#aaffaa">Square Connect</span>',
            $capability,
            $request_tokens,
            null,
            null
        );
    } else {
        if ( class_exists( 'WC_Cash_App_Pay_Gateway' ) && !empty($SQ_Access_Token) ) {
            add_submenu_page(
                $square_parent_slug,
                'Square',
                'Refresh/Disconnect',
                $capability,
                'wc_cashapp_square',
                'wc_cashapp_square_menu_page',
                null
            );
        }
    }
    
    add_submenu_page(
        $square_parent_slug,
        'Compared',
        'Cash App Link vs Cash App Pay',
        $capability,
        'wc_cashapp_compared',
        'wc_cashapp_compared_menu_page',
        null
    );
    add_submenu_page(
        $square_parent_slug,
        'Review Cash App Pay',
        'Review',
        $capability,
        'https://wordpress.org/support/plugin/wc-cashapp/reviews/?filter=5',
        null,
        null
    );
    add_submenu_page(
        $square_parent_slug,
        'Account',
        'Account',
        $capability,
        $account_url,
        null,
        null
    );
    add_submenu_page(
        $square_parent_slug,
        'Our Most Recommended Plugins',
        'Recommended Plugins',
        $capability,
        'wc_cashapp_recommended',
        'wc_cashapp_recommended_menu_page',
        null
    );
    add_submenu_page(
        $square_parent_slug,
        'Our Plugins',
        'Free Plugins',
        $capability,
        admin_url( "plugin-install.php?s=theafricanboss&tab=search&type=author" ),
        null,
        null
    );
    add_submenu_page(
        $square_parent_slug,
        'Help',
        'Help',
        $capability,
        'wc_cashapp_help',
        'wc_cashapp_help_menu_page',
        null
    );
    add_submenu_page(
        $square_parent_slug,
        'Contact',
        'Support',
        $capability,
        $contact_url,
        null,
        null
    );
}

add_action( 'admin_menu', 'wc_cash_app_pay_admin_menu' );
function wc_cashapp_square_menu_page()
{
    require_once WCCASHAPP_PLUGIN_DIR . 'includes/admin/square.php';
}
