<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists( 'WC_Cashapp_Square' ) && class_exists( 'WC_Cash_App_Pay_Gateway' ) ):
class WC_Cashapp_Square extends WC_Cash_App_Pay_Gateway {

  function register() {
    add_action( 'admin_post_save_live_square_env', array( $this, 'wc_cashapp_save_live_square_env' ) );
    add_action( 'admin_post_revoke_square_token', array( $this, 'wc_cashapp_revoke_square_token' ) );
    add_action( 'admin_post_refresh_square_token', array( $this, 'wc_cashapp_refresh_square_token' ) );
    add_action( 'wc_cashapp_square_renewal_token_cron_hook', array( $this, 'wc_cashapp_renew_square_token_cron' ) );
    // add_action( 'admin_post_save_test_square_env', array( $this, 'wc_cashapp_save_test_square_env' ) );

    if ( 'no' === $this->enabled && wp_next_scheduled( 'wc_cashapp_square_renewal_token_cron_hook' ) !== false ) {
        wp_clear_scheduled_hook( 'wc_cashapp_square_renewal_token_cron_hook' );
    }
    if ( 'yes' === $this->enabled && wp_next_scheduled( 'wc_cashapp_square_renewal_token_cron_hook' ) === false ) {
      wp_schedule_event( time(), 'weekly', 'wc_cashapp_square_renewal_token_cron_hook' );
    }

  }

  function wc_cashapp_save_live_square_env() {
      $merchant_id = esc_html( $_POST['merchant_id'] );
      $location_id = esc_html( $_POST['location_id'] );
      $access_token = esc_html( $_POST['access_token'] );
      $refresh_token = esc_html( $_POST['refresh_token'] );

      $referer = urldecode( $_POST['_wp_http_referer'] );
      $html = '<div class="wrap"><div style="padding: 10rem">' ;

      if ( !wp_verify_nonce( $_POST['save_live_square_env_nonce'], 'save_live_square_env' ) ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Invalid nonce</h1>' . var_export( $_POST, true ) );
      }
      if ( !$referer ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Missing target</h1>' . var_export( $_POST, true ) );
      }

      $this->update_option( 'SQ_Merchant_Id', $merchant_id );
      $this->update_option( 'SQ_Location_Id', $location_id );
      $this->update_option( 'SQ_Access_Token', $access_token );
      $this->update_option( 'SQ_Refresh_Token', $refresh_token );

      if ( $access_token && $refresh_token ) {
        if ( wp_next_scheduled( 'wc_cashapp_square_renewal_token_cron_hook' ) === false ) {
            wp_schedule_event( time(), 'weekly', 'wc_cashapp_square_renewal_token_cron_hook' );
        }
      }

      $html .= '<p style="margin-top: 50px;">
    <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
    href="' . $referer . '">Go Back</a></p><br>';

      $html .= '</div></div>';
      echo $html;
      wp_safe_redirect( $referer );
      exit;
  }

  function wc_cashapp_revoke_square_token() {
      $referer = urldecode( $_POST['_wp_http_referer'] );
      $html = '<div class="wrap"><div style="padding: 10rem">' ;

      if ( !wp_verify_nonce( $_POST['revoke_square_token_nonce'], 'revoke_square_token' ) ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Invalid nonce</h1>' . var_export( $_POST, true ) );
      }
      if ( !$referer ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Missing target</h1>' . var_export( $_POST, true ) );
      }

      $access_token = $this->SQ_Access_Token;
      if ( !$access_token ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Missing access token</h1>' . var_export( $_POST, true ) );
      }

      $data = array( 'access_token' => $access_token, 'origin' => get_bloginfo('url'), 'admin_email' => get_bloginfo('admin_email') );
			$url = $this->wc_cash_app_pay_square_url('revoke', true);
      // $html .= "<p>Sending Data:</p><pre>" . var_export( $data, true ) . "</pre> to $url";

			$revoke_token_response = wp_remote_post( $url, array(
          'method'      => 'POST',
          'timeout'     => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking'    => true,
          'headers'     => array(),
          'body'        => $data,
          'cookies'     => array()
          )
      );

      $html .= '<p style="margin-top: 50px;">
    <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
    href="' . $referer . '">Go Back</a></p><br>';

    $revoke_body = wp_remote_retrieve_body( $revoke_token_response );
    if ( !is_wp_error( $revoke_token_response ) ) {
      $revoke_token_response_body = is_string($revoke_body) ? $revoke_body : json_decode( $revoke_body, true );
      // print_r( $revoke_token_response_body );
      if ( 200 !== wp_remote_retrieve_response_code( $revoke_token_response ) ) {
        $error_message = is_string($revoke_token_response_body) ? var_export($revoke_token_response_body, true) : var_export( $revoke_token_response_body, true );
        $this->wccp_log( $error_message, 'error');
        $html .= '<p>We encountered an error and were unable to remove your access token.</p><br><p>Manage your <a href="https://squareup.com/dashboard/apps/my-applications" target="_blank">Square Apps</a></p><br>Error: ' . $error_message;
        $error_message = var_export( $revoke_body, true );
        $html .= "<p>Full Error Details:</p>\n<pre>$error_message</pre>";
      } else {
        $this->update_option( 'SQ_Access_Token', null );
        $this->update_option( 'SQ_Refresh_Token', null );
        $this->update_option( 'SQ_Merchant_Id', null );
        $this->update_option( 'SQ_Location_Id', null );
        wp_clear_scheduled_hook( 'wc_cashapp_square_renewal_token_cron_hook' );
        $html .= '<pre>' . var_export( $revoke_body, true ) . '</pre>';
        // wp_safe_redirect( $referer ); exit;
      }
    } else if ( is_wp_error( $revoke_token_response ) ) {
      // print_r( $revoke_token_response );
      $error_message = method_exists($revoke_token_response,'get_error_message') ? $revoke_token_response->get_error_message() : var_export( $revoke_body, true );
      $this->wccp_log( $error_message, 'error');
      $html .= "<p>Something went wrong:\n$error_message</p>";
      // $error_message = var_export( $revoke_token_response, true );
      // $html .= "WP Error:\n<pre>$error_message</pre>";
    } else {
      // $revoke_token_response_body = json_decode( $revoke_token_response, true );
      $error_message = var_export( $revoke_body, true );
      $this->wccp_log( $error_message, 'error');
      $html .= "<p>Failed to revoke access token.</p>\n\nError:\n<pre>$error_message</pre>";
      // $error_message = var_export( $revoke_token_response, true );
      // $html .= "<p>Full Error Details:</p>\n$error_message";
      // $this->wc_cashapp_revoke_token_logs("Failed to revoke access token");
    }

    $html .= '</div></div>';
    echo $html;
    exit;
  }

  function wc_cashapp_refresh_square_token() {
      $referer = urldecode( $_POST['_wp_http_referer'] );
      $html = '<div class="wrap"><div style="padding: 10rem">';

      if ( !wp_verify_nonce( $_POST['refresh_square_token_nonce'], 'refresh_square_token' ) ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Invalid nonce</h1>' . var_export( $_POST, true ) );
      }
      if ( !$referer ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Missing target</h1>' . var_export( $_POST, true ) );
      }

      $refresh_token = $this->SQ_Refresh_Token;
      if ( !$refresh_token ) {
          wp_die( '<p style="margin-top: 50px;">
  <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
  href="' . $referer . '">Go Back</a></p><br><br>
  <h1>Missing refresh token</h1>' . var_export( $_POST, true ) );
      }

      $data = array( 'refresh_token' => $refresh_token, 'origin' => get_bloginfo('url'), 'admin_email' => get_bloginfo('admin_email') );
			$url = $this->wc_cash_app_pay_square_url('refresh', true);

			$refresh_token_response = wp_remote_post( $url, array(
          'method'      => 'POST',
          'timeout'     => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking'    => true,
          'headers'     => array(),
          'body'        => $data,
          'cookies'     => array()
          )
      );

      $html .= '<p style="margin-top: 50px;">
    <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
    href="' . $referer . '">Go Back</a></p>';

    $error_message = '';
    $refresh_body = wp_remote_retrieve_body( $refresh_token_response );
    if ( !is_wp_error( $refresh_token_response ) && 200 == wp_remote_retrieve_response_code( $refresh_token_response ) ) {
      $refresh_token_response_body = is_string($refresh_body) ? json_decode( $refresh_body, true ) : $refresh_body;
      // print_r( $refresh_token_response_body );
      if ( isset( $refresh_token_response_body['access_token'] ) ) {
          $SQ_Access_Token = $this->update_option( 'SQ_Access_Token', $refresh_token_response_body['access_token'] );
          if ( $SQ_Access_Token ) {
            $msg = 'Square Access token refreshed and updated successfully to *******' . substr($refresh_token_response_body['access_token'], -8);
            $html .= "<h1>$msg</h1>";
            $to = get_bloginfo('admin_email');
            $headers = array('Content-Type: text/html; charset=UTF-8');

            wp_mail( $to, $msg, $msg, $headers );
            $html .= '<br>' . "Also an email has been sent to $to for the update. The new access token ends in ****". substr($refresh_token_response_body['access_token'], -10) . '<br>';

            if ( wp_next_scheduled( 'wc_cashapp_square_renewal_token_cron_hook' ) === false ) {
                wp_schedule_event( time(), 'weekly', 'wc_cashapp_square_renewal_token_cron_hook' );
            }
          } else {
            $html .= '<h1>Error refreshing access token</h1>' . var_export( $refresh_token_response_body, true );
            $error_message = '<h1>Error refreshing access token</h1>' . var_export( $refresh_token_response_body, true );
          }
      } else {
        $html .= '<h1>Error refreshing access token</h1>' . var_export( $refresh_token_response_body, true );
        $error_message = '<h1>Error refreshing access token</h1>' . var_export( $refresh_token_response_body, true );
      }
    } else if ( is_wp_error( $refresh_token_response ) ) {
      // print_r( $refresh_token_response );
      $error_message = method_exists($refresh_token_response,'get_error_message') ? $refresh_token_response->get_error_message() : var_export( $refresh_body, true );
      $html .= "<p>Something went wrong:\n$error_message</p>";
      // $error_message = var_export( $refresh_token_response, true );
      // $html .= "WP Error:\n<pre>$error_message</pre>";
    } else {
      // $refresh_token_response_body = json_decode( $refresh_token_response, true );
      $error_message = var_export( $refresh_body, true );
      $html .= "<p>Failed to update/refresh access token.</p>\n\nError:\n<pre>$error_message</pre>";
      // $error_message = var_export( $refresh_token_response, true );
      // $html .= "<p>Full Error Details:</p>\n$error_message";
      // $this->wc_cashapp_refresh_token_logs("Failed to update/refresh access token");
    }

    if ( !empty($error_message) ) {
      $this->wccp_log( $error_message, 'error');
    }

    $html .= '</div></div>';
    echo $html;
    exit;
  }

	function wc_cashapp_renew_square_token_cron() {
		$refresh_token = $this->SQ_Refresh_Token;
		if ( !$refresh_token ) {
			// $this->wc_cashapp_refresh_token_logs( 'Missing refresh token' . var_export( $_POST, true ) );
      return;
		}

    $data = array( 'refresh_token' => $refresh_token, 'origin' => get_bloginfo('url'), 'admin_email' => get_bloginfo('admin_email') );
    $url = $this->wc_cash_app_pay_square_url('refresh', true);

    $refresh_token_response = wp_remote_post( $url, array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => $data,
        'cookies'     => array()
        )
    );

    $error_message = '';
    $refresh_body = wp_remote_retrieve_body( $refresh_token_response );
    if ( !is_wp_error( $refresh_token_response ) && 200 == wp_remote_retrieve_response_code( $refresh_token_response ) ) {
      $refresh_token_response_body = is_string($refresh_body) ? json_decode( $refresh_body, true ) : $refresh_body;
      if ( isset( $refresh_token_response_body['access_token'] ) ) {
          $SQ_Access_Token = $this->update_option( 'SQ_Access_Token', $refresh_token_response_body['access_token'] );
          if ( $SQ_Access_Token ) {
            $msg = 'Square Access token refreshed and updated successfully to *******' . substr($refresh_token_response_body['access_token'], -8);
            $to = get_bloginfo('admin_email');
            $headers = array('Content-Type: text/html; charset=UTF-8');

            wp_mail( $to, $msg, $msg, $headers );

            if ( wp_next_scheduled( 'wc_cashapp_square_renewal_token_cron_hook' ) === false ) {
                wp_schedule_event( time(), 'weekly', 'wc_cashapp_square_renewal_token_cron_hook' );
            }
            return;
          } else {
            $error_message = !empty($refresh_body) ? var_export( $refresh_body, true ) : 'Error refreshing access token';
          }
      } else {
        $error_message = !empty($refresh_body) ? var_export( $refresh_body, true ) : 'Failed to update/refresh access token';
      }
    } else if ( is_wp_error( $refresh_token_response ) ) {
      $error_message = method_exists($refresh_token_response,'get_error_message') ? $refresh_token_response->get_error_message() : var_export( $refresh_body, true );
    } else {
      $error_message = !empty($refresh_body) ? var_export( $refresh_body, true ) : 'Unknown error';
    }

    if ( !empty($error_message) ) {
      $this->wccp_log( $error_message, 'error');
      //   $this->wc_cashapp_refresh_token_logs($error_message);
      wp_mail( get_bloginfo('admin_email'),
      'IMPORTANT: Error trying to renew your Square token',
      '<p>An error occured trying to renew your Square access token.</p>
      <p>Please renew it manually in your admin dashboard to keep processing Cash App Pay orders.</p>
      <p>Proceed to your admin dashboard by following <a href="' . admin_url('admin.php?page=wc_cashapp_square') . '">Dashboard > Cash App Pay > Square Tokens</a></p>
      <br><br>' . $error_message,
      array('Content-Type: text/html; charset=UTF-8') );
    }
	}

}

$WC_Cashapp_Square = new WC_Cashapp_Square();
$WC_Cashapp_Square->register();

endif;