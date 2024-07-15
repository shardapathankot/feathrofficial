<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

$action = 'wc_cash_app_pay_connect';
$nonce = urldecode($_REQUEST['_wpnonce']) ?? urldecode($_GET['_wpnonce']);

$oauth = isset($_REQUEST['oauth']) ? urldecode($_REQUEST['oauth']) : (isset($_GET['oauth']) ? urldecode($_GET['oauth']) : null);
parse_str($oauth, $parsed);

if ( ! isset( $nonce ) || wp_verify_nonce( $nonce, $action ) === false ) {
    wp_die( "Invalid nonce. $nonce<br>" .
    var_export( $parsed, true ) .
      "<p>Unable to get Square Tokens for Cash App Pay</p>");
}

$html = '<div class="wrap">';

$refresh_token = isset($_POST['refresh_token']) ? $_POST['refresh_token'] : ( isset($parsed['refresh_token']) ? $parsed['refresh_token'] : null);
$access_token = isset($_POST['access_token']) ? $_POST['access_token'] : ( isset($parsed['access_token']) ? $parsed['access_token'] : null);
$merchant_id = isset($_POST['merchant_id']) ? $_POST['merchant_id'] : ( isset($parsed['merchant_id']) ? $parsed['merchant_id'] : null);

// $referer = admin_url('admin.php?page=wc_cashapp_square');
$referer = admin_url('admin.php?page=wc-settings&tab=checkout&section=cash-app-pay');
if ( $refresh_token && $access_token && $merchant_id ) {
    $SQ_Refresh_Token = $this->update_option( 'SQ_Refresh_Token', $refresh_token );
    $SQ_Access_Token = $this->update_option( 'SQ_Access_Token', $access_token );
    $SQ_Merchant_Id = $this->update_option( 'SQ_Merchant_Id', $merchant_id );

    if ( $SQ_Access_Token && $SQ_Refresh_Token && $SQ_Merchant_Id ) {
        $html .= "<h1>Square Access Connect</h1>" .
        "<p>Square Refresh token updated successfully to *******" . substr($SQ_Refresh_Token, -8) . "</p>" .
        "<p>Square Access token updated successfully to *******" . substr($SQ_Access_Token, -8) . "</p>" .
        "<p>Square Merchant Id updated successfully to *******" . substr($SQ_Merchant_Id, -8) . "</p>";

      if ( wp_next_scheduled( 'wc_cashapp_square_renewal_token_cron_hook' ) === false ) {
          wp_schedule_event( time(), 'weekly', 'wc_cashapp_square_renewal_token_cron_hook' );
      }
    } else {
      $html .= '<h1>Error updating Square tokens</h1><br>' .
        // var_export( $_POST, true ) . '<br>' . var_export( $parsed, true ) .
      "<p>Unable to update Square Tokens for Cash App Pay</p>";
      $html .= '<p style="margin-top: 50px;">
      <a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
      href="' . $referer . '">Go Back and try again</a></p>';
      $html .= '</div>';
      echo( $html );
      //   wp_die( $html );
    }

    wp_safe_redirect( $referer ); exit;
} else {
    $html .= '<h1>Error getting Square tokens</h1><br>' .
    // var_export( $_POST, true ) . '<br>' . var_export( $parsed, true ) .
    "<p>Unable to update Square Tokens for Cash App Pay</p>";
}

$html .= '<p style="margin-top: 50px;">
<a style="padding: 1rem; border: none; background-color: black; color: white; text-decoration: none;"
href="' . $referer . '">Go Back and try again</a></p>';
$html .= '</div>';
wp_die( $html );
exit;

?>