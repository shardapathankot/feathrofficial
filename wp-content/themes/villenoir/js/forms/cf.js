jQuery(document).ready(function($) {
	var form = $('#contact-form');
    
    $(form).each(function () {

        $(this).formValidation({framework: 'bootstrap'})
        .on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var fv = $form.data('formValidation');

            $form.find("#cf-msg").html('<div class="gg-ajax-loader">Loading...</div>');
			$.ajax({
				type: 'POST',
				url: ajax_object_cf.ajax_url,
				data: $form.serialize(),
				dataType: 'json',
				success: function(response) {
					if (response.status == 'success') {
						$form[0].reset();
					}
					$form.find("#cf-msg .gg-ajax-loader").remove();
					$form.find("#cf-msg").html(response.errmessage);

				}

			});

        });
});
});