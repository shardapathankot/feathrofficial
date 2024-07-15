<?php
$headings_array = array(
	esc_html__("Heading 1", "okthemes-villenoir-shortcodes")         => "h1", 
	esc_html__("Heading 2", "okthemes-villenoir-shortcodes")         => "h2", 
	esc_html__("Heading 3", "okthemes-villenoir-shortcodes")         => "h3", 
	esc_html__("Heading 4", "okthemes-villenoir-shortcodes")         => "h4",
	esc_html__("Heading 5", "okthemes-villenoir-shortcodes")         => "h5",
	esc_html__("Heading 6", "okthemes-villenoir-shortcodes")         => "h6"
);

$add_css_animation_extended = array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("CSS animation", "okthemes-villenoir-shortcodes"),
    "param_name" => "css_animation",
    "value" => array(
        "No" => "",
        //Fade in
        "Fade In" => "kd-animated fadeIn",
        "Fade In - 200 ms delay" => "kd-animated fadeIn animation-delay-200",
        "Fade In - 400 ms delay" => "kd-animated fadeIn animation-delay-400",
        "Fade In - 600 ms delay" => "kd-animated fadeIn animation-delay-600",
        "Fade In - 800 ms delay" => "kd-animated fadeIn animation-delay-800",
        //Fade in down
        "Fade In Down" => "kd-animated fadeInDown",
        "Fade In Down - 200 ms delay" => "kd-animated fadeInDown animation-delay-200",
        "Fade In Down - 400 ms delay" => "kd-animated fadeInDown animation-delay-400",
        "Fade In Down - 600 ms delay" => "kd-animated fadeInDown animation-delay-600",
        "Fade In Down - 800 ms delay" => "kd-animated fadeInDown animation-delay-800",
        //Fade in left
        "Fade In Left" => "kd-animated fadeInLeft",
        "Fade In Left - 200 ms delay" => "kd-animated fadeInLeft animation-delay-200",
        "Fade In Left - 400 ms delay" => "kd-animated fadeInLeft animation-delay-400",
        "Fade In Left - 600 ms delay" => "kd-animated fadeInLeft animation-delay-600",
        "Fade In Left - 800 ms delay" => "kd-animated fadeInLeft animation-delay-800",
        //Fade in right
        "Fade In Right" => "kd-animated fadeInRight",
        "Fade In Right - 200 ms delay" => "kd-animated fadeInRight animation-delay-200",
        "Fade In Right - 400 ms delay" => "kd-animated fadeInRight animation-delay-400",
        "Fade In Right - 600 ms delay" => "kd-animated fadeInRight animation-delay-600",
        "Fade In Right - 800 ms delay" => "kd-animated fadeInRight animation-delay-800",
        //Fade in up
        "Fade In Up" => "kd-animated fadeInUp",
        "Fade In Up - 200 ms delay" => "kd-animated fadeInUp animation-delay-200",
        "Fade In Up - 400 ms delay" => "kd-animated fadeInUp animation-delay-400",
        "Fade In Up - 600 ms delay" => "kd-animated fadeInUp animation-delay-600",
        "Fade In Up - 800 ms delay" => "kd-animated fadeInUp animation-delay-800",
        //Zoom in
        "Zoom In" => "kd-animated zoomIn",
        "Zoom In - 200 ms delay" => "kd-animated zoomIn animation-delay-200",
        "Zoom In - 400 ms delay" => "kd-animated zoomIn animation-delay-400",
        "Zoom In - 600 ms delay" => "kd-animated zoomIn animation-delay-600",
        "Zoom In - 800 ms delay" => "kd-animated zoomIn animation-delay-800",
    ),
    "save_always" => true,
    "admin_label" => true,
    "description" => esc_html__("Select type of animation for element to be animated when it enters the browsers viewport (Note: works only in modern browsers).", "okthemes-villenoir-shortcodes"),
    "group" => esc_html__( "Animations", "okthemes-villenoir-shortcodes" ),
);

$img_style_arr = array(
	esc_html__( 'Default (Square corners)', 'okthemes-villenoir-shortcodes' ) => "default",
	esc_html__( 'Rounded corners', 'okthemes-villenoir-shortcodes' )          => "rounded",
	esc_html__( 'Circle', 'okthemes-villenoir-shortcodes' )                   => "circle"
);

$icon_size_param = array(
    "edit_field_class" => "vc_col-sm-6 vc_column",
    "type"       => "dropdown",
    "heading"    => esc_html__("Icon size", "lucia-shortcodes"),
    "param_name" => "icon_size",
    "value"      => array(
        esc_html__("Large", "lucia-shortcodes")   => "icon-size-large",
        esc_html__("Normal", "lucia-shortcodes")  => "icon-size-normal",
        esc_html__("Small", "lucia-shortcodes") => "icon-size-small"
    ),
);

$align_param = array(
    "edit_field_class" => "vc_col-sm-6 vc_column",
    "type"       => "dropdown",
    "heading"    => esc_html__("Align", "lucia-shortcodes"),
    "param_name" => "align",
    "value"      => array(
        esc_html__("Left", "lucia-shortcodes")   => "text-align-left",
        esc_html__("Right", "lucia-shortcodes")  => "text-align-right",
        esc_html__("Center", "lucia-shortcodes") => "text-align-center"
    ),
);

$colors_array_extended = array(
    esc_html__("Primary color", "lucia-shortcodes")   => "primary-color",
    esc_html__("Secondary color", "lucia-shortcodes")  => "secondary-color",
    esc_html__("Custom", "lucia-shortcodes") => "custom-color"
);

$css_editor_array_extended_desktop = array(
    'type'       => 'css_editor',
    'heading'    => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
    'param_name' => 'css',
    'group'      => __( 'Design (Desktop)', 'okthemes-villenoir-shortcodes' ),
);

$css_editor_array_extended_tablet = array(
    'type'       => 'css_editor',
    'heading'    => __( 'CSS box tablet', 'okthemes-villenoir-shortcodes' ),
    'param_name' => 'css_tablet',
    'group'      => __( 'Design (Tablet)', 'okthemes-villenoir-shortcodes' ),
);

$css_editor_array_extended_mobile = array(
    'type'       => 'css_editor',
    'heading'    => __( 'CSS box mobile', 'okthemes-villenoir-shortcodes' ),
    'param_name' => 'css_mobile',
    'group'      => __( 'Design (Mobile)', 'okthemes-villenoir-shortcodes' ),
);