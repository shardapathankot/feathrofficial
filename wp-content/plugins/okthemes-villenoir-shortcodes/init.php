<?php
/*
Plugin Name: OKThemes Villenoir Shortcodes
Plugin URI: http://www.okthemes.com
Description: Custom shortcodes for Villenoir theme
Version: 2.4
Author: Gogoneata Cristian
Author URI: http://okthemes.com/
License: GPLv2
*/

// Do not load this file directly!
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if(!defined('VILLENOIR_SHORTCODES_VERSION')) 
	define( 'VILLENOIR_SHORTCODES_VERSION', '2.0' );
if(!defined('VILLENOIR_SHORTCODES_PATH')) 
	define( 'VILLENOIR_SHORTCODES_PATH', plugin_dir_path(__FILE__) );
if(!defined('VILLENOIR_SHORTCODES_DIR')) 
	define( 'VILLENOIR_SHORTCODES_DIR', plugin_dir_url(__FILE__) );

load_plugin_textdomain( 'okthemes-villenoir-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

// Do not proceed if VC class WPBakeryShortCode does not exists
if ( ! class_exists( 'WPBakeryShortCode' ) ) {
    return;
}

//Include each file from the visualcomposer directory
foreach (glob(VILLENOIR_SHORTCODES_PATH.'/shortcodes/'."*.php") as $filename) {
    require_once $filename;
}

//Include functions file
require_once VILLENOIR_SHORTCODES_PATH . 'functions.php';

/**
 * Change path for overridden templates
 */
if( function_exists('vc_set_shortcodes_templates_dir') ) {
    $dir = plugin_dir_path( __FILE__ ) . '/shortcodes/vc-templates';
    vc_set_shortcodes_templates_dir($dir);
}


add_filter( 'vc_base_build_shortcodes_custom_css', 'exclude_mobile_tablet_css', 10, 2 );

function exclude_mobile_tablet_css( $css, $id ) {
    $post = get_post( $id );
    if ( ! is_object( $post ) ) {
        return $css;
    }

    $parsed_css = parseShortcodesCustomCss( $post->post_content );
    return $parsed_css;
}

function parseShortcodesCustomCss( $content ) {
    $css = '';
    if ( ! preg_match( '/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $content ) ) {
        return $css;
    }
    WPBMap::addAllMappedShortcodes();
    preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
    foreach ( $shortcodes[2] as $index => $tag ) {
        $shortcode = WPBMap::getShortCode( $tag );
        $attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
        if ( isset( $shortcode['params'] ) && ! empty( $shortcode['params'] ) ) {
            foreach ( $shortcode['params'] as $param ) {
                if ( isset( $param['type'] ) && 'css_editor' === $param['type'] && isset( $attr_array[ $param['param_name'] ] ) ) {
                    $param_value = $attr_array[ $param['param_name'] ];
                    if ( 'css_tablet' === $param['param_name'] ) {
                        $param_value = '@media (max-width: 768px) {' . $param_value . '}';
                    } elseif ( 'css_mobile' === $param['param_name'] ) {
                        $param_value = '@media (max-width: 480px) {' . $param_value . '}';
                    }
                    $css .= $param_value;
                }
            }
        }
    }
    foreach ( $shortcodes[5] as $shortcode_content ) {
        $css .= parseShortcodesCustomCss( $shortcode_content );
    }

    return $css;
}


/*** Row ***/
if( ! function_exists('villenoir_vc_row_map') ) {
    /**
     * Map VC Row shortcode
     * Hooks on vc_after_init action
     */
    function villenoir_vc_row_map() {

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

        vc_add_param('vc_row', array(
            "type"       => "checkbox",
            "heading"    => esc_html__("Reverse columns on mobile?", "okthemes-villenoir-shortcodes"),
            "value"      => array(esc_html__("Yes, please", "okthemes-villenoir-shortcodes") => "yes" ),
            "param_name" => "reverse_columns_mobile",
            'group'      => esc_html__( 'Villenoir Options', 'okthemes-villenoir-shortcodes' ),
        ));

        //Remove default animations
        vc_remove_param('vc_row', 'css_animation');
        vc_remove_param('vc_column', 'css_animation');
        vc_remove_param('vc_btn', 'css_animation');
        vc_remove_param('vc_single_image', 'css_animation');
        vc_remove_param('vc_column_text', 'css_animation');

        //Custom animations
        vc_add_param('vc_row', $add_css_animation_extended);
        vc_add_param('vc_row', $css_editor_array_extended_tablet);
        vc_add_param('vc_row', $css_editor_array_extended_mobile);

        vc_add_param('vc_row_inner', $add_css_animation_extended);
        vc_add_param('vc_row_inner', $css_editor_array_extended_tablet);
        vc_add_param('vc_row_inner', $css_editor_array_extended_mobile);

        vc_add_param('vc_column', $add_css_animation_extended);
        vc_add_param('vc_column', $css_editor_array_extended_tablet);
        vc_add_param('vc_column', $css_editor_array_extended_mobile);


        vc_add_param('vc_button', $add_css_animation_extended);
        vc_add_param('vc_btn', $add_css_animation_extended);
        vc_add_param('vc_single_image', $add_css_animation_extended);

        vc_add_param('vc_column_text', $add_css_animation_extended);
        vc_add_param('vc_column_text', $css_editor_array_extended_tablet);
        vc_add_param('vc_column_text', $css_editor_array_extended_mobile);

        vc_add_param('vc_empty_space', 
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Height (Tablet)', 'js_composer' ),
                'param_name' => 'height_tablet',
                'value' => '',
                'admin_label' => true,
                'description' => esc_html__( 'Enter empty space height for tablet.', 'js_composer' ),
                'group' => 'Tablet/Mobile'
            )
        );
        vc_add_param('vc_empty_space', 
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Height (Mobile)', 'js_composer' ),
                'param_name' => 'height_mobile',
                'value' => '',
                'admin_label' => true,
                'description' => esc_html__( 'Enter empty space height for mobile.', 'js_composer' ),
                'group' => 'Tablet/Mobile'
            )
        );

    }   

    add_action('vc_after_init', 'villenoir_vc_row_map');
}