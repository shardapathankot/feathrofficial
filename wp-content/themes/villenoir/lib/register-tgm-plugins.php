<?php
add_action( 'tgmpa_register', 'villenoir_register_required_plugins' );

function villenoir_register_required_plugins() {
    $plugins = array(
        array(
            'name'               => 'OKThemes Villenoir Shortcodes', 
            'slug'               => 'okthemes-villenoir-shortcodes', 
            'source'             => get_template_directory() . '/plugins/okthemes-villenoir-shortcodes.zip',
            'required'           => true, 
            'force_activation'   => false, 
            'force_deactivation' => false, 
            'external_url'       => '', 
            'version'            => '2.4',
        ),
        array(
            'name'               => 'Advanced Custom Fields Pro', 
            'slug'               => 'advanced-custom-fields-pro', 
            'source'             => get_template_directory() . '/plugins/advanced-custom-fields-pro.zip',
            'required'           => true, 
            'force_activation'   => false, 
            'force_deactivation' => false, 
            'external_url'       => '', 
            'version'            => '6.2.7',
        ),        
        array(
            'name'               => 'WPBakery Visual Composer', 
            'slug'               => 'js_composer', 
            'source'             => get_template_directory() . '/plugins/js_composer.zip',
            'required'           => true, 
            'force_activation'   => false, 
            'force_deactivation' => false, 
            'external_url'       => '', 
            'version'            => '7.5',
        ),
        array(
            'name'               => 'Slider Revolution', 
            'slug'               => 'revslider', 
            'source'             => get_template_directory() . '/plugins/revslider.zip',
            'required'           => false, 
            'force_activation'   => false, 
            'force_deactivation' => false, 
            'external_url'       => '', 
            'version'            => '6.6.20',
        ),
        array(
            'name'      => 'One Click Demo Import',
            'slug'      => 'one-click-demo-import',
            'required'  => false,
        ),
        array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => false,
        ),
        array(
            'name'     => 'MailChimp for WordPress',
            'slug'     => 'mailchimp-for-wp',
            'required' => false,
        ),
        array(
            'name'     => 'Age Verify',
            'slug'     => 'age-verify',
            'required' => false,
        ),
        array(
            'name'     => 'The Events Calendar',
            'slug'     => 'the-events-calendar',
            'required' => false,
        ),
        array(
            'name'     => 'Smash Balloon Social Photo Feed',
            'slug'     => 'instagram-feed',
            'required' => false,
        ),
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'villenoir',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '', 
    );
    tgmpa( $plugins, $config );
}
?>