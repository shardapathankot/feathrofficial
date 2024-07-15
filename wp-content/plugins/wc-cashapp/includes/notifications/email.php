<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// $order = wc_get_order( $order_id );
$amount = $order->get_total();
$currency = $order->get_currency();
// $total = "$amount $currency";
// $total = $order->get_total();
$total = $order->get_formatted_order_total();
$payment_url = $this->wc_cashapp_payment_url( $amount, $note );
$qr_code_url = $this->wc_cashapp_qrcode_url( $amount, $note );
$qr_code = $this->wc_cashapp_qrcode_html( $amount, $note );
echo  '<h2>' . esc_html__( 'Cash App Notice', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '</h2>' ;
echo  '<p>' . esc_html__( 'Send', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ' <a style="color: #00d632" href="' . $payment_url . '" target="_blank">' . wp_kses_post( $total ) . ' ' . esc_html__( 'to', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ' ' . esc_attr( wp_kses_post( $this->ReceiverCashApp ) ) . '</a> ' . esc_html__( 'or click the Cash App button below', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '</p>' ;
echo  '<p><a href="' . $payment_url . '" target="_blank"><img width="150" height="150" alt="Cash App Link" src="' . esc_attr( WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cashapp.png' ) . '"></a></p>' ;
if ( !empty($order_id) ) {
    echo  '<p>' . esc_html__( 'Payment Reference / Order Number', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ': ' . $order_id . '</p>' ;
}