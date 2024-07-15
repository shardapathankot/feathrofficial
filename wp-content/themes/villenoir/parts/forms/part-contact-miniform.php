<?php
$form_id = get_query_var( 'form_id' );
?>

<?php if (isset($form_title)) : ?>
<h3 class="contact-form-mini-header"><?php echo esc_html($form_title); ?></h3>
<?php endif; ?>

<form class="contact-form-mini"
    data-fv-addons="mandatoryIcon"
    data-fv-addons-mandatoryicon-icon="fa fa-asterisk"

    data-fv-message="This value is not valid" 
    data-fv-feedbackicons-valid="fa fa-check" 
    data-fv-feedbackicons-invalid="fa fa-times" 
    data-fv-feedbackicons-validating="fa fa-refresh">

	<div id="cmf-msg"></div><!-- Message display -->

    <div class="form-group">
        <label class="sr-only" for="name"><?php esc_html_e( 'Name', 'villenoir' ); ?></label>
        <input placeholder="<?php esc_html_e( 'Name', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The name is required and cannot be empty', 'villenoir' ); ?>" type="text" name="name" id="name" value="" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk" />
    </div>    
    <div class="form-group">    
        <label class="sr-only" for="email"><?php esc_html_e( 'Email', 'villenoir' ); ?></label>
        <input placeholder="<?php esc_html_e( 'Email', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The email is required and cannot be empty', 'villenoir' ); ?>" data-fv-emailaddress="true" data-fv-emailaddress-message="<?php esc_html_e( 'The input is not a valid email address', 'villenoir' ); ?>" type="text" name="email" id="email" value="" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk" />
    </div>
    <div class="form-group">    
        <label class="sr-only" for="message"><?php esc_html_e( 'Message', 'villenoir' ); ?></label>
        <textarea placeholder="<?php esc_html_e( 'Message', 'villenoir' ); ?>" data-fv-notempty="true" data-fv-notempty-message="<?php esc_html_e( 'The message is required and cannot be empty', 'villenoir' ); ?>" name="message" id="message" rows="11" class="form-control" data-fv-addons="mandatoryIcon" data-fv-addons-mandatoryicon-icon="glyphicon glyphicon-asterisk"></textarea>
    </div>
        <input name="action" type="hidden" value="cmf_action" />
        <input name="form_id" type="hidden" value="<?php echo esc_html($form_id); ?>" />
      	<?php wp_nonce_field( 'contact_form_html', '_cmf_nonce'); ?>
    <div class="form-group">
     	<button type="submit" id="cmfs" class="btn btn-default pull-right"><?php esc_html_e( 'Send', 'villenoir' ); ?></button>
    </div>
    </ul>
</form>
