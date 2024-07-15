<?php
/**
 * Define theme's widget areas.
 *
 */
function villenoir_widgets_init() {

    //Construct the default sidebars array
    $sidebars_array = array(
        esc_html__('Page Sidebar', 'villenoir')          => 'sidebar-page',
        esc_html__('Posts Sidebar', 'villenoir')         => 'sidebar-posts',
        esc_html__('Search Sidebar', 'villenoir')        => 'sidebar-search',
        esc_html__('Shop Sidebar', 'villenoir')          => 'sidebar-shop',
        esc_html__('Product Sidebar', 'villenoir')       => 'sidebar-product',
        esc_html__('Footer First Sidebar', 'villenoir')  => 'sidebar-footer-first',
        esc_html__('Footer Second Sidebar', 'villenoir') => 'sidebar-footer-second',
        esc_html__('Footer Third Sidebar', 'villenoir')  => 'sidebar-footer-third',
        esc_html__('Footer Fourth Sidebar', 'villenoir') => 'sidebar-footer-fourth',
    );


    foreach ($sidebars_array as $sidebar_name => $sidebar_id) {
        register_sidebar(
            array(
                'name'          => $sidebar_name,
                'id'            => $sidebar_id,
                'before_widget' => '<div id="%1$s" class="gg-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>'
            )
        );
    }
    

    //Dynamic sidebars
    if ( class_exists( 'acf' ) ) { //Check to see if ACF is installed
        if (_get_field('gg_sidebars','option')) {
            while (_has_sub_field('gg_sidebars','option')) { //Loop through sidebar fields to generate custom sidebars

                $s_name = _get_sub_field('gg_sidebar_name','option');
                $s_id   = str_replace(" ", "-", $s_name); // Replaces spaces in Sidebar Name to dash
                $s_id   = strtolower($s_id); // Transforms edited Sidebar Name to lowercase
                
                register_sidebar( array(
                    'name'          => $s_name,
                    'id'            => $s_id,
                    'before_widget' => '<div id="%1$s" class="gg-widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h4 class="widget-title">',
                    'after_title'   => '</h4>',
                ) );
            }
        }
    }
}
add_action('init', 'villenoir_widgets_init');
?>