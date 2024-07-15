<?php
/*
 * ACF Google font selector
 */
if( !function_exists('villenoir_include_field_types_google_font_selector') ) {
    include (get_template_directory() . '/lib/acf/acf-google-font-selector-field/acf-google_font_selector.php');
}

/*
 * ACF - Polylang fix
 */
if ( villenoir_is_polylang_activated() ) {
    include (get_template_directory() . '/lib/acf/acf-polylang.php');
}

/*
 * ACF Options page
 */
if( function_exists('acf_add_options_page') ) {
  
    acf_add_options_page(array(
        'parent_slug' => 'themes.php',
        'page_title'  => esc_html__( 'Theme options', 'villenoir' ),
        'menu_slug'   => 'theme-options',
        'capability'  => 'edit_posts',
        'redirect'    => false,
        'autoload' => true,
    ));

    function villenoir_load_acf_wp_admin_style() {
        wp_enqueue_style( 'acf_admin_css', get_template_directory_uri() . '/styles/acf-admin-style.css', false, '1.0.0' );
    }
    add_action( 'admin_enqueue_scripts', 'villenoir_load_acf_wp_admin_style' );
  
}

/*
 * ACF Social icons Loader
 */
function villenoir_acf_load_social_icons( $field ) {
    // reset choices
    $field['choices'] = array();
    include(get_template_directory() . '/lib/icons-array.php');

    $field['choices'] = $social_icons_arr;

    return $field;
}
 
add_filter('acf/load_field/name=gg_select_social_icon', 'villenoir_acf_load_social_icons');

/*
 * ACF Sidebar Loader
 */
function villenoir_acf_load_sidebar( $field ) {
    // reset choices
    $field['choices'] = array();
    $field['choices']['default-sidebar'] = 'Default Sidebar';

    // load repeater from the options page
    if(_get_field('gg_sidebars', 'option')) {
        // loop through the repeater and use the sub fields "value" and "label"
        while(has_sub_field('gg_sidebars', 'option')) {
            $label = get_sub_field('gg_sidebar_name');
            $value = str_replace(" ", "-", $label);
            $value = strtolower($value);
            $field['choices'][ $value ] = $label;
        }
    }
    // Important: return the field
    return $field;
}
//Page sidebar
add_filter('acf/load_field/name=gg_select_sidebar', 'villenoir_acf_load_sidebar');

/*
 * Add rev sliders to metabox
 */
if ( function_exists('set_revslider_as_theme') ) {
    function villenoir_populate_rv_select( $field ) {
        global $wpdb;

        $rs_table_name = $wpdb->prefix . "revslider_sliders";
        $limit = 999;
        $rs = $wpdb->get_results( $wpdb->prepare("SELECT id, title, alias FROM $rs_table_name ORDER BY id ASC LIMIT %d", $limit) );

        $revsliders = array();
        if ($rs) {
            foreach ( $rs as $slider ) {
                $revsliders[$slider->alias] = $slider->alias;
            }
        } else {
            $revsliders["No sliders found"] = 'No sliders found';
        }

        $field['choices'] = $revsliders;
        return $field;
    }
    add_filter('acf/load_field/name=gg_page_header_slider_select', 'villenoir_populate_rv_select');
}