<?php
/**
 * Theme Functions
 *
 * @author Gogoneata Cristian <cristian.gogoneata@gmail.com>
 * @package WordPress
 * @subpackage villenoir
 */

$theme = wp_get_theme();
if ( is_child_theme() ) {
    $theme = wp_get_theme( $theme->get( 'Template' ) );
}
$theme_version = $theme->get( 'Version' );
define("VILLENOIR_THEMEVERSION",$theme_version);

// Include the helpers
include (get_template_directory().'/lib/helpers.php');


if ( is_admin() ) {
    // Include the TGM configuration
    require_once (get_template_directory() . '/lib/class-tgm-plugin-activation.php');
    require_once (get_template_directory() . '/lib/register-tgm-plugins.php');

    // Include the importer
    require_once get_template_directory() . '/admin/importer/init.php';
    require_once get_template_directory() . '/lib/register-demo-import.php';
}

// ACF metaboxes
include get_template_directory() . '/lib/metaboxes.php';
// ACF fields
include get_template_directory() . '/lib/acf/acf-fields.php';
//ACF
if ( class_exists( 'acf' ) ) {
    
    // ACF functions
    include get_template_directory() . '/lib/acf/acf-functions.php';

    //ACF theme customizer
    include get_template_directory() . '/lib/theme-customizer/theme-customize.php';
    
    // Hide ACF field group menu item
    if( ! _get_field('gg_acf_admin_tab', 'option', false) ) :
        add_filter('acf/settings/show_admin', '__return_false');
    endif;

    // Include text domain for metaboxes
    function villenoir_acf_settings_textdomain( $export_textdomain ) {
        return 'villenoir';
    }
    add_filter('acf/settings/export_textdomain', 'villenoir_acf_settings_textdomain');

}

// Include sidebars
require_once (get_template_directory() . '/lib/sidebars.php');

// Include widgets
require_once (get_template_directory() . '/lib/widgets.php');

/**
 * Load woocommerce functions
 */
if ( villenoir_is_wc_activated() ) {
    require_once get_template_directory() . '/lib/theme-woocommerce.php';
}

// Include aq resize
include (get_template_directory() . '/lib/aq_resizer.php');

// Include mobile detect
include_once (get_template_directory() . '/lib/Mobile_Detect.php');

// Include breadcrumbs
include_once (get_template_directory() . '/lib/breadcrumbs.php');

/**
 * Maximum allowed width of content within the theme.
 */
if (!isset($content_width)) {
    $content_width = 1170;
}

/**
 * Disable redirection to Getting Started Page after activating Elementor.
 */
add_action(
	'admin_init',
	function() {
		if ( did_action( 'elementor/loaded' ) ) {
			remove_action( 'admin_init', array( \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ) );
		}
	},
	1
);

/**
 * Setup Theme Functions
 *
 */
if (!function_exists('villenoir_theme_setup')):
    function villenoir_theme_setup() {

        load_theme_textdomain('villenoir', get_template_directory() . '/lang');

        add_theme_support( 'title-tag' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'woocommerce' );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
        
        $defaults = array(
            'default-color'          => 'ffffff',
            'default-image'          => '',
            'default-repeat'         => '',
            'default-position-x'     => '',
            'wp-head-callback'       => 'villenoir_page_background_cb',
        );
        add_theme_support( 'custom-background', $defaults);

        register_nav_menus(
            array(
                'main-menu'      => esc_html__('Main Menu', 'villenoir'),
                'footer-menu'    => esc_html__('Footer Menu', 'villenoir')
            )
        );

        set_post_thumbnail_size('full');

        /*WooCommerce 3.0*/
        //add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        

        /*WooCommerce 3.3*/
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width'         => 9999,
            'gallery_thumbnail_image_width' => 140,
            'single_image_width'            => 9999,
        ) );
    }
endif;
add_action('after_setup_theme', 'villenoir_theme_setup');

if (!function_exists('villenoir_page_background_cb')) :
    function villenoir_page_background_cb() { 
        $page_background = _get_field('gg_page_background');
        $page_background_style = '';

        if ($page_background) :
        $page_background_style = 'background: url('.esc_url($page_background).');';
        $page_background_style .= ' background-repeat: no-repeat;';
        $page_background_style .= ' background-position: center bottom;';
        //$page_background_style .= ' background-attachment: local;';
        $page_background_style .= ' background-size: inherit;';
        ?>

        <style type="text/css">
            body.pace-done { <?php echo esc_html( $page_background_style ); ?> }
        </style>

        <?php 
        endif;
    }
endif;

/**
 * JavaScript Detection.
 */
function villenoir_javascript_detection() {
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'villenoir_javascript_detection', 0 );

/**
 * Display preloader
 *
 */
function villenoir_site_preloader() {
    ?>
    <div id="site-preloader" class="site-preloader">
        <div class="pace-progress" data-progress="0">
            <div class="pace-progress-inner"></div>
        </div>
    </div>
    <?php
}
add_action('wp_body_open', 'villenoir_site_preloader');

if ( ! function_exists( 'villenoir_fonts_url' ) ) :

function villenoir_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    /*
     * Translators: If there are characters in your language that are not supported
     * by Lato, translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Lato font: on or off', 'villenoir' ) ) {
        $fonts[] = 'Lato:300,400,700';
    }

    /*
     * Translators: If there are characters in your language that are not supported
     * by Playfair Display, translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'villenoir' ) ) {
        $fonts[] = 'Playfair Display:400,700';
    }

    /*
     * Translators: To add an additional character subset specific to your language,
     * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
     */
    $subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'villenoir' );

    if ( 'cyrillic' == $subset ) {
        $subsets .= ',cyrillic,cyrillic-ext';
    } elseif ( 'greek' == $subset ) {
        $subsets .= ',greek,greek-ext';
    } elseif ( 'devanagari' == $subset ) {
        $subsets .= ',devanagari';
    } elseif ( 'vietnamese' == $subset ) {
        $subsets .= ',vietnamese';
    }

    if ( $fonts ) {
        $fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $fonts ) ),
            'subset' => urlencode( $subsets ),
            'display' => 'swap',
        ), '//fonts.googleapis.com/css' );
    }

    return $fonts_url;
}
endif;


/**
 * Load CSS styles for theme.
 *
 */
function villenoir_styles_loader() {
    /*Site preloader*/
    if( _get_field('gg_site_preloader', 'option', true) ) :
        wp_enqueue_style('site-preloader', get_template_directory_uri() . '/styles/site-loader.css', false, VILLENOIR_THEMEVERSION, 'all');
        endif;

    /*Register fonts if acf is not available*/
    if ( ! class_exists( 'acf' ) ) {
        // Add custom fonts, used in the main stylesheet.
        wp_enqueue_style( 'villenoir-google-fonts', villenoir_fonts_url(), array(), null );
    }

    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', false, VILLENOIR_THEMEVERSION, 'all');

    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', false, VILLENOIR_THEMEVERSION, 'all');
    
    wp_enqueue_style('isotope', get_template_directory_uri() . '/styles/isotope.css', false, VILLENOIR_THEMEVERSION, 'all');
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/styles/magnific-popup.css', false, VILLENOIR_THEMEVERSION, 'all');

    //SlickCarousel
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/slick/slick.css', false, VILLENOIR_THEMEVERSION, 'all');

    //Form validation and addons
    wp_enqueue_style('villenoir-bootval', get_template_directory_uri() . '/assets/bootstrap-validator/css/formValidation.min.css', false, VILLENOIR_THEMEVERSION, 'all');

    //Default stylesheet + child
    wp_enqueue_style( 'villenoir-style', get_template_directory_uri() . '/style.css' );
   
    //Responsive stylesheet
    wp_enqueue_style('villenoir-responsive', get_template_directory_uri() . '/styles/responsive.css', false, VILLENOIR_THEMEVERSION, 'all');


    if ( villenoir_is_wpml_activated() ) {
        // get lang direction and enqueue rtl stylesheet if needed.
        if( ICL_LANGUAGE_CODE == 'he' ){
            wp_enqueue_style('rtl', get_template_directory_uri().'/rtl.css');
        }
    }

    // Add custom inline styles.
    wp_add_inline_style( 'villenoir-style', villenoir_custom_style() );

    if ( class_exists( 'acf' ) ) {
        wp_add_inline_style( 'villenoir-style', villenoir_generate_dynamic_css() );
    }
}
add_action('wp_enqueue_scripts', 'villenoir_styles_loader');


/**
 * Load JavaScript and jQuery files for theme.
 *
 */
function villenoir_scripts_loader() {

    $setBase = (is_ssl()) ? "https://" : "http://";

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    /*Site preloader*/
    if( _get_field('gg_site_preloader', 'option', true) ) :
        wp_enqueue_script('site-preloader',get_template_directory_uri() ."/js/site-preloader.js",array('jquery'),VILLENOIR_THEMEVERSION,true);
    endif;

    //Primary navigation
    wp_enqueue_script( 'villenoir-navigation',get_template_directory_uri().'/js/primary-navigation.js', array(), '', false);
   
    //Waypoints
    wp_enqueue_script( 'waypoints',get_template_directory_uri().'/js/jquery.waypoints.min.js', array(), '', false);   

    /*Site plugins*/
    wp_enqueue_script('villenoir-plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    
    /*Smooth scroll*/
    if( _get_field('gg_site_smooth_scroll', 'option', true) ) :
        wp_enqueue_script('villenoir-smoothscroll', get_template_directory_uri() . '/js/SmoothScroll.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    endif;      
    
    /* Contact form + Maps*/
    if ( is_page_template( 'theme-templates/contact.php' ) ) {

        wp_enqueue_script('google-maps-api',$setBase."maps.google.com/maps/api/js?key=" . esc_js(_get_field('gg_google_api_key', 'option')) . "&libraries=geometry");
        wp_enqueue_script('maplace',get_template_directory_uri() ."/js/maplace-0.1.3.min.js",array('jquery'),VILLENOIR_THEMEVERSION,true);

        wp_enqueue_script('villenoir-cfjs', get_template_directory_uri() ."/js/forms/cf.js",array('jquery'),VILLENOIR_THEMEVERSION,true);
        wp_localize_script( 'villenoir-cfjs', 'ajax_object_cf',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );
    }

    /* Contact miniform */
    wp_enqueue_script('villenoir-cmfjs', get_template_directory_uri() ."/js/forms/cmf.js",array('jquery'),VILLENOIR_THEMEVERSION,true);
    wp_localize_script( 'villenoir-cmfjs', 'ajax_object_cmf',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        )
    );

    /* General */
    wp_enqueue_script('villenoir-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), VILLENOIR_THEMEVERSION, true);
    wp_localize_script( 'villenoir-custom', 'villenoir_custom_object',
        array(
            'infinite_scroll_img' => get_template_directory_uri().'/images/animated-ring.gif',
            'infinite_scroll_msg_text' => esc_html__( 'Loading the next set of posts...', 'villenoir' ),
            'infinite_scroll_finished_msg_text' => esc_html__( 'All posts loaded.', 'villenoir' ),
        )
    );

}
add_action('wp_enqueue_scripts', 'villenoir_scripts_loader');



/**
 * Display template for post meta information.
 *
 */
if (!function_exists('villenoir_posted_on')) :
    function villenoir_posted_on() {

    $date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );

    printf($date);    

}
endif;

if ( ! function_exists( 'villenoir_posted_on_summary' ) ) :
    function villenoir_posted_on_summary() {
        
        if ( is_single() ) {
            echo '<time class="updated" datetime="'. get_the_time( 'c' ) .'">'. sprintf( esc_html__( 'Posted on %s ', 'villenoir' ), get_the_date() ) .'</time>';
            echo '<p class="byline author">'. esc_html__( 'by', 'villenoir' ) .' <a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';

            $categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'villenoir' ) );
            if ( $categories_list ) {
              printf( '<span class="cat-links"><span> %1$s </span>%2$s</span>',
                _x( 'in', 'Used before category names.', 'villenoir' ),
                $categories_list
              );
            }

        } else {
            echo '<p class="byline author">'. esc_html__( 'By', 'villenoir' ) .' <a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';
        }

        if ( the_title( ' ', ' ', false ) == "" ) {
            echo '<time class="updated" datetime="'. get_the_time( 'c' ) .'">'. sprintf( '%1$s <a href="%2$s" rel="bookmark"> %3$s </a>', esc_html__( 'Posted on', 'villenoir' ), get_permalink(), get_the_date() ) .'</time>';
        }
        
        //echo '<div class="clearfix"></div>';
    }
endif;

/**
 * Display page header
 *
 */
if ( ! function_exists( 'villenoir_page_header' ) ) :

function villenoir_page_header() {
    //Get global post id
    $post_id               = villenoir_global_page_id();
    
    $page_header           = _get_field('gg_page_header',$post_id, true);
    $page_header_style     = _get_field('gg_page_header_style',$post_id, 'style1');
    $page_title            = _get_field('gg_page_title',$post_id,true);
    $page_subtitle         = _get_field('gg_page_subtitle',$post_id,'');
    $page_breadcrumbs      = _get_field('gg_page_breadcrumbs',$post_id,false);
    $page_description      = _get_field('gg_page_description',$post_id,'');
    $post_featured_image   = _get_field('gg_post_featured_image',$post_id,'post_body');
    $tax_header_image      = _get_field('gg_taxonomy_header_image',get_queried_object(),'');
    $gg_page_header_parallax = _get_field('gg_page_header_parallax',$post_id, true);

    $page_header_img_style = '';
    $page_header_img_cls   = '';
    $page_header_has_parallax = '';

    if ( $gg_page_header_parallax ) {
        $page_header_has_parallax = '<div class="parallax-overlay" data-vc-kd-parallax="1.5"></div>';
        $page_header_img_cls = 'gg_vc_parallax ';
    }
   
    if (is_singular('product')) {
        //$page_breadcrumbs = true;
    }

    //Get product category description
    if ( is_tax( array( 'product_cat', 'product_tag' ) ) && 0 === absint( get_query_var( 'paged' ) ) ) {
        $page_description = wc_format_content( term_description() );
    }

    $page_header_slider = _get_field('gg_page_header_slider',$post_id, false);
    $rev_slider_alias   = _get_field('gg_page_header_slider_select',$post_id);

    //Page header image - style 3
    if ($page_header_style == 'style3' || $post_featured_image !== 'post_body') :
    if ( has_post_thumbnail($post_id) && !is_singular('product') && !is_archive() && !is_search() && !is_post_type_archive() ) {
        $page_header_img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
        $page_header_img_style = 'style="background-image: url('.esc_url($page_header_img_url[0]).');"';
        $page_header_img_cls .= 'style3-image-header';
    }
    endif;
    //Page header image - style 4
    if ($page_header_style == 'style4' || $post_featured_image !== 'post_body') :
    if ( has_post_thumbnail($post_id) && !is_singular('product') && !is_archive() && !is_search() && !is_post_type_archive() ) {
        $page_header_img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
        $page_header_img_style = 'style="background-image: url('.esc_url($page_header_img_url[0]).');"';
        $page_header_img_cls .= 'style4-image-header';
    }
    endif;
    //Shop page 
    if ($page_header_style == 'style3' && (villenoir_is_wc_activated() && is_shop()) ) {
        $page_header_img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
        $page_header_img_style = 'style="background-image: url('.esc_url($page_header_img_url[0]).');"';
        $page_header_img_cls .= 'style3-image-header';
    }
    if ($page_header_style == 'style4' && (villenoir_is_wc_activated() && is_shop()) ) {
        $page_header_img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
        $page_header_img_style = 'style="background-image: url('.esc_url($page_header_img_url[0]).');"';
        $page_header_img_cls .= 'style4-image-header';
    }

    if ( $tax_header_image  ) {
        $page_header_img_style = 'style="background-image: url('.esc_url($tax_header_image).');"';
    }

    ?>

    <!-- Page header image -->
    <?php
    if ($page_header_style === 'style2' &&
        has_post_thumbnail($post_id) &&
        !is_singular('product') &&
        !is_single() &&
        !is_archive() &&
        !is_post_type_archive() &&
        !is_search()
    ) {
        $page_header_img_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $page_header_img_style_2 = sprintf('style="background-image: url(%s);"', esc_url($page_header_img_url[0]));
        $page_header_img_style_2_cls = 'gg_vc_parallax';

        ?>
        <div class="page-header-image <?php echo esc_attr($page_header_img_style_2_cls); ?>" <?php echo wp_kses_post($page_header_img_style_2); ?>>
            <?php echo wp_kses_post($page_header_has_parallax); ?>
            <?php echo get_the_post_thumbnail($post_id, 'full'); ?>
        </div>
        <?php
    }
    ?>
    <!-- End Page header image -->

    <?php if ( ( is_page() || is_single() ) && $page_header_slider && function_exists('set_revslider_as_theme') ) : ?>
    <div class="subheader-slider">
        <div class="container">
            <?php putRevSlider(esc_html($rev_slider_alias)); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php endif; ?>
           
    <?php
    if (is_archive() && !is_post_type_archive('product')) {
        $page_header = true;
    }
    if (
        ($page_header === TRUE || $page_header === NULL) && 
        !is_front_page() &&
        !is_404() 
    ) :
        
    ?>
        <!-- Page meta -->
        <div class="page-meta <?php echo esc_attr($page_header_img_cls); ?>" <?php echo wp_kses_post($page_header_img_style); ?>>

            <?php echo wp_kses_post($page_header_has_parallax); ?>

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="page-meta-wrapper">
                        
                        <?php if ($page_subtitle != '' ) : ?>
                        <p class="page-header-subtitle"><?php echo esc_html($page_subtitle); ?></p>
                        <?php endif; ?>

                        <?php if ( ($page_title === TRUE OR $page_title === NULL) && !is_singular('product') )  : ?>
                        <h1>
                            <?php echo villenoir_wp_title(); ?>
                                
                            <?php if ( villenoir_is_wc_activated() && is_cart() )  {
                                echo sprintf ( _n( '(%d item)', '(%d items)', WC()->cart->get_cart_contents_count(),'villenoir' ), WC()->cart->get_cart_contents_count() );
                            } ?>

                            </h1>
                        <?php endif; ?>

                        <?php 
                        if ( $page_breadcrumbs === TRUE OR $page_breadcrumbs === NULL ) :
                            if (function_exists('villenoir_breadcrumbs')) villenoir_breadcrumbs();
                        endif;
                        ?>

                        <?php if ( class_exists( 'Tribe__Events__Main' ) && tribe_is_month() ) : ?>
                            <h1><?php echo tribe_get_events_title() ?></h1>
                        <?php endif; ?>

                        <?php if ( is_singular( 'tribe_events' ) ) : ?>
                            <p class="tribe-events-back">
                                <a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '' . esc_html__( 'All %s', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?></a>
                            </p>
                        <?php endif; ?>

                        <?php if ($page_description != '' ) : ?>
                        <div class="header-page-description">
                            <?php echo wp_kses_post($page_description); ?>
                        </div>
                        <?php endif; ?>
                        </div><!-- .page-meta-wrapper -->

                    </div><!-- .col-md-12 -->
                    
                </div><!-- .row -->
            </div><!-- .container -->

        </div><!-- .page-meta -->
        <!-- End Page meta -->

        <!-- Page header image -->
        <?php if ($page_header_style == 'style1') : ?>
            <?php if ( has_post_thumbnail($post_id) && !is_singular('product') && !is_single() && !is_archive() && !is_post_type_archive() && !is_search() ) :
                $page_header_img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
                $page_header_img_style_1 = 'style="background-image: url('.esc_url($page_header_img_url[0]).');"';
                $page_header_img_style_1_cls = 'gg_vc_parallax';
            ?>
            <div class="page-header-image <?php echo esc_attr($page_header_img_style_1_cls); ?>"  <?php echo wp_kses_post($page_header_img_style_1); ?>>
                <?php echo wp_kses_post($page_header_has_parallax); ?>
                <?php echo get_the_post_thumbnail( $post_id, 'full' ); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <!-- End Page header image -->

        <?php
            //Get product category image
            if ( is_tax( array( 'product_cat', 'product_tag' ) ) ) {
                $image_id = get_term_meta( get_queried_object_id(), 'thumbnail_id', true );
                if ( $image_id ) {
                    $page_header_tax_img_url = wp_get_attachment_image_url( $image_id, 'full' );
                    if ( $page_header_tax_img_url ) {
                        printf( '<div class="page-header-image gg_vc_parallax" style="background-image: url(%s)"></div>', esc_url( $page_header_tax_img_url ) );
                    }
                }
            }
        ?>

    <?php endif; ?>

<?php
}
endif;

/**
 * Display template for post footer information (in single.php).
 *
 */
if (!function_exists('villenoir_posted_in')) :
    function villenoir_posted_in() {

    // Translators: used between list items, there is a space after the comma.
    $tag_list = get_the_tag_list('<ul class="list-inline post-tags"><li>','</li><li>','</li></ul>');

    // Translators: 1 is the tags
    if ( $tag_list ) {
        $utility_text = esc_html__( '%1$s', 'villenoir' );
    } 

    printf($tag_list);

}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 */
if (!function_exists('villenoir_body_classes')) :
    function villenoir_body_classes($classes) {

        $page_header_style           = _get_field('gg_page_header_style',villenoir_global_page_id(), 'style1');
        $page_header_slider          = _get_field('gg_page_header_slider', villenoir_global_page_id(), false);
        $page_header_slider_position = _get_field('gg_page_header_slider_position', villenoir_global_page_id(),'under_header');
        $page_header_transparent = _get_field('gg_transparent_header', villenoir_global_page_id(),false);

        if ( has_post_thumbnail( villenoir_global_page_id() ) ) {
            $classes[] = 'gg-page-has-header-image';
        }
        if ( $page_header_slider ) {
            $classes[] = 'gg-page-has-header-slider';
        }
        if ( $page_header_slider && $page_header_slider_position ) {
            $classes[] = 'gg-slider-is-'.$page_header_slider_position.'';
        }

        if ( $page_header_style ) {
            if (is_search()) {
                $classes[] = 'gg-page-header-style1';
            } else {
                $classes[] = 'gg-page-header-'.$page_header_style.''; 
            }
        }

        if ( $page_header_transparent ) {
            $classes[] = 'gg-page-has-transparent-header';
        }

        //Header styles
        $overwrite_header_style = _get_field('gg_overwrite_header_style_on_page', villenoir_global_page_id(), false);

        if ($overwrite_header_style) {
            $nav_sticky = _get_field('gg_page_sticky_menu',villenoir_global_page_id(), false);
            $nav_menu = _get_field('gg_page_menu_style',villenoir_global_page_id(), 'style_1');
        } else {
            $nav_sticky = _get_field('gg_sticky_menu','option', false);
            $nav_menu = _get_field('gg_menu_style','option', 'style_1');
        }
        
        if ($nav_sticky) {
            $classes[] = 'gg-has-stiky-menu';
        }
        //Sticky logo
        $sticky_logo = _get_field('gg_sticky_logo_image', 'option');
        if ($sticky_logo) {
            $classes[] = 'gg-has-sticky-logo';
        }
        //Mobile logo
        $mobile_logo = _get_field('gg_mobile_logo_image', 'option');
        if ($mobile_logo) {
            $classes[] = 'gg-has-mobile-logo';
        }

         if ($nav_menu) {
            $classes[] = 'gg-has-'.$nav_menu.'-menu';
        }
        //End header styles

        if (!is_multi_author()) {
            $classes[] = 'single-author';
        }

        if (is_page_template('theme-templates/gallery.php')) {
            $classes[] = 'gg-gallery-template';
        }

        if (is_page_template('theme-templates/contact.php')) {
            $classes[] = 'gg-contact-template';
        }

        if (!_get_field('gg_site_preloader', 'option',true)) {
            $classes[] = 'pace-not-active';
        }

        if ( !_get_field('gg_footer_text', 'option','') ) {
            $classes[] = 'no-footer-text';
        }

        //Post
        $post_nav = _get_field('gg_post_navigation',villenoir_global_page_id(),true);
        if ( ! $post_nav ) {
            $classes[] = 'gg-post-nav-off';
        }

        //WC
        $shop_style = _get_field('gg_shop_product_style','option', 'style1');

        if ( ( villenoir_is_wc_activated() && is_shop() ) && isset( $_GET['shop_style'] ) ) {
           $shop_style = $_GET['shop_style'];
        }

        if ( villenoir_is_wc_activated() ) {
            $classes[] = 'gg-shop-'.$shop_style;
        }

        //WPML
        if ( villenoir_is_wpml_activated() ) {
            
            $classes[] = 'gg-theme-has-wpml';
            
            //WPML currency
            if ( class_exists('woocommerce_wpml') ) {
                $classes[] = 'gg-theme-has-wpml-currency';
            }
        }

        //Mobile
        $detect = new villenoir_Mobile_Detect;
        if( $detect->isMobile() || $detect->isTablet() ){
            $classes[] = 'gg-theme-is-mobile';
        }

        return $classes;
    }
    add_filter('body_class', 'villenoir_body_classes');
endif;

/**
 * Replaces the login header logo
 */
if (!function_exists('villenoir_admin_login_style')) :
    add_action( 'login_head', 'villenoir_admin_login_style' );
    function villenoir_admin_login_style() {

        $display_logo = _get_field('gg_display_admin_image_logo', 'option');
        $logo = _get_field('gg_admin_logo_image', 'option');

        if ( $display_logo && $logo ) : ?>
            <style>
            .login h1 a { 
                background-image: url( <?php echo esc_url($logo['url']); ?> ) !important; 
                background-size: <?php echo esc_attr($logo['width']); ?>px <?php echo esc_attr($logo['height']);?>px;
                width:<?php echo esc_attr($logo['width']); ?>px;
                height:<?php echo esc_attr($logo['height']); ?>px;
                margin-bottom:15px; 
            }
            </style>
        <?php
        endif;
    }
endif;

/* Modify the titles */

add_filter( 'get_the_archive_title', function ( $title ) {

    if( is_category() ) {
        $title = single_cat_title( '', false );
    }

    if ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }

    return $title;

});

/**
 * Theme logo
 */
if (!function_exists('villenoir_logo')) :
    function villenoir_logo() {
        // Displays H1 or DIV based on whether we are on the home page or not (SEO)
        $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
        //var_dump(_get_field('gg_display_image_logo', 'option'));

        if ( _get_field('gg_display_image_logo', 'option', false) ) {    
            
            $class="graphic";
            $main_logo_style = $sticky_logo_style = $mobile_logo_style = '';

            //Theme Logo
            $default_logo = array(
                'url' => get_template_directory_uri() . '/images/logo.png',
                'width' => '230',
                'height' => '64',
            );

            $logo        = _get_field('gg_logo_image', 'option', $default_logo);
            
            //If logo is not yet imported display default logo
            if ($logo == false)
                $logo = $default_logo;

            $margins   = _get_field('gg_logo_margins', 'option');
            $max_width = _get_field('gg_logo_max_width', 'option');

            if ($margins) {
                $main_logo_style .= ' margin: '.esc_attr($margins).';';
            }
            if ($max_width) {
                $main_logo_style .= ' max-width: '.esc_attr($max_width).';';
            }

            //Normal logo
            echo '<a class="brand" href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'" rel="home">'. "\n";
            echo '<img style="'.$main_logo_style.'" class="brand" src="'.esc_url($logo['url']).'" width="'.esc_attr($logo['width']).'" height="'.esc_attr($logo['height']).'" alt="'.esc_attr( get_bloginfo('name','display')).'" />'. "\n";
            echo '</a>'. "\n";

            $sticky_logo      = _get_field('gg_sticky_logo_image', 'option');
            $sticky_margins   = _get_field('gg_sticky_logo_margins', 'option');
            $sticky_max_width = _get_field('gg_sticky_logo_max_width', 'option');

            if ($sticky_margins) {
                $sticky_logo_style .= ' margin: '.esc_attr($sticky_margins).';';
            }
            if ($sticky_max_width) {
                $sticky_logo_style .= ' max-width: '.esc_attr($sticky_max_width).';';
            }

            //Sticky logo
            if ( $sticky_logo ) {
                echo '<a class="sticky-brand" href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'" rel="home">'. "\n";
                echo '<img style="'.$sticky_logo_style.'" src="'.esc_url($sticky_logo['url']).'" width="'.esc_attr($sticky_logo['width']).'" height="'.esc_attr($sticky_logo['height']).'" alt="'.esc_attr( get_bloginfo('name','display')).'" />'. "\n";
                echo '</a>'. "\n";
            }

            $mobile_logo      = _get_field('gg_mobile_logo_image', 'option');
            $mobile_margins   = _get_field('gg_mobile_logo_margins', 'option');
            $mobile_max_width = _get_field('gg_mobile_logo_max_width', 'option');

            if ($mobile_margins) {
                $mobile_logo_style .= ' margin: '.esc_attr($mobile_margins).';';
            }
            if ($mobile_max_width) {
                $mobile_logo_style .= ' max-width: '.esc_attr($mobile_max_width).';';
            }

            //Mobile logo
            if ( $mobile_logo ) {
                echo '<a class="mobile-brand" href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'" rel="home">'. "\n";
                echo '<img style="'.$mobile_logo_style.'" src="'.esc_url($mobile_logo['url']).'" width="'.esc_attr($mobile_logo['width']).'" height="'.esc_attr($mobile_logo['height']).'" alt="'.esc_attr( get_bloginfo('name','display')).'" />'. "\n";
                echo '</a>'. "\n";
            }

        } else {
            $class="text site-title"; 
            echo '<'.$heading_tag.' class="'.esc_attr($class).'">';
            echo '<a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'" rel="home">'.get_bloginfo('name').'</a>';
            if ( _get_field('gg_display_tagline', 'option', false) ) {
                echo '<small class="visible-desktop visible-tablet '.esc_attr($class).'">'.get_bloginfo('description').'</small>'. "\n";
            }
            echo '</'.$heading_tag.'>'. "\n";     
        }


    }
endif;

/**
 * Theme secondary navigation
 */
if (!function_exists('villenoir_secondary_navigation')) :
    function villenoir_secondary_navigation() {

        $header_minicart   = _get_field('gg_header_minicart','option', true);
        $header_my_account = _get_field('gg_header_my_account','option', true);
        $header_search     = _get_field('gg_header_search','option', false);
        $lang_menu         = _get_field('gg_lang_menu','option', true);

        if ( villenoir_is_wc_activated() || villenoir_is_wpml_activated() || villenoir_is_polylang_activated()) : ?>
        <ul class="nav navbar-nav secondary-nav">
            
            <?php

            //Hook at the beginning of secondary nav
            do_action('gg_secondary_nav_start');

            if ( $lang_menu === true ) {

                if ( villenoir_is_wpml_activated() ) {
                     // Add WPML language selector  
                    echo '<li class="gg-language-switcher menu-item-has-children">' . villenoir_wpml_language_sel() .'</li>';
                }
           
                if ( villenoir_is_wpml_activated() && class_exists('woocommerce_wpml') ) {
                    // Add currency switcher to menu items  
                    echo '<li class="gg-currency-switcher menu-item-has-children">' . villenoir_currency_switcher() .'</li>';
                }

                //Polylang languages
                if ( villenoir_is_polylang_activated() ) {
                    echo villenoir_polylang_languages();
                }

            }

            //Header minicart
            if ( $header_minicart === true && villenoir_is_wc_activated() ) {
                // Add cart link to menu items
                echo '<li class="gg-woo-mini-cart dropdown">' . villenoir_wc_minicart() .'</li>';
            }

            //Header MyAccount
            if ( $header_my_account === true && villenoir_is_wc_activated() ) {
                echo '<li class="quick-my-account"><a href="'.esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ).'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></a></li>';
            }        

            if ( $header_search === true && villenoir_is_wc_activated() ) {
                // Add search to menu items  
                echo '<li class="gg-header-search">' . villenoir_header_search() .'</li>';
            }

            //Hook at the end of secondary nav
            do_action('gg_secondary_nav_end');

            ?>

        </ul>
        <?php endif; ?>
    <?php }
endif;


if ( ! function_exists( 'villenoir_custom_style' ) ) {
    /**
     * Function that print custom page style
     */
    function villenoir_custom_style() {
        $css = '';
        $gg_row_spacing = _get_field('gg_row_spacing', 'option');
        $gg_css = _get_field('gg_css', 'option');

        //Custom row spacing
        if ($gg_row_spacing) {
            $css .= '
                body .wpb_row:not(.vc_inner),
                body .vc_row.wpb_row:not(.vc_inner),
                body.post-type-archive.wpb-is-on #content,
                body #content,
                body.post-type-archive.wpb-is-on #content,
                body.woocommerce.single-product.wpb-is-on #content {
                    padding: '.$gg_row_spacing.';
                }
            ';
        }

        //Custom CSS
        if ($gg_css) {
            $css .= $gg_css;
        }

        return wp_strip_all_tags($css);
    }
}


if ( ! function_exists( 'villenoir_print_custom_js' ) ) {
    /**
     * Prints out custom css from theme options
     */
    function villenoir_print_custom_js() {
        $custom_js = _get_field('gg_script', 'option');

        if ( ! empty( $custom_js ) ) {
            wp_add_inline_script( 'villenoir-custom', $custom_js );
        }
    }

    add_action( 'wp_enqueue_scripts', 'villenoir_print_custom_js' );
}

/**
 * Footer info module
 */
if (!function_exists('villenoir_footer_info_module')) :
    function villenoir_footer_info_module() { ?>
        <?php 
        $footer_image = _get_field('gg_footer_image', 'option');
        
        if( $footer_image ): ?>
            <div class="footer-image-module">
                <img src="<?php echo esc_url($footer_image['url']); ?>" alt="<?php echo esc_html($footer_image['alt']); ?>" />
            </div>

        <?php endif; ?>

        <?php if (_get_field('gg_footer_text', 'option') != '') : ?>
            <div class="footer-message"> 
                <?php echo wp_kses_post(_get_field('gg_footer_text', 'option')); ?>
            </div>
        <?php endif; ?>    
    <?php }
endif;

/**
 * Footer extras module
 */
if (!function_exists('villenoir_footer_extras')) :
    function villenoir_footer_extras() { ?>
        
        <?php if( _get_field('gg_footer_extras', 'option', true) ) : ?>
        <div class="footer-extras">

            <!-- Begin Footer Navigation -->
            <?php
            if ( _get_field('gg_footer_extras_menu','option', true ) ) :
                wp_nav_menu(
                    array(
                        'theme_location'    => 'footer-menu',
                        'container_class'   => 'gg-footer-menu',
                        'menu_class'        => 'nav navbar-nav',
                        'menu_id'           => 'footer-menu',
                        'fallback_cb'       => false,
                        'depth'             => -1
                    )
                );
            endif;
            ?>
            <!-- End Footer Navigation -->

            <!-- Begin Footer Social -->
            <?php $footer_social = _get_field('gg_footer_extras_social','option',false); ?>

            <?php if ($footer_social) : ?>
            <div class="footer-social">
                <?php echo villenoir_social_icons(true); ?>
            </div>
            <?php endif; ?>
            <!-- End Footer Social -->

            <!-- Copyright -->
            <?php if ( _get_field('gg_footer_extras_copyright', 'option', true) != '') : ?>
            <div class="footer-copyright">
                <?php echo wp_kses_post( _get_field('gg_footer_extras_copyright', 'option', '&copy; 2021 Villenoir. All rights reserved') ); ?>
            </div>    
            <?php endif; ?>


        </div><!-- /footer-extras -->
        <?php endif; ?>

    <?php }
endif;


/*Tribe events function*/

/* Always display map separatly */
add_filter('tribe_events_single_event_the_meta_group_venue', '__return_false');
add_filter('tribe_events_single_event_the_meta_skeleton', '__return_false');

/*Query for search page to display more the 1 result*/
add_filter('posts_orderby', 'group_by_post_type', 10, 2);
function group_by_post_type($orderby, $query) {
    global $wpdb;
    if ($query->is_search) {
        return $wpdb->posts . '.post_type DESC';
    }
    // provide a default fallback return if the above condition is not true
    return $orderby;
}

function villenoir_change_wp_search_size($query) {
    if ( $query->is_search )
        $query->query_vars['posts_per_page'] = -1;
    return $query;
}
add_filter('pre_get_posts', 'villenoir_change_wp_search_size');

/*Scroll to top*/
if ( ! function_exists( 'villenoir_scroll_to_top' ) ) {
    /**
     * The footer meta container close
     */
    function villenoir_scroll_to_top() {
        $back_to_top   = _get_field('gg_site_back_to_top','option', true);
        
        if ( !$back_to_top ) return;
        ?>
        <a href="#site-top" class="scrollup">
        <svg class="icon icon-scrollup" id="icon-scrollup" viewBox="0 0 45 45" width="100%" height="100%">
            <g fill="none" fill-rule="evenodd">
                <path d="M22.966 14.75v18.242H22V14.86l-2.317 2.317-.683-.684 3-3v-.26h.261l.232-.233.045.045.045-.045.232.232h.151v.152l3.11 3.11-.683.683-2.427-2.427z" fill="#ffffff"></path>
            </g>
        </svg>
        </a>
    <?php }
}
add_action( 'gg_scroll_to_top', 'villenoir_scroll_to_top' );

/* Wrapper for footer extra content */
function gg_footer_items() {
    do_action( 'gg_side_cart' );
    do_action( 'gg_search_overlay' );
    do_action( 'gg_scroll_to_top' );
    ?>
    
    <?php
}
add_action( 'gg_footer_site_wrapper', 'gg_footer_items' );

function villenoir_sub_menu_toggle($depth) {
    // Add toggle button.
    $output = '<button class="sub-menu-toggle depth-'.$depth.'" aria-expanded="false" onClick="villenoirExpandSubMenu(this)">';
    $output .= '<span class="icon-plus"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z" fill="currentColor"/></svg></span>';
    $output .= '<span class="icon-minus"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6 11h12v2H6z" fill="currentColor"/></svg></span>';
    $output .= '<span class="screen-reader-text">' . esc_html__( 'Open menu', 'villenoir' ) . '</span>';
    $output .= '</button>';
    return $output;
}

function villenoir_add_sub_menu_toggle( $output, $item, $depth, $args ) {
    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
        // Add toggle button.
        $output .= villenoir_sub_menu_toggle($depth);
    }
    return $output;
}
add_filter( 'walker_nav_menu_start_el', 'villenoir_add_sub_menu_toggle', 10, 4 );

/*Social icons*/
if ( ! function_exists( 'villenoir_social_icons' ) ) {
    function villenoir_social_icons( $wrap_in_ul = false ) {
        if ( _get_field('gg_social_icons','option') ) : ?>
            <?php if ($wrap_in_ul) : ?><ul><?php endif; ?>
                <?php
                    while (has_sub_field('gg_social_icons','option')) : //Loop through social icons

                        $s_icon = get_sub_field('gg_select_social_icon','option');
                        $s_link = get_sub_field('gg_social_icon_link','option');

                        if (is_rtl()) {
                            echo '<li><a href="'.esc_url($s_link).'" target="_blank"><i class="'.esc_attr($s_icon).'"></i></a></li>';
                        } else {
                            echo '<li><a href="'.esc_url($s_link).'" target="_blank"><i class="'.esc_attr($s_icon).'"></i></a></li>';
                        }
                        
                    endwhile;
                ?>
            <?php if ($wrap_in_ul) : ?></ul><?php endif; ?>
        <?php endif;
    }
}
function enqueue_custom_scripts() {
    // Deregister default jQuery and register from CDN if necessary
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), null, true);
    wp_enqueue_script('jquery');

    // Enqueue your custom script
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom-script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


