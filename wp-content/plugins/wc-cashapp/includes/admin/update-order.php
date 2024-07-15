<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$update_order = null;
$post_title = null;
$post_content = null;
$response_code = null;
global  $cashapp_fs ;
$update_order .= "UPGRADE TO UNLOCK AUTOMATED ORDER PROCESSING\n";
$post_title .= wp_kses_post( " - UPGRADE TO AUTOMATE FURTHER" );
$post_content .= wp_kses_post( " - Upgrade to automated further." );
$response_code = 426;
$message .= $update_order;
$message_array['update_order'] = $update_order;

if ( $receipt_post_id ) {
    $post_dump = print_r( $body, true );
    $receipt_post = get_post( $receipt_post_id );
    
    if ( $receipt_post ) {
        $receipt_post->post_title .= wp_kses_post( $post_title );
        $receipt_post->post_content .= wp_kses_post( "<br>{$post_content}<br><br>{$email_subject}<br><br>{$post_dump}" );
        wp_update_post( $receipt_post );
    }

}

http_response_code( $response_code );
// global $cashapp_fs;
// if ( cashapp_fs()->is_plan__premium_only('pro') ) {
//     if ( $amount && $order_id ) {
//         echo "$money from $cashtag for $order_id\n";
//         if ( $order ) {
//             $order_amount = wp_kses_post(floatval($order->get_total()));
//             if ( $amount == $order_amount && $order->get_status() != 'processing' ) {
//                 $update_status = $order->payment_complete();
//                 // $update_status = $order->update_status( 'processing' );
//                 // $isVirtualCount = 0;
//                 // foreach ($order->get_items() as $order_item){
//                 //     $item = wc_get_product($order_item->get_product_id());
//                 //     if ($item->is_virtual()) {
//                 //         // this order contains a virtual product do what you want here or return true
//                 //         $isVirtualCount += 1;
//                 //     }
//                 // }
//                 // if ($isVirtualCount > 0) { $update_status = $order->update_status( 'completed' ); }
//                 if ( $update_status ) {
//                     echo "Order $order_id updated to " . $order->get_status() . "\n";
//                     // if ( $this->CashAppStockManagement == 'yes' ) {
//                     //     $order->reduce_order_stock();
//                     // }
//                     if ( $receipt_post_id ) {
//                         $receipt_post = get_post( $receipt_post_id );
//                         if ( $receipt_post ) {
//                             $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - " . $order->get_status());
//                             $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Order $order_id updated to " . $order->get_status() . "<br><br>$post_dump");
//                             wp_update_post( $receipt_post );
//                         }
//                     }
//                     http_response_code(200);
//                 } else {
//                     echo "Order $order_id could not be processed\n";
//                     if ( $receipt_post_id ) {
//                         $receipt_post = get_post( $receipt_post_id );
//                         if ( $receipt_post ) {
//                             $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - status update error");
//                             $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Order $order_id could not be processed<br><br>$post_dump");
//                             wp_update_post( $receipt_post );
//                         }
//                     }
//                     http_response_code(500);
//                 }
//             } else if ( $amount != $order_amount ) {
//                 echo "Order $order_id amount does not match with $money provided: $amount != $order_amount\n";
//                 if ( $receipt_post_id ) {
//                     $receipt_post = get_post( $receipt_post_id );
//                     if ( $receipt_post ) {
//                         $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - amount error");
//                         $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Order $order_id amount $order_amount does not match with $amount provided<br><br>$post_dump");
//                         wp_update_post( $receipt_post );
//                     }
//                 }
//                 http_response_code(406);
//             } else if ( $order->get_status() != 'on-hold' ) {
//                 echo "Order $order_id is already " . $order->get_status() . "\n";
//                 if ( $receipt_post_id ) {
//                     $receipt_post = get_post( $receipt_post_id );
//                     if ( $receipt_post ) {
//                         $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - " . $order->get_status() . " already");
//                         $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Order $order_id is already " . $order->get_status() . "<br><br>$post_dump");
//                         wp_update_post( $receipt_post );
//                     }
//                 }
//                 http_response_code(406);
//             }
//         } else {
//             echo "Order $order_id not found\n";
//             if ( $receipt_post_id ) {
//                 $receipt_post = get_post( $receipt_post_id );
//                 if ( $receipt_post ) {
//                     $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - order not found");
//                     $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Order $order_id was not found<br><br>$post_dump");
//                     wp_update_post( $receipt_post );
//                 }
//             }
//             http_response_code(404);
//         }
//     } else {
//         echo "Missing important data: amount: $amount or order_id: $order_id\n";
//         if ( $receipt_post_id ) {
//             $receipt_post = get_post( $receipt_post_id );
//             if ( $receipt_post ) {
//                 $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - missing data error");
//                 $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Missing data: amount: $amount or order_id: $order_id.<br><br>$email_subject<br><br>$post_dump");
//                 wp_update_post( $receipt_post );
//             }
//         }
//         http_response_code(422);
//     }
// } else {
//     echo "UPGRADE TO UNLOCK AUTOMATED ORDER PROCESSING\n";
//     if ( $receipt_post_id ) {
//         $receipt_post = get_post( $receipt_post_id );
//         if ( $receipt_post ) {
//             $receipt_post->post_title = wp_kses_post($receipt_post->post_title . " - UPGRADE TO AUTOMATE FURTHER");
//             $receipt_post->post_content = wp_kses_post($receipt_post->post_content . " - Upgrade to automated further.<br><br>$email_subject<br><br>$post_dump");
//             wp_update_post( $receipt_post );
//         }
//     }
//     http_response_code(426);
// }