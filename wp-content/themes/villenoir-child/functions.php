<?php
function villenoir_child_enqueue_styles() {
    //$parent_style = 'villenoir-style'; // This is 'parent-style' for the Villenoir theme.
   // wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'villenoir-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'villenoir_child_enqueue_styles' );