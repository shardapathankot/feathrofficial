<?php
$contact_form_description = _get_field( 'gg_contact_form_description' );
$privacy_checkbox         = _get_field( 'gg_contact_page_privacy_checkbox' );
$privacy_checkbox_label   = _get_field( 'gg_contact_page_privacy_checkbox_label' );
$privacy_checkbox_error   = _get_field( 'gg_contact_page_privacy_checkbox_error_message' );
?>


<?php if ($contact_form_description) : ?>
<div class="contact-form-description">
<?php echo wp_kses_post($contact_form_description); ?>
</div>
<?php endif; ?>

<form id="contact-form"
    data-fv-addons="mandatoryIcon"
    data-fv-addons-mandatoryicon-icon="fa fa-asterisk"

    data-fv-message="This value is not valid" 
    data-fv-feedbackicons-valid="fa fa-check" 
    data-fv-feedbackicons-invalid="fa fa-times" 
    data-fv-feedbackicons-validating="fa fa-refresh">

	<div id="cf-msg"></div><!-- Message display -->

    <div class="form-group">
        <label class="hidden" for="name"><?php esc_html_e( 'Name', 'villenoir' ); ?></label>
        <input placeholder="<?php esc_html_e( 'Name', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The name is required and cannot be empty', 'villenoir' ); ?>" type="text" name="name" id="name" value="" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk" />
    </div>    
    <div class="form-group">    
        <label class="hidden" for="email"><?php esc_html_e( 'Email', 'villenoir' ); ?></label>
        <input placeholder="<?php esc_html_e( 'Email', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The email is required and cannot be empty', 'villenoir' ); ?>" data-fv-emailaddress="true" data-fv-emailaddress-message="<?php esc_html_e( 'The input is not a valid email address', 'villenoir' ); ?>" type="text" name="email" id="email" value="" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk" />
    </div>
    <div class="form-group">
        <label class="hidden" for="phone"><?php esc_html_e( 'Phone number', 'villenoir' ); ?></label>
        <input placeholder="<?php esc_html_e( 'Phone number', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The phone number is required and cannot be empty', 'villenoir' ); ?>" type="text" name="phone" id="phone" value="" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk" />
    </div>
    <div class="form-group">
        <label class="hidden" for="subject"><?php esc_html_e( 'Subject', 'villenoir' ); ?></label>
        <input placeholder="<?php esc_html_e( 'Subject', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The subject is required and cannot be empty', 'villenoir' ); ?>" type="text" name="subject" id="subject" value="" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk" />
    </div>
    <div class="form-group">    
        <label class="hidden" for="message"><?php esc_html_e( 'Message', 'villenoir' ); ?></label>
        <textarea placeholder="<?php esc_html_e( 'Message', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The message is required and cannot be empty', 'villenoir' ); ?>" name="message" id="message" rows="11" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk"></textarea>
    </div>
    
    <?php if ( $privacy_checkbox ) : ?>
    <div class="form-group">
        <label for="consent">
            <input id="consent" name="consent" type="checkbox" value="yes" data-fv-notempty="true" data-fv-notempty-message="<?php echo esc_html( $privacy_checkbox_error ); ?>" />
            <div class="privacy-label"><?php echo wp_kses_post($privacy_checkbox_label); ?></div>
        </label>
    </div>
        <input name="privacy_field" type="hidden" value="has_privacy_checkbox" />
    <?php endif; ?>

        <input name="action" type="hidden" value="cf_action" />
        <input name="post_id" type="hidden" value="<?php echo esc_html($post->ID); ?>" />
      	<?php wp_nonce_field( 'contact_form_html', '_cf_nonce'); ?>
    <div class="form-group">
     	<button type="submit" id="cfs" class="btn btn-default pull-left"><?php esc_html_e( 'Send', 'villenoir' ); ?></button>
    </div>
</form>
