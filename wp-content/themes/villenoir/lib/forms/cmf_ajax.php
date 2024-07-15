<?php
//Contact form
function villenoir_cmf_ajax() {
    $form_id                 = absint($_POST['form_id']);
    
    if ( $form_id ) {
        $contact_page_email =  get_option( 'email_address_'.$form_id );
    }

    if (isset($success_message)) {
        $contact_page_success = $success_message;
    } else {
        $contact_page_success = esc_html__( 'Your message was sent successfully.' , 'villenoir' );
    }

    if (isset($error_message)) {
        $contact_page_error = $error_message;
    } else {
        $contact_page_error = esc_html__( 'There was an error submitting the form.' , 'villenoir' );
    }

    if ($contact_page_email_from == '') {
        $contact_page_email_from = 'noreply@yoursitename.com';
    }

    $error = '';
    $status = 'error';

    if ( empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message']) ) {
        $error = esc_html__( 'All fields are required to enter.' , 'villenoir' );
    } else {
        if (!wp_verify_nonce($_POST['_cmf_nonce'], 'contact_form_html')) {
            $error = esc_html__( 'Verification error, try again.' , 'villenoir' );
        } else {

            $name           = sanitize_text_field($_POST['name']);
            $email          = sanitize_email($_POST['email']);
            $email_check    = villenoir_check_email($email);
            $messagecmf      = esc_textarea($_POST['message']);


            if ($email_check == 1) {
                
                $subject = sprintf(esc_html__( 'New contact form message from : %1$s' , 'villenoir' ), $name);

                $message = '<html><body>';
                $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
                $message .= "<tr style='background: #eee;'><td><strong>".esc_html__( 'Name', 'villenoir')."</strong> </td><td>" . $name . "</td></tr>";
                $message .= "<tr><td><strong>".esc_html__( 'Email', 'villenoir')."</strong> </td><td>" . $email . "</td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>".esc_html__( 'Message', 'villenoir')."</strong> </td><td>" . $messagecmf . "</td></tr>";
                $message .= "</table>";
                $message .= "</body></html>";

                $to = $contact_page_email;
                if (!isset($to) || ($to == '') ){
                    $to = get_option('admin_email');
                }
                
                $emailfrom = $contact_page_email_from;

                $header = 'From: '.get_option('blogname').' <'.$emailfrom.'>'. "\r\n";
                $header .= 'Reply-To: '.$email. "\r\n";
                $header .= 'Content-Type: text/html; charset=UTF-8'. "\r\n";
                
                if ( wp_mail($to, $subject, $message, $header) ) {
                    $status = 'success';
                    $thankyou = '';
                    $error = ($thankyou != '') ? $thankyou : $contact_page_success;
                } else {
                    $error = $contact_page_error;
                }

            } else {
                $error = $email_check;
            }
        }
    }

    $resp = array('status' => $status, 'errmessage' => $error);
    wp_send_json($resp);
}
add_action( 'wp_ajax_cmf_action', 'villenoir_cmf_ajax' );
add_action( 'wp_ajax_nopriv_cmf_action', 'villenoir_cmf_ajax' );