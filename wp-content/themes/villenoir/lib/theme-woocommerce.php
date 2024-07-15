<?php
/*
 * Prevent the wc wizard from loading
 */
add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );


//Update version to current 
update_option( 'woocommerce_db_version', WC()->version );

/*
 * Load WooCommerce Memberships functions
 */
if ( get_option( 'wc_memberships_is_active' ) ) {
    require_once get_template_directory() . '/lib/woocommerce-memberships.php';
}

/*
 * Remove default stylesheet
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Suppress certain WooCommerce admin notices
 */
function villenoir_wc_suppress_nags() {
    if ( class_exists( 'WC_Admin_Notices' ) ) {
        // Remove the "you have outdated template files" nag
        WC_Admin_Notices::remove_notice( 'template_files' );
        
        // Remove the "install pages" and "wc-install" nag
        WC_Admin_Notices::remove_notice( 'install' );
    }
}
add_action( 'wp_loaded', 'villenoir_wc_suppress_nags', 99 );

/**
 * Load JavaScript for WC
 */
add_action('wp_enqueue_scripts', 'villenoir_wc_scripts_loader');
function villenoir_wc_scripts_loader() {
    //CSS
    wp_enqueue_style('villenoir-woocommerce', get_template_directory_uri() . '/styles/gg-woocommerce.css', false, VILLENOIR_THEMEVERSION, 'all');
    //Scripts
    wp_enqueue_script('perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.jquery.min.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    wp_enqueue_script('gsap', get_template_directory_uri() . '/js/gsap.min.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    wp_enqueue_script('bezier-easing', get_template_directory_uri() . '/js/bezier-easing.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    wp_enqueue_script('villenoir_wc', get_template_directory_uri() . '/js/woocommerce.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    wp_localize_script( 'villenoir_wc', 'villenoir_wc_settings',
        array(
            'is_cart' => is_cart(),
            'is_checkout' => is_checkout(),
            'header_quick_cart' => _get_field('gg_header_minicart','option', true),
        )
    ); 
}

/**
 * Define image sizes - filter
 */

function villenoir_custom_image_sizes() {
    $theme_support = get_theme_support( 'woocommerce' );
    $theme_support = is_array( $theme_support ) ? $theme_support[0] : array();
 
    unset( $theme_support['single_image_width'], $theme_support['thumbnail_image_width'] );
 
    remove_theme_support( 'woocommerce' );
    add_theme_support( 'woocommerce', $theme_support );
}

//Remove the filters at user input
if ( _get_field('gg_activate_product_image_sizes','option', false) === true ) {
    add_action('after_setup_theme', 'villenoir_custom_image_sizes', 11);
}


/**
 * WooCommerce Breadcrubs
 */
function villenoir_wc_breadcrumbs() {
    return array(
            'delimiter'   => ' <span class="delimiter">&frasl;</span> ',
            'wrap_before' => '<div class="gg-breadcrumbs"><i class="icon_house_alt"></i> ',
            'wrap_after'  => '</div>',
            'before'      => '',
            'after'       => '',
            'home'        => _x('Home', 'breadcrumb', 'villenoir'),
        );
}
add_filter( 'woocommerce_breadcrumb_defaults', 'villenoir_wc_breadcrumbs' );


/**
 * Add Sold out badge
 */
include (get_template_directory().'/lib/woocommerce-sold-out.php');

/**
 * Add Year custom field
 */
include (get_template_directory().'/lib/woocommerce-year-field.php');


/**
 * Hide shop page title
 */
add_filter('woocommerce_show_page_title', 'villenoir_remove_shop_title' );
function villenoir_remove_shop_title() {
    return false;
}

/** 
 * Remove tab headings
 */
add_filter('woocommerce_product_description_heading', 'villenoir_clear_tab_headings');
add_filter('woocommerce_product_additional_information_heading', 'villenoir_clear_tab_headings');
function villenoir_clear_tab_headings() {
    return '';
}

/** 
 * Remove product rating display on product loops
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/** 
 * Remove product rating display on product single
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

/** 
 * Move product tabs 
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );


/*
 * Add custom pagination
 */
function villenoir_wc_pagination() {
    villenoir_pagination();       
}
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action( 'woocommerce_after_shop_loop', 'villenoir_wc_pagination', 10);


/*
 * Allow shortcodes in product excerpts
 */
if (!function_exists('woocommerce_template_single_excerpt')) {
   function woocommerce_template_single_excerpt( $post ) {
       global $post;
       if ($post->post_excerpt) echo '<div itemprop="description">' . do_shortcode(wpautop(wptexturize($post->post_excerpt))) . '</div>';
   }
}

/*
 * Shop page - Number of products per row
 */
add_filter('loop_shop_columns', 'villenoir_shop_columns');
if (!function_exists('villenoir_shop_columns')) {
    function villenoir_shop_columns() {
        return _get_field('gg_shop_product_columns','option', '3');
    }
}

/*
 * Shop page - Number of products per page
 */
add_filter('loop_shop_per_page',  'villenoir_shop_products_per_page', 20);
if (!function_exists('villenoir_shop_products_per_page')) {
    function villenoir_shop_products_per_page() {
        $product_per_page = _get_field('gg_product_per_page','option', '12');
        return $product_per_page;
    }
}

/**
 * Enable/Disable Sale Flash
 */
if ( _get_field('gg_store_sale_flash','option', true) === true ) {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
} else {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
}

/**
 * Enable/Disable Products price
 */
if ( _get_field('gg_store_products_price','option', true) === true ) {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
    add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);    
} else {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
}

/**
 * Enable/Disable Add to cart
 */
if ( _get_field('gg_store_add_to_cart','option', true) === true ) {
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    add_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_add_to_cart',30);
} else {
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10);
}

/**
 * Options for product page
 */

/**
 * Move price bellow short description
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);

/*Sale flash*/
if ( _get_field('gg_product_sale_flash','option', true) === false ) {
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
}

/*Price*/
if ( _get_field('gg_product_products_price','option', true) === false ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
}

/*Product summary*/
if ( _get_field('gg_product_products_excerpt','option', true) === false ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
} 

/*Add to cart*/
if ( _get_field('gg_product_add_to_cart','option', true) === false ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
}

/*Meta*/
if ( _get_field('gg_product_products_meta','option', true) === false ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 40 );
}

/**
 * Remove Description tab
 */
add_filter( 'woocommerce_product_tabs', 'villenoir_product_remove_description_tab', 98);
function villenoir_product_remove_description_tab($tabs) {
    unset($tabs['description']);
    return $tabs;
}

/**
 * Remove Review tab
 */
add_filter( 'woocommerce_product_tabs', 'villenoir_product_remove_reviews_tab', 98);
function villenoir_product_remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}

/**
 * Remove Attributes tab
 */
add_filter( 'woocommerce_product_tabs', 'villenoir_product_remove_attributes_tab', 98);
function villenoir_product_remove_attributes_tab($tabs) {
    unset($tabs['additional_information']);
    return $tabs;
}

//Move product description under all
function villenoir_woocommerce_template_product_description() {
    wc_get_template( 'single-product/tabs/description.php' );
}
add_action( 'woocommerce_after_single_product_summary', 'villenoir_woocommerce_template_product_description', 10 );

//Move product attributes under add to cart
function villenoir_woocommerce_template_product_attributes() {
    wc_get_template( 'single-product/tabs/additional-information.php' );
}
add_action( 'woocommerce_single_product_summary', 'villenoir_woocommerce_template_product_attributes', 60 );

//Move product reviews under product attributes
function villenoir_woocommerce_template_product_reviews() {
    comments_template();
}
add_action( 'woocommerce_single_product_summary', 'villenoir_woocommerce_template_product_reviews', 65 );


//Tabs
//Disable reviews tab
if ( _get_field('gg_product_reviews_tab','option', false) === false ) {
    remove_action( 'woocommerce_single_product_summary', 'villenoir_woocommerce_template_product_reviews', 65 );
}
//Disable attributes tab
if ( _get_field('gg_product_attributes_tab','option', true) === false ) {
    remove_action( 'woocommerce_single_product_summary', 'villenoir_woocommerce_template_product_attributes', 60 );
}
//Disable description tab
if ( _get_field('gg_product_description_tab','option', true) === false ) {
    remove_action( 'woocommerce_after_single_product_summary', 'villenoir_woocommerce_template_product_description', 10 );
}

/**
 * Enable/Disable Related products
 */
if ( _get_field('gg_product_related_products','option', true) === true ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    add_filter( 'woocommerce_output_related_products_args', 'villenoir_related_products_args' );
    function villenoir_related_products_args( $args ) {
        $args['posts_per_page'] = 2; // 4 related products
        $args['columns'] = 2; // arranged in 2 columns
        return $args;
    }

} else {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

/**
 * Enable/Disable Up Sells products
 */
if ( _get_field('gg_product_upsells_products','option', true) === true ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
    add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

    if ( ! function_exists( 'woocommerce_upsell_display_output' ) ) {
        function woocommerce_upsell_display_output() {
            woocommerce_upsell_display( 2,2 ); // Display 2 products in rows of 2
        }
    }

} else {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
}


/**
 * Enable/Disable Cross Sells products
 */
if ( _get_field('gg_product_crosssells_products','option', true) === true ) {
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_output',5 );

    if ( ! function_exists( 'woocommerce_cross_sell_output' ) ) {
        function woocommerce_cross_sell_output() {
            woocommerce_cross_sell_display( 3,1 ); // Display 2 products in rows of 2
        }
    }
} else {
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
}

/**
 * Catalog mode functions (must be always the last function)
 */
if ( _get_field('gg_store_catalog_mode','option', false ) === true ) {
    // Remove add to cart button from the product loop
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10);
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_add_to_cart',30);

    // Remove add to cart button from the product details page
    remove_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_template_single_product_add_to_cart', 10, 2);
    
    add_filter( 'woocommerce_add_to_cart_validation', '__return_false', 10, 2 );

    // check for clear-cart get param to clear the cart
    add_action( 'init', 'villenoir_wc_clear_cart_url' );
    function villenoir_wc_clear_cart_url() {    
        global $woocommerce;
        if ( isset( $_GET['empty-cart'] ) ) { 
            $woocommerce->cart->empty_cart(); 
        }  
    }

    add_action( 'wp', 'villenoir_check_pages_redirect');
    function villenoir_check_pages_redirect() {
        $cart     = is_page( wc_get_page_id( 'cart' ) );
        $checkout = is_page( wc_get_page_id( 'checkout' ) );

        if ( $cart || $checkout ) {
            wp_redirect( esc_url( home_url( '/' ) ) );
            exit;
        }
    }
}

/**
 * Remove product category description - its included in page_header function
 **/

remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );


/**
 * Search function
 **/
if ( ! function_exists('villenoir_header_search') ) { 
    function villenoir_header_search() {
    ob_start();
    ?>
    <a href="#fullscreen-searchform" title="<?php esc_html_e('Search products', 'villenoir'); ?>">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </span>
    </a>

    <?php
    return ob_get_clean();
    } 
}

/**
 * Minicart function
 **/
if ( ! function_exists('villenoir_wc_minicart') ) { 
function villenoir_wc_minicart() {
ob_start();
?>
    <a id="quick_cart" data-target="open-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_html_e('View your shopping cart', 'villenoir'); ?>">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </span>
        <?php
            if ( WC()->cart->get_cart_contents_count() > 0 ) {
                echo '<span class="quick_cart_count">' . WC()->cart->get_cart_contents_count() . '</span>';
            } 
        ?>
    </a>

    <?php return ob_get_clean(); ?>

<?php } 

}

add_filter( 'woocommerce_add_to_cart_fragments', 'villenoir_wc_minicart_fragment' );
if ( ! function_exists( 'villenoir_wc_minicart_fragment' ) ) {
    function villenoir_wc_minicart_fragment( $fragments ) {
        $fragments['.gg-woo-mini-cart'] = '<li class="gg-woo-mini-cart">' . villenoir_wc_minicart() .'</li>';
        return $fragments;
    }
}

//WC
$shop_style = _get_field('gg_shop_product_style','option', 'style1');

if ( isset( $_GET['shop_style'] ) ) {
   $shop_style = $_GET['shop_style'];
}

//Style 1
if ( $shop_style == 'style1' ) {

    //Move product title before the image
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_title', 5 );

    //Wrap the product image in a div
    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_product_image_begin', 5 );
    function villenoir_wrap_product_image_begin() {
        echo '<div class="gg-product-image-wrapper">';
    }

    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_product_image_end', 15 );
    function villenoir_wrap_product_image_end() {
        echo '</div>';
    }

    //Close the link right after the image wrapper div
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );

    //Wrap the price/add to cart in a wrapper div
    add_action( 'woocommerce_after_shop_loop_item_title', 'villenoir_wrap_product_meta_begin', 5 );
    function villenoir_wrap_product_meta_begin() {
        echo '<div class="gg-product-meta-wrapper">';
    }

    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_meta_end', 10 );
    function villenoir_wrap_product_meta_end() {
        echo '</div>';
    }
} elseif ( $shop_style == 'style2' ) {

    //Wrap the product image in a div
    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_product_image_begin', 5 );
    function villenoir_wrap_product_image_begin() {
        echo '<div class="gg-product-image-wrapper">';
    }

    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_product_image_end', 15 );
    function villenoir_wrap_product_image_end() {
        echo '</div>';
    }

    //Close the link right after the image wrapper div
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );

    //Wrap the price/add to cart in a wrapper div
    add_action( 'woocommerce_after_shop_loop_item_title', 'villenoir_wrap_product_meta_begin', 5 );
    function villenoir_wrap_product_meta_begin() {
        echo '<div class="gg-product-meta-wrapper">';
    }

    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_meta_end', 10 );
    function villenoir_wrap_product_meta_end() {
        echo '</div>';
    }

} elseif ( $shop_style == 'style3' ) {
    
    //Wrap the whole product in figure
    add_action( 'woocommerce_before_shop_loop_item', 'villenoir_wrap_product_begin', 5 );
    function villenoir_wrap_product_begin() {
        echo '<figure class="gg-product-image-wrapper effect-zoe">';
    }

    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_end', 15 );
    function villenoir_wrap_product_end() {
        echo '</figure>';
    }

    //Close the link right before the image 
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

    //Wrap the others in a figcaption wrapper
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_wrap_product_meta_begin', 5 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 6 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 6 );
    function villenoir_wrap_product_meta_begin() {
        echo '<figcaption class="product-image-overlay">';
        echo '<span class="product-overlay-meta">';
    }

    
    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_meta_end', 15 );
    function villenoir_wrap_product_meta_end() {
        echo '</span class="product-overlay-meta">';
        echo '</figcaption>';
    }

    //Put the year above the title
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_add_year_above_title', 5 );
    function villenoir_add_year_above_title() {
        $wine_year = get_post_meta( get_the_ID(), '_year_field', true );
        if ( $wine_year ) {
            echo '<span class="year">'.esc_html($wine_year).'</span>';
        }
    }

} elseif ( $shop_style == 'style4' ) {

    //Wrap the product image in a div
    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_product_image_begin', 5 );
    function villenoir_wrap_product_image_begin() {
        echo '<div class="gg-product-image-wrapper">';
    }

    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_product_image_end', 15 );
    function villenoir_wrap_product_image_end() {
        echo '</div>';
    }

    //Close the link right after the image wrapper div
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );

    //Wrap the price/add to cart in a wrapper div
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_wrap_product_meta_begin', 5 );
    function villenoir_wrap_product_meta_begin() {
        echo '<div class="gg-product-meta-wrapper">';
    }

    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_meta_end', 10 );
    function villenoir_wrap_product_meta_end() {
        echo '</div>';
    }

    //Put the year above the title
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_add_year_above_title', 5 );
    function villenoir_add_year_above_title() {
        $wine_year = get_post_meta( get_the_ID(), '_year_field', true );
        if ( _get_field('gg_store_year_field','option', true) !== true ) {
            $wine_year = false;
        }
        if ( $wine_year ) {
            echo '<span class="year">'.esc_html($wine_year).'</span>';
        }
    }

} elseif ( $shop_style == 'style5' ) {
    //Wrap the whole product in wrapper
    add_action( 'woocommerce_before_shop_loop_item', 'villenoir_wrap_product_begin', 5 );
    function villenoir_wrap_product_begin() {
        echo '<div class="gg-product-image-wrapper effect-flip">';
        echo '<div class="hoverbox-inner">';

    }

    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_end', 15 );
    function villenoir_wrap_product_end() {
        echo '</div>';
        echo '</div>';
    }

    //Wrap the picture in a wrapper
    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_front_image_begin', 5 );
    function villenoir_wrap_front_image_begin() {
        echo '<div class="hoverbox-block hoverbox-flip-front">';
    }

    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_front_image_end', 15 );
    function villenoir_wrap_front_image_end() {
        echo '</div>';
    }

    //Close the link right before the image 
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

    //Wrap the others in a figcaption wrapper
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_wrap_product_meta_begin', 5 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 6 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 6 );
    function villenoir_wrap_product_meta_begin() {
        echo '<div class="hoverbox-block hoverbox-flip-back">';
    }

    
    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_meta_end', 15 );
    function villenoir_wrap_product_meta_end() {
        echo '</div>';
    }

    //remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    //remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_add_to_cart',30);

} elseif ( $shop_style == 'style6' ) {
    //Wrap the whole product in wrapper
    add_action( 'woocommerce_before_shop_loop_item', 'villenoir_wrap_product_begin', 5 );
    function villenoir_wrap_product_begin() {
        echo '<div class="product-flex-wrapper">';

    }

    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_end', 15 );
    function villenoir_wrap_product_end() {
        echo '</div>';
    }

    //Wrap the picture in a wrapper
    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_image_begin', 5 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 6 );
    function villenoir_wrap_image_begin() {
        echo '<div class="product-flex-image">';
    }

    add_action( 'woocommerce_before_shop_loop_item_title', 'villenoir_wrap_image_end', 15 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 14 );
    function villenoir_wrap_image_end() {
        echo '</div>';
    }

    //Close the link right before the image 
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

    //Wrap the others in a figcaption wrapper
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_wrap_product_meta_begin', 5 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 6 );
    add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 6 );
    function villenoir_wrap_product_meta_begin() {
        echo '<div class="product-flex-meta">';
    }

    
    add_action( 'woocommerce_after_shop_loop_item', 'villenoir_wrap_product_meta_end', 15 );
    function villenoir_wrap_product_meta_end() {
        echo '</div>';
    }

    //Remove title and replace it with title and year
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    add_action( 'woocommerce_shop_loop_item_title', 'villenoir_style_6_year_above_title', 7 );
    
    //Remove add to cart and add link (svg) to product on hover
    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_add_to_cart',30);
    add_action('woocommerce_after_shop_loop_item_title','villenoir_style_6_svg_link_to_product',30);

}

function villenoir_style_6_year_above_title() {
    $wine_year = get_post_meta( get_the_ID(), '_year_field', true );
    $wine_year_html = '';
    if ( _get_field('gg_store_year_field','option', true) !== true ) {
        $wine_year = false;
    }
    if ( $wine_year ) {
        $wine_year_html = '<span class="year">'.esc_html($wine_year).'</span>';
    }

    echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . $wine_year_html . get_the_title() . '</h2>';
}

function villenoir_style_6_svg_link_to_product() {
    echo '<span class="view-product-svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="13" viewBox="0 0 20 7.77">
            <polygon points="20 3.88 16.12 7.77 15.63 7.29 19.04 3.88 15.63 0.48 16.12 0 20 3.88"></polygon>
            <rect y="3.55" width="19.52" height="0.68"></rect>
          </svg></span>';
}

function villenoir_add_searchform() {
    //Add search form
    if ( _get_field('gg_header_search','option', false) === true ) {
        get_template_part( 'parts/part','searchform-toolbar' );
    }
}
add_action( 'gg_search_overlay', 'villenoir_add_searchform' );

//Fact sheet
function villenoir_woocommerce_template_product_factsheet() {
    $factsheet_doc = _get_field('gg_product_fact_sheet');
    if ($factsheet_doc) :
    ?>
    
        <a class="product-factsheet" href="<?php echo esc_url($factsheet_doc); ?>">
            <svg class="svg-icon-download" viewBox="0 0 20 20">
                <path fill="none" d="M9.634,10.633c0.116,0.113,0.265,0.168,0.414,0.168c0.153,0,0.308-0.06,0.422-0.177l4.015-4.111c0.229-0.235,0.225-0.608-0.009-0.836c-0.232-0.229-0.606-0.222-0.836,0.009l-3.604,3.689L6.35,5.772C6.115,5.543,5.744,5.55,5.514,5.781C5.285,6.015,5.29,6.39,5.522,6.617L9.634,10.633z"></path>
                <path fill="none" d="M17.737,9.815c-0.327,0-0.592,0.265-0.592,0.591v2.903H2.855v-2.903c0-0.327-0.264-0.591-0.591-0.591c-0.327,0-0.591,0.265-0.591,0.591V13.9c0,0.328,0.264,0.592,0.591,0.592h15.473c0.327,0,0.591-0.264,0.591-0.592v-3.494C18.328,10.08,18.064,9.815,17.737,9.815z"></path>
            </svg>
            <span><?php esc_html_e('Download the factsheet','villenoir');?></span>
        </a>
    
    <?php endif;
}
add_action( 'woocommerce_single_product_summary', 'villenoir_woocommerce_template_product_factsheet', 70 );

/* Variable price format */
function villenoir_variable_price_format( $price, $product ) {
 
    $prefix = '<span class="price-text-prefix">' . sprintf('%s ', __('From', 'villenoir')) . '</span>';
 
    $min_price_regular = $product->get_variation_regular_price( 'min', true );
    $min_price_sale    = $product->get_variation_sale_price( 'min', true );
    $max_price = $product->get_variation_price( 'max', true );
    $min_price = $product->get_variation_price( 'min', true );
 
    $price = ( $min_price_sale == $min_price_regular ) ?
        wc_price( $min_price_regular ) :
        '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . '</ins>';
 
    return ( $min_price == $max_price ) ?
        $price :
        sprintf('%s%s', $prefix, $price);
 
}
 
add_filter( 'woocommerce_variable_sale_price_html', 'villenoir_variable_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'villenoir_variable_price_format', 10, 2 );


/* Change Read more text to More Info */
add_filter( 'woocommerce_product_add_to_cart_text', function( $text ) {
    if ( 'Read more' == $text ) {
        $text = __( 'More Info', 'woocommerce' );
    }

    return $text;
} );


/* Side Cart */
function gg_side_cart() {
    if ( ! is_cart() && ! is_checkout() ) {
        ?>
        <nav id="side-cart" class="side-panel">
            <header>
                <h6><?php esc_html_e( 'Shopping cart', 'villenoir' ); ?></h6>
                <a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'villenoir' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="12" height="12" viewBox="1.1 1.1 12 12" enable-background="new 1.1 1.1 12 12" xml:space="preserve"><path d="M8.3 7.1l4.6-4.6c0.3-0.3 0.3-0.8 0-1.2 -0.3-0.3-0.8-0.3-1.2 0L7.1 5.9 2.5 1.3c-0.3-0.3-0.8-0.3-1.2 0 -0.3 0.3-0.3 0.8 0 1.2L5.9 7.1l-4.6 4.6c-0.3 0.3-0.3 0.8 0 1.2s0.8 0.3 1.2 0L7.1 8.3l4.6 4.6c0.3 0.3 0.8 0.3 1.2 0 0.3-0.3 0.3-0.8 0-1.2L8.3 7.1z"></path></svg></a>
            </header>
            <div class="side-panel-content">
                <?php
                if ( class_exists( 'WC_Widget_Cart' ) ) {
                    the_widget(
                        'WC_Widget_Cart',
                        array(
                            'title' => false,
                        )
                    );
                }
                ?>
            </div>
        </nav>
        <div class="click-capture"></div>
        <?php
    }
}
add_action( 'gg_side_cart', 'gg_side_cart', 3 );