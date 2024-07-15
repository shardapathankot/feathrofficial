<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( is_checkout() ) {
    // Load CSS
    wp_register_style( 'wc_cashapp_checkout', WCCASHAPP_PLUGIN_DIR_URL . 'assets/css/checkout.css' );
    wp_enqueue_style('wc_cashapp_checkout');

    $qrcode_styling = 'qr-code-styling.min.js';
    if (! wp_script_is( $qrcode_styling, 'enqueued' )) {
        wp_register_script( $qrcode_styling, WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/'. $qrcode_styling);
        wp_enqueue_script( $qrcode_styling );
        // wp_enqueue_script( 'wc_cashapp_qrcode_styling', WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/qr-code-styling.min.js' );
    }
    $qrcode_generator = 'qr-code-generator.js';
    if (! wp_script_is( $qrcode_generator, 'enqueued' )) {
        wp_register_script( $qrcode_generator, WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/'. $qrcode_generator);
        wp_enqueue_script( $qrcode_generator );
        // wp_enqueue_script( 'wc_cashapp_qrcode_generator', WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/qr-code-generator.js' );
    }

    wp_enqueue_script( 'wc_cashapp_qrcode', WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/qrcode.js', array( 'jquery', $qrcode_styling, $qrcode_generator ), null, true );

    $copy_js = 'copy.js';
    if (! wp_script_is( $copy_js, 'enqueued' )) {
        wp_register_script( $copy_js, WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/'. $copy_js, array('jquery', 'wc_cashapp_qrcode'), null, true);
        wp_enqueue_script( $copy_js );
        // wp_enqueue_script( 'wc_cashapp_copy', WCCASHAPP_PLUGIN_DIR_URL . 'assets/js/copy.js' );
    }

    global $woocommerce;
    $amount = $woocommerce->cart->total;

    $wc_cashapp_qrcode = array(
        "url" => $this->wc_cashapp_payment_url($amount),
        "width" => $this->QRwidth ?? 150,
        "height" => $this->QRheight ?? 150,
        "darkcolor" => $this->QRdarkcolor ?? '#000000',
        "lightcolor" => $this->QRlightcolor ?? '#ffffff',
        "logo" => WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cashapp.png',
        "dotsType" => $this->QRdotsType ?? 'dots',
        "cornersSquareType" => $this->QRcornersSquareType ?? 'extra-rounded',
        "cornersDotType" => $this->QRcornersDotType ?? 'square',
        "backgroundcolor" => $this-> QRbackgroundcolor ?? '#ffffff',
    );
    wp_localize_script( 'wc_cashapp_qrcode', 'wc_cashapp_qrcode', $wc_cashapp_qrcode );

    // // jquery-dialog on checkout/thankyou with countdown https://jqueryui.com/demos/dialog/
    // wp_enqueue_script( 'jquery-ui-dialog' );

}

// if ( ! is_cart() || ! is_checkout() || ! isset( $_GET['pay_for_order'] ) ) { }

?>