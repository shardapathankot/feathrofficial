<?php
//Contact form
function villenoir_cf_ajax() {
    //Get the post id
    $post_id                 = absint($_POST['post_id']);
    
    $contact_page_email      = _get_field( 'gg_contact_page_email',$post_id);
    $contact_page_email_from = _get_field( 'gg_contact_page_email_from',$post_id);
    $contact_page_success    = _get_field( 'gg_contact_page_success_msg',$post_id);
    $contact_page_error      = _get_field( 'gg_contact_page_error_msg',$post_id);
    $privacy_checkbox        = _get_field( 'gg_contact_page_privacy_checkbox',$post_id);

    if ($contact_page_success == '') {
        $contact_page_success = esc_html__( 'Your message was sent successfully.' , 'villenoir' );
    }

    if ($contact_page_error == '') {
        $contact_page_error = esc_html__( 'There was an error submitting the form.' , 'villenoir' );
    }

    if ($contact_page_email_from == '') {
        $contact_page_email_from = 'noreply@yoursitename.com';
    }

    $error = '';
    $status = 'error';

    if ( empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message']) || (isset($_POST['has_privacy_checkbox']) && empty($_POST['consent'])) ) {
        $error = esc_html__( 'All fields are required to enter.' , 'villenoir' );
    } else {
        if (!wp_verify_nonce($_POST['_cf_nonce'], 'contact_form_html')) {
            $error = esc_html__( 'Verification error, try again.' , 'villenoir' );
        } else {

            $name        = sanitize_text_field($_POST['name']);
            $phone       = sanitize_text_field($_POST['phone']);
            $email       = sanitize_email($_POST['email']);
            $email_check = villenoir_check_email($email);
            $subject     = sanitize_text_field($_POST['subject']);
            $messagecf   = esc_textarea($_POST['message']);
            $consent     = sanitize_text_field($_POST['consent']);


            if ($email_check == 1) {

                //Admin
                $subject_admin = sprintf(esc_html__( 'New contact form message from : %1$s' , 'villenoir' ), $name);

                //User
                $subject_user = sprintf(__( 'Your contact message on %1$s' , 'villenoir' ), get_option('blogname'));

                $message = '<html><body>';
                $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
                $message .= "<tr><td><strong>".esc_html__( 'Subject', 'villenoir')."</strong> </td><td>" . $subject . "</td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>".esc_html__( 'Name', 'villenoir')."</strong> </td><td>" . $name . "</td></tr>";
                $message .= "<tr><td><strong>".esc_html__( 'Email', 'villenoir')."</strong> </td><td>" . $email . "</td></tr>";
                $message .= "<tr style='background: #eee;'><td><strong>".esc_html__( 'Phone', 'villenoir')."</strong> </td><td>" . $phone . "</td></tr>";
                $message .= "<tr><td><strong>".esc_html__( 'Message', 'villenoir')."</strong> </td><td>" . $messagecf . "</td></tr>";
                if ( $privacy_checkbox ) {
                    $message .= "<tr style='background: #eee;'><td><strong>".esc_html__( 'Consent', 'villenoir')."</strong> </td><td>" . $consent . "</td></tr>";
                }
                $message .= "</table>";
                $message .= "</body></html>";

                $to = $contact_page_email;
                if (!isset($to) || ($to == '') ){
                    $to = get_option('admin_email');
                }
                
                $emailfrom = $contact_page_email_from;

                //Header admin
                $header_admin = 'From: '.get_option('blogname').' <'.$emailfrom.'>'. "\r\n";
                $header_admin .= 'Reply-To: '.$email. "\r\n";
                $header_admin .= 'Content-Type: text/html; charset=UTF-8'. "\r\n";

                //Header user
                $header_user = 'From: '.get_option('blogname').' <'.$emailfrom.'>'. "\r\n";
                $header_user .= 'Reply-To: '.$emailfrom. "\r\n";
                $header_user .= 'Content-Type: text/html; charset=UTF-8'. "\r\n";
                
                if ( wp_mail($to, $subject_admin, $message, $header_admin) ) {
                    $status = 'success';
                    $thankyou = '';
                    $error = ($thankyou != '') ? $thankyou : $contact_page_success;
                } else {
                    $error = $contact_page_error;
                }

                wp_mail($email, $subject_user, $message, $header_user);

            } else {
                $error = $email_check;
            }
        }
    }

    $resp = array('status' => $status, 'errmessage' => $error);
    wp_send_json($resp);
}
add_action( 'wp_ajax_cf_action', 'villenoir_cf_ajax' );
add_action( 'wp_ajax_nopriv_cf_action', 'villenoir_cf_ajax' );