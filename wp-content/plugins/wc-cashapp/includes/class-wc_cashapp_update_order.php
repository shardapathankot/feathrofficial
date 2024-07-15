<?php if ( ! defined( "ABSPATH" ) ) { exit; }

if ( !class_exists( "WC_Cashapp_Update_Order" ) && class_exists( "WC_Cashapp_Gateway" ) ):
class WC_Cashapp_Update_Order extends WC_Cashapp_Gateway {

  function register() {
    add_action( "init", array( $this, "wc_cashapp_cpt" ) );
    add_action( "rest_api_init", array( $this, "wc_cashapp_update_order_route" ) );
  }

  function wc_cashapp_cpt() {
    if ( class_exists( "Woocommerce" ) && !post_type_exists( "{$this->id}-receipts" ) ) {
      register_post_type( "{$this->id}-receipts",
        array(
          "labels" => array(
              "name" => __( "{$this->method_title} Receipts" ),
              "singular_name" => __( "{$this->method_title} Receipt" )
          ),
          "public" => false,
          "show_ui" => true,
          "show_in_rest" => false,
          "has_archive" => false,
          "rewrite" => array("slug" => "{$this->id}-receipts"),
          "show_in_rest" => false,
          "menu_icon" => "dashicons-money-alt",
          "menu_position" => 56,
        )
      );
    }
  }

  // Create REST API route
  function wc_cashapp_update_order_route() {
    register_rest_route( "wc-{$this->id}/v1", "/update-{$this->id}-order", array(
      "methods" => "POST",
      "callback" => array( $this, "wc_cashapp_emrcpts_order_update"),
      "permission_callback" => "__return_true",
    ) );
  }

  // Update order
  function wc_cashapp_emrcpts_order_update( $data ) {
    header("Content-type: application/json");

    $message_array = array();

    $body = $data->get_body_params();
    $signature = is_array($data->get_headers()) && !empty($data->get_headers()["x_api_key"]) ? wp_kses_post($data->get_headers()["x_api_key"][0]) : null;

    $cashtag = wp_kses_post($body["transactionaccountid"]);
    $money = wp_kses_post($body["transactionamount"]);
    $currency = wp_kses_post($body["transactioncurrency"]);
    $amount = wp_kses_post($body["transactionamount"]);
    $order_id = wp_kses_post($body["transactionorderid"]);
    $note = wp_kses_post($body["transactionnote"]);
    $receipt_post_id = null;
    $email_subject = !empty($body["emailsubject"]) ? wp_kses_post($body["emailsubject"]) : null;

    $shop = wp_kses_post(get_bloginfo("url"));
    $message = "Response by: $shop\n";
    $message_array["url"] = $shop;
    $message .= "Money: $money\n";
    $message_array["money"] = $money;
    $message .= "Currency: $currency\n";
    $message_array["currency"] = $currency;
    $message .= "Amount: $amount\n";
    $message_array["amount"] = $amount;
    $message .= "Note: $note\n";
    $message_array["note"] = $note;

    $verify = $this->wc_cashapp_emrcpts_verify_signature($signature, true);
    if ( is_array($verify) && $verify["status"] === true ) {
      $amount = wp_kses_post(floatval($amount)); // $amount == $orderamount
      // $order = $this->wc_cashapp_find_cashapp_order($money, $amount, $order_id, $cashtag, $email_subject, $receipt_post_id);

      $find_order = $this->wc_cashapp_find_cashapp_order($money, $amount, $order_id, $cashtag, $email_subject, $receipt_post_id);
      $receipt_post_id = $find_order["receipt_post_id"];
      $order = $find_order["order"];
      // $cashtag = empty($cashtag) && !empty($order) && $order->meta_exists("customer_cashtag") ? $order->get_meta("customer_cashtag") : $cashtag;
      $cashtag = empty($cashtag) && !empty($order) && $order->meta_exists("customer_cashtag") ? $order->get_meta("customer_cashtag") : $cashtag;
      $message .= "Account ID: $cashtag\n";
      $message_array["accountid"] = $cashtag;
      // $order_id = !empty($order) ? $order->get_id() : $order_id;
      $order_id = !empty($order) ? $order->get_id() : $order_id;
      $message .= "Order ID: $order_id\n";
      $message_array["orderid"] = $order_id;
      $message .= $find_order["post_content"];
      $message_array["find_order"] = $find_order["post_content"];
      // $this->wcc_log( "cashapp_emrcpts_order_update: " . $find_order["post_content"] );
      require_once WCCASHAPP_PLUGIN_DIR . "includes/admin/update-order.php";
    } else {
      $message .= is_array($verify) ? "Invalid Signature: " . $verify['message'] . "\n" : "Invalid Request Signature was not verified.\n";
      $message_array['signature'] = is_array($verify) ? $verify['message'] : "Invalid Request Signature was not verified.";
      // $this->wcc_log( "cashapp_emrcpts_order_update: " . $message_array['signature'] );
      http_response_code(401);
    }
    $message .= "Status: " . http_response_code();
    // $message_array['status'] = http_response_code();

    // echo $message;
    $this->wcc_log($message);
    // return $message;

    $emrcpts_response = array(
      'status' => http_response_code(),
      'message' => wp_kses_post($message),
      'data' => $message_array,
    );
    // echo json_encode($emrcpts_response);
    return $emrcpts_response;
  }
  // function OLD_wc_cashapp_emrcpts_order_update( $data ) {
  //   // header("Content-type: application/json");
  //   header("Content-type: text/plain");

  //   $body = $data->get_body_params();
  //   // print_r($body);

  //   $cashtag = wp_kses_post($body["transactionaccountid"]);
  //   $money = wp_kses_post($body["transactionamount"]);
  //   $currency = wp_kses_post($body["transactioncurrency"]);
  //   $amount = wp_kses_post($body["transactionamount"]);
  //   $order_id = wp_kses_post($body["transactionorderid"]);
  //   $note = wp_kses_post($body["transactionnote"]);
  //   $receipt_post_id = null;

  //   $shop = get_bloginfo("url");
  //   echo "Response by: $shop\n";

  //   echo "Money: $money\n";
  //   echo "Currency: $currency\n";
  //   echo "Amount: $amount\n";
  //   echo "Note: $note\n";

  //   if ( $_SERVER["HTTP_HOST"] !== "emailreceipts.io" ) {
  //     $email_subject = null;
  //     $shop = wp_kses_post(get_bloginfo("url"));
  //     $amount = wp_kses_post(floatval($amount)); // $amount == $orderamount
  //     $order = $this->wc_cashapp_find_cashapp_order($money, $amount, $order_id, $cashtag, $email_subject, $receipt_post_id);
  //     $cashtag = empty($cashtag) && !empty($order) && $order->meta_exists("customer_cashtag") ? $order->get_meta("customer_cashtag") : $cashtag;
  //     echo "Account ID: $cashtag\n";
  //     $order_id = !empty($order) ? $order->get_id() : $order_id;
  //     echo "Order ID: $order_id\n";
  //     require_once WCCASHAPP_PLUGIN_DIR . "includes/admin/update-order.php";
  //   } else {
  //     http_response_code(422);
  //   }
  //   echo "Status: " . http_response_code();
  // }

  // Verify signature hash
  function wc_cashapp_emrcpts_verify_signature( $key, $isJSON = false ) {
    $verified = false;

    if ( empty($key) ) {
      $message = "No signature provided.";
      if ( $isJSON ) {
        $response = array(
          "status" => $verified,
          "message" => $message,
        );
        return $response;
      } else {
        return $verified;
      }
    }

    $response = wp_remote_post( "https://emailreceipts.io/keys/verify", array(
      "method" => "POST",
      "headers" => array(
        "Content-Type" => "application/json; charset=utf-8",
      ),
      "body" => json_encode(array(
        "domain" => wp_kses_post($this->CashAppForwardingURL),
        "key" => wp_kses_post($key),
      )),
    ) );
    // print_r($response);

    if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      $message = "Something went wrong: $error_message";
    } else {
      $respose_body = wp_remote_retrieve_body($response);
      $body = json_decode($respose_body, true);
      if (json_last_error() === JSON_ERROR_NONE) {
        $verified = !empty($body) ? $body["status"] : false;
        $message = !empty($body) ? $body["message"] : "";
      } else {
        $message = "Invalid response from emailreceipts.io";
      }
    }

    if ( $isJSON ) {
      $response = array(
        "status" => $verified,
        "message" => wp_kses_post($message),
      );
      return $response;
    } else {
      return $verified;
    }
  }
  // function wc_cashapp_find_cashapp_order( $money, $amount, $order_id = null, $cashtag = null, $email_subject = null, $receipt_post_id = null ) {
  function wc_cashapp_find_cashapp_order( $money, $amount, $order_id = null, $cashtag = null, $email_subject = null, $receipt_post_id = null ) {
    $order = array();
    $post_title = null;
    $post_content = null;
    if (!empty($order_id)) {
      $order = wc_get_order( $order_id );
      $order_id = !empty($order) ? $order->get_id() : $order_id;
      $cashtag = empty($cashtag) && $order ? $order->get_meta("customer_cashtag") : $cashtag;
      $post_title = "Receipt: $money from $cashtag for $order_id";
      $post_content .= "$money from $cashtag for $order_id.";
    }

    if (empty($order)) {
      // "orderby" => "date", "orderby" => "<" . ( time() - 3600 ), 'date_created' => '>' . ( time() - 3600 ), date_created' => '>' . ( time() - DAY_IN_SECONDS ),// ordered before the last hour
      $orders = wc_get_orders( ["limit" => 5, "payment_method" => $this->id, 'date_created' => '>' . ( time() - 3600 ), "status" => array("wc-on-hold")] );
      // print_r($orders);
      $ordercountmsg = count($orders) . " recent order(s) match(es) your criteria: payment_method: {$this->id}, ordered in the last hour, status: on-hold\n";
      $post_content .= $ordercountmsg;
      if (count($orders) > 0) {
        $found_order = false;
        $orderind = 0;
        while ($orderind < count($orders) && $found_order == false) {
          $order = $orders[$orderind];
          $orderid = wp_kses_post($order->get_id());
          $orderamount = wp_kses_post(floatval($order->get_total()));
          $customer_cashtag = wp_kses_post($order->get_meta("customer_cashtag"));
          $post_content .= "Recent order $orderid: $orderamount vs provided: $amount from $customer_cashtag.\n";
          if ( $amount == $orderamount || (!empty($cashtag) && $customer_cashtag == $cashtag) ) {
            $post_title = "Receipt: $money from $cashtag for $order_id (extracted from recent {$this->method_title} order)";
            $post_content .= "$money from $cashtag for $order_id.";
            $order_id = !empty($order) ? $orderid : $order_id;
            $cashtag = empty($cashtag) ? $customer_cashtag : $cashtag;
            $found_order = true;
            $post_content .= "Recent {$this->method_title} order $order_id with cashtag: $cashtag matched amount $amount == $orderamount\n";
          } else {
            $order = array();
            // $order_id = null;
          }
          $orderind++;
        }
      } else {
          $post_title = "Receipt: No valid orders matched the amount: $amount";
          $post_content .= "Since the order information was invalid, we tried looking for the most recent order to see if it was a match.<br>" . $ordercountmsg;
      }
    }

    if ($post_title && $post_content && post_type_exists( "{$this->id}-receipts" ) ) {
        $cashapp_receipt = array(
            "post_title" => $post_title,
            "post_content" => "$post_content.<br><br>$email_subject",
            "post_type" => "{$this->id}-receipts",
            "post_status" => "private",
        );
        $receipt_post_id = wp_insert_post( $cashapp_receipt );
        if ($receipt_post_id) {
            $post_content .= "{$this->method_title} Receipt ID: $receipt_post_id created successfully\n";
            http_response_code(201);
        } else {
            $post_content .= "{$this->method_title} Receipt creation failed\n";
            http_response_code(500);
        }
    }

    // echo $post_content;

    return array(
      "order" => $order,
      "post_content" => $post_content,
      "receipt_post_id" => $receipt_post_id,
    );
  }
  // function OLD_wc_cashapp_find_cashapp_order( $money, $amount, $order_id = null, $cashtag = null, $email_subject = null, $receipt_post_id = null ) {
  //   $order = array();
  //   $post_title = null;
  //   $post_content = null;
  //   if (!empty($order_id)) {
  //     $order = wc_get_order( $order_id );
  //     $order_id = !empty($order) ? $order->get_id() : $order_id;
  //     $cashtag = empty($cashtag) && $order ? $order->get_meta("customer_cashtag") : $cashtag;
  //     $post_title = "Receipt: $money from $cashtag for $order_id";
  //     $post_content .= "$money from $cashtag for $order_id.";
  //   }

  //   if (empty($order)) {
  //     // "orderby" => "<" . ( time() - 3600 ), // ordered before the last hour
  //     $orders = wc_get_orders( ["limit" => 5, "payment_method" => "cashapp", "orderby" => time() - 3600, "status" => array("wc-on-hold")] );
  //     // print_r($orders);
  //     $ordercountmsg = count($orders) . " recent order(s) match(es) your criteria: payment_method: cashapp, ordered in the last hour, status: on-hold\n";
  //     $post_content .= $ordercountmsg;
  //     echo $ordercountmsg;
  //     if (count($orders) > 0) {
  //       $found_order = false;
  //       $orderind = 0;
  //       while ($orderind < count($orders) && $found_order == false) {
  //         $order = $orders[$orderind];
  //         $orderid = wp_kses_post($order->get_id());
  //         $orderamount = wp_kses_post(floatval($order->get_total()));
  //         $customer_cashtag = wp_kses_post($order->get_meta("customer_cashtag"));
  //         $post_content .= "Recent order $orderid: $orderamount vs provided: $amount from $customer_cashtag\n";
  //         echo "Recent order $orderid: $orderamount vs provided: $amount from $customer_cashtag\n";
  //         if ( $amount == $orderamount || $customer_cashtag == $cashtag ) {
  //           $post_title = "Receipt: $money from $cashtag for $order_id (extracted from recent {$this->method_title} order)";
  //           $post_content .= "$money from $cashtag for $order_id.";
  //           $order_id = !empty($order) ? $orderid : $order_id;
  //           $cashtag = empty($cashtag) ? $customer_cashtag : $cashtag;
  //           $found_order = true;
  //           echo "Recent {$this->method_title} order $order_id with cashtag: $cashtag matched amount $amount == $orderamount\n";
  //           $post_content .= "Recent {$this->method_title} order $order_id with cashtag: $cashtag matched amount $amount == $orderamount\n";
  //         } else {
  //           $order = array();
  //         }
  //         $orderind++;
  //       }

  //       // $order = $orders[0];
  //       // $orderamount = wp_kses_post(floatval($order->get_total()));
  //       // echo "Recent order: $orderamount vs provided: $amount\n";
  //       // if ($order->get_payment_method() === "cashapp" && $amount == $orderamount) {
  //       //   $post_title = "Receipt: $money from $cashtag for $order_id (extracted from recent {$this->method_title} order)";
  //       //   $post_content .= "$money from $cashtag for $order_id.";
  //       //   $order_id = !empty($order) ? $order->get_id() : $order_id;
  //       //   $cashtag = empty($cashtag) ? $order->get_meta("customer_cashtag") : $cashtag;
  //       //   echo "Recent {$this->method_title} order $order_id with cashtag: $cashtag matched amount $amount == $orderamount\n";
  //       // } else {
  //       //     $post_title = "Receipt: Invalid {$order->get_payment_method_title()} order";
  //       //     $post_content .= "Since the order information was invalid, we tried looking for the most recent order to see if it was a match.<br>Invalid recent order {$order->get_id()} did not match amount or payment method.<br>{$order->get_payment_method_title()} Order of amount $orderamount";
  //       //     echo "Invalid {$order->get_payment_method_title()} recent order {$order->get_id()} of amount $orderamount != $amount \n";
  //       // }
  //     } else {
  //         $post_title = "Receipt: No valid orders matched the amount: $amount";
  //         $post_content .= "Since the order information was invalid, we tried looking for the most recent order to see if it was a match.<br>" . $ordercountmsg;
  //     }
  //   }

  //   if ($post_title && $post_content && post_type_exists( "{$this->id}-receipts" ) ) {
  //     $cashapp_receipt = array(
  //         "post_title" => $post_title,
  //         "post_content" => "$post_content.<br><br>$email_subject",
  //         "post_type" => "{$this->id}-receipts",
  //         "post_status" => "private",
  //     );
  //     $receipt_post_id = wp_insert_post( $cashapp_receipt );
  //     if ($receipt_post_id) {
  //         echo "{$this->method_title} Receipt ID: $receipt_post_id created successfully\n";
  //         http_response_code(201);
  //     } else {
  //         echo "{$this->method_title} Receipt creation failed\n";
  //         http_response_code(500);
  //     }
  //   }

  //   return $order;
  // }

}

$WC_Cashapp_Update_Order = new WC_Cashapp_Update_Order();
$WC_Cashapp_Update_Order->register();

endif;