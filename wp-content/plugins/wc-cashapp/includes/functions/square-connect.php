<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

global $current_user;
$first_name = '';
$last_name = '';
$phone = '';
if ($current_user && is_php_version_compatible('7.0')) {
    $first_name = $current_user->user_firstname ?? get_user_meta( get_current_user_id(), 'first_name', true ) ?? '';
    $last_name = $current_user->user_lastname ?? get_user_meta( get_current_user_id(), 'last_name', true ) ?? '';
    $phone = get_user_meta( get_current_user_id(), 'billing_phone', true ) ?? '';
} else if ($current_user) {
    $first_name = $current_user->user_firstname ? $current_user->user_firstname : get_user_meta( get_current_user_id(), 'first_name', true );
    $last_name = $current_user->user_lastname ? $current_user->user_lastname : get_user_meta( get_current_user_id(), 'last_name', true );
    $phone = get_user_meta( get_current_user_id(), 'billing_phone', true );
}

$sn = urlencode(get_bloginfo("name"));
$su = urlencode(get_site_url());
$fn = urlencode($first_name);
$ln = urlencode($last_name);
$ph = urlencode($phone);
$em = urlencode(get_bloginfo("admin_email"));
$th = urlencode(get_site_icon_url());
$_wpnonce = wp_create_nonce( 'wc_cash_app_pay_connect' );
// $_wpnonce = urlencode($nonce);
$ref = WCCASHAPP_PLUGIN_SLUG;

// http://localhost:10004/wordpress/wp-admin/?action=wc_cash_app_pay_connect&_wpnonce=$_wpnonce
$redirect = urlencode(admin_url("admin-post.php?action=wc_cash_app_pay_connect&_wpnonce=$_wpnonce"));

// // $square = "http://localhost:8000/connect?name=$name&domain=$domain&fname=$fname&lname=$lname&email=$email&phone=$phone&extension=$extension&key=$key&thumbnailUrl=$thumbnailUrl";
// $square = ' <a href="https://square.theafricanboss.com/access.php?sn=' . urlencode(get_bloginfo("name")) . '&su=' . urlencode(get_site_url()) . '&fn=' . urlencode($first_name) . '&ln=' . urlencode($last_name) . '&em=' . urlencode(get_bloginfo("admin_email")) . '&ph=' . urlencode($phone) . '&th=' . urlencode(get_site_icon_url()) . '&_wpnonce=' . urlencode(wp_create_nonce( 'wc_cash_app_pay_connect' )) . '&ref=' . WCCASHAPP_PLUGIN_SLUG . '" target="_blank">Get it here</a>';
$uniq = uniqid();
$square_connect_url = "https://square.theafricanboss.com/access.php?nonce=$uniq&_wpnonce=$_wpnonce&redirect=$redirect&sn=$sn&su=$su&fn=$fn&ln=$ln&em=$em&ph=$ph&th=$th&ref=$ref&v=2";

?>