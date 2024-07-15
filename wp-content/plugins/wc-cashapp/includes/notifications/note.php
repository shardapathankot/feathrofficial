<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

// $order = wc_get_order( $order_id );
$amount = $order->get_total();
$currency = $order->get_currency();
// $total = "$amount $currency";
// $total = $order->get_total();
$total = $order->get_formatted_order_total();

$payment_url = $this->wc_cashapp_payment_url($amount, $note);
$qr_code_url = $this->wc_cashapp_qrcode_url($amount, $note);
$qr_code = $this->wc_cashapp_qrcode_html($amount, $note);

$note = $this->order_note ? wp_kses_post( str_replace( array( '**order_id**', '**order_total**', '**shop_name**', '**shop_email**', '**shop_url**' ), array( $order_id, $total, get_bloginfo("name"), get_bloginfo("admin_email"), get_site_url() ), $this->order_note )) : esc_html__( 'Your order was received!', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) .'<br><br>'.
	esc_html__( 'Please send', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ' <a style="color: #00d632" href="' . $payment_url . '" target="_blank">' . wp_kses_post( $total ) . ' ' . esc_html__( 'to', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ' '. esc_attr( wp_kses_post( $this->ReceiverCashApp ) ). '</a> if you haven\'t already.<br><br>'.
	sprintf( __( 'We are checking our Cash App to confirm that we received the %s you sent so we can start processing your order.', WCCASHAPP_PLUGIN_TEXT_DOMAIN ), '<strong>' . wp_kses_post( $total ) . '</strong>'  ) .'<br><br>'.
	esc_html__( 'Thank you for doing business with us', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '!<br> ' . esc_html__( 'You will be updated regarding your order details soon', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . '<br><br>'.
	esc_html__( 'Kindest Regards', WCCASHAPP_PLUGIN_TEXT_DOMAIN ) . ',<br>'.
	wp_kses_post(get_bloginfo("name")). '<br>'.
	wp_kses_post(get_bloginfo("admin_email")). '<br>'.
	wp_kses_post(get_site_url()). '<br>';

// some notes to customer (replace true with false to make it private)
$order->add_order_note( $note, 1 );

?>