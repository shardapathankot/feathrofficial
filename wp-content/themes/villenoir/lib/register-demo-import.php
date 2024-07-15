<?php
function villenoir_import_files() {
    return array(
        array(
            'import_file_name'             => 'Villenoir Light Demo Import',
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/light/content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/light/widgets.json',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/light/customizer.dat',
            'local_import_acf_json' => array(
                array(
                    'file_path'     => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/light/theme_options.json',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'admin/importer/demo-files/light/villenoir-light-preview.jpg',
			'preview_url'                  => 'https://okthemes.com/villenoirdemonew/',
        ),
        array(
            'import_file_name'             => 'Villenoir Dark Demo Import',
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/dark/content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/dark/widgets.json',
            'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/dark/customizer.dat',
            'local_import_acf_json' => array(
                array(
                    'file_path'     => trailingslashit( get_template_directory() ) . 'admin/importer/demo-files/dark/theme_options.json',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'admin/importer/demo-files/dark/villenoir-dark-preview.jpg',
			'preview_url'                  => 'https://okthemes.com/villenoirdarkdemo/',
        ),
    );
}
add_filter( 'ocdi/import_files', 'villenoir_import_files' );


function villenoir_after_import_setup() {

    //Remove the hello world post
    wp_delete_post(1);

    //Remove the samples pages
    wp_delete_post(2);

    

    // Assign menus to their locations.
    $main_menu      = get_term_by('name', 'Header menu', 'nav_menu');
    $footer_menu = get_term_by('name', 'Footer menu', 'nav_menu');

    set_theme_mod( 'nav_menu_locations', array(
            'main-menu'      => $main_menu->term_id,
            'footer-menu' => $footer_menu->term_id,
        )
    );

    //Replace demo url with client url
    $menu_arr = wp_get_nav_menu_items($main_menu);
        
    foreach ( (array) $menu_arr as $key => $menu_item ) {
        $title = $menu_item->title;
        $url = $menu_item->url;
        $db_id = $menu_item->db_id;
        $position = $menu_item->menu_order;

        if ($url == 'http://okthemes.com/villenoirdemonew' || $url == 'http://okthemes.com/villenoirdarkdemo') {
            wp_update_nav_menu_item($main_menu->term_id, $db_id, array(
                'menu-item-title' => 'Home',
                'menu-item-url' => site_url(),
                'menu-item-position' => $position
                )
            );
        }
        
        if ( class_exists('Tribe__Events__Main') ) {
            if ($url == 'http://okthemes.com/villenoirdemonew/events/' || $url == 'https://okthemes.com/villenoirdarkdemo/events/') {
                wp_update_nav_menu_item($main_menu->term_id, $db_id, array(
                    'menu-item-title' => 'Events',
                    'menu-item-url' => site_url( '/events/' ),
                    'menu-item-position' => $position
                    )
                );
            }
        }
    }

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Homepage' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

    //Set the WC pages
    if ( villenoir_is_wc_activated() ) {

        //Shop Page
        $shop_page = get_page_by_title('Wines');
        if($shop_page && $shop_page->ID){
            update_option('woocommerce_shop_page_id',$shop_page->ID);
        }
        
        //Cart Page
        $cart_page = get_page_by_title('Cart');
        if($cart_page && $cart_page->ID){
            update_option('woocommerce_cart_page_id',$cart_page->ID);
        }
        
        //Checkout Page
        $checkout_page = get_page_by_title('Checkout');
        if($checkout_page && $checkout_page->ID){
            update_option('woocommerce_checkout_page_id',$checkout_page->ID);
        }
        
        //My Account Page
        $myaccount_page = get_page_by_title('My Account');
        if($myaccount_page && $myaccount_page->ID){
            update_option('woocommerce_myaccount_page_id',$myaccount_page->ID);
        }
        
    }

    if ( 'Villenoir Light Demo Import' === $selected_import['import_file_name'] ) {

    } elseif ( 'Villenoir Dark Demo Import' === $selected_import['import_file_name'] ) {

    }

    //Set the Revolution Slider
    if( class_exists('RevSlider') ) {
           
        $slider_array = array(
            get_template_directory()."/admin/importer/demo-sliders/light/main-slideshow.zip",
            get_template_directory()."/admin/importer/demo-sliders/light/secondary-slideshow.zip",
            get_template_directory()."/admin/importer/demo-sliders/light/homepage-v3.zip"
        );

        $slider = new RevSlider();
         
        foreach($slider_array as $filepath){
            $slider->importSliderFromPost(false,false,$filepath);
        }
                
    }

    //Set a flag to reset permalinks
    update_option('villenoir_just_imported','yes');

}
add_action( 'ocdi/after_import', 'villenoir_after_import_setup' );


// Check if just imported
$villenoir_just_imported = get_option('villenoir_just_imported');
if ( ! empty( $villenoir_just_imported ) ) {
    add_action( 'init', 'villenoir_update_permalinks' );
}

// Update permalinks
function villenoir_update_permalinks() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
    
    flush_rewrite_rules();
    
    delete_option( 'villenoir_just_imported' );
}

/**
 * Adding local_import_acf_json and import_json param supports.
 */
if ( ! function_exists( 'villenoir_after_content_import_execution' ) ) {
  function villenoir_after_content_import_execution( $selected_import_files, $import_files, $selected_index ) {

    $downloader = new OCDI\Downloader();

    if( ! empty( $import_files[$selected_index]['local_import_acf_json'] ) ) {

        foreach( $import_files[$selected_index]['local_import_acf_json'] as $index => $import ) {
            $file_path = $import['file_path'];
            $file_raw  = OCDI\Helpers::data_from_file( $file_path );

            $acf_options = json_decode( $file_raw, true );
            foreach ( $acf_options as $key => $value ) {
                update_option( $key, $value );
            }
        }
    }

  }
  add_action('ocdi/after_content_import_execution', 'villenoir_after_content_import_execution', 3, 99 );
}

//Remove branding
add_filter( 'ocdi/disable_pt_branding', '__return_true' );
//Disable generation of smaller images
add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

function ocdi_change_time_of_single_ajax_call() {
    return 10;
}
add_action( 'ocdi/time_for_one_ajax_call', 'ocdi_change_time_of_single_ajax_call' );
