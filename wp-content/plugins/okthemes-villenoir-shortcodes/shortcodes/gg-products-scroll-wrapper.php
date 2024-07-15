<?php
if(!function_exists('products_scroll_gallery')){
    
    function products_scroll_gallery( $atts, $content = null){

        $atts =  extract(shortcode_atts( 
            array( 
                'el_class' => '',
        ),$atts )) ;

        $output = '';
        $output .= "\n\t".'<div class="gg_posts_grid_scroll '.$el_class.'" data-scroll-container >';
        $output .= "\n\t".'<div class="content-scroll">';
        $output .= "\n\t".'<div class="scroll-gallery">';

        //The content
        $output .= do_shortcode( $content );

        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    add_shortcode( 'products_scroll_gallery' , 'products_scroll_gallery' );
}

if(!function_exists('products_hero')){
    
    function products_hero($atts, $content = null){
        
        $output = $product_hero_title = '';
         extract(shortcode_atts(array(
            'products_hero_toptitle'    => '',
            'products_hero_title_1'     => '',
            'products_hero_title_2'     => '',
            'products_hero_title_3'     => '',
            'products_hero_description' => '',
            'products_hero_link'        => '',
            'image'                     => '',
            'css'                     => '',
         ), $atts));

        $unique_id = rand();

        $img_id = preg_replace('/[^\d]/', '', $image);
        $button = vc_build_link( $products_hero_link );

        if ($img_id > 0) {
            $attachment_url = wp_get_attachment_url($img_id , 'full');
            $alt_text = get_post_meta($img_id, '_wp_attachment_image_alt', true);
            $thumbnail = ' <img class="wp-post-image " src="'.esc_url($attachment_url).'" alt="'.$alt_text.'" /> ';          
        }

        //The content
        //$output  = "\n\t".'<div class="gallery-product-hero-wrapper">';
        $output  = "\n\t".'<div class="gallery-product-hero ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '">';

        if ($products_hero_toptitle) {
            $output .= "\n\t".'<span class="product-hero-toptitle" data-scroll="" data-scroll-speed="0.5">'.$products_hero_toptitle.'</span>';
        }

        if ($products_hero_title_1) {
            $products_hero_title_1 = '<span class="title-line-1" data-scroll="" data-scroll-speed="-0.5">'.$products_hero_title_1.'</span>';
        }
        if ($products_hero_title_2) {
            $products_hero_title_2 = '<span class="title-line-2" data-scroll="" data-scroll-speed="0.5">'.$products_hero_title_2.'</span>';
        }
        if ($products_hero_title_3) {
            $products_hero_title_3 = '<span class="title-line-3" data-scroll="" data-scroll-speed="4">'.$products_hero_title_3.'</span>';
        }

        if ($products_hero_title_1 || $products_hero_title_2 || $products_hero_title_3 ) {
        $product_hero_title .= '<h1>'. $products_hero_title_1.$products_hero_title_2.$products_hero_title_3.'</h1>';
        }

        $output .= "\n\t".$product_hero_title;

        if ($products_hero_description) {
            $output .= "\n\t".'<div class="product-hero-description">'.$products_hero_description.'</div>';
        }

        if ( strlen( $products_hero_link ) > 0 && strlen( $button['url'] ) > 0 ) {
            $output .= "\n\t".'<a class="product-hero-button" href="' . esc_url( $button['url'] ) . '" title="' . esc_attr( $button['title'] ) . '" target="' . ( strlen( $button['target'] ) > 0 ? esc_attr( $button['target'] ) : '_self' ) . '">' . esc_attr( $button['title'] ) . '</a>';
        }


        $output .= "\n\t".'</div>';
        //$output .= "\n\t".'</div>';

        return $output;
    }

    add_shortcode( 'products_hero' , 'products_hero' );
}

if ( function_exists( 'vc_map' ) ) {
// Parent container
vc_map( array(
    'name'                    => esc_html__( 'Products scroll gallery' , 'okthemes-villenoir-shortcodes' ),
    'base'                    => 'products_scroll_gallery',
    'icon'                    => 'icon-wpb-row',
    "icon"                    => "gg_vc_icon",
    "weight"                  => -50,
    'admin_enqueue_css'       => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
    'description'             => esc_html__( 'Products scroll gallery (horizontal)', 'okthemes-villenoir-shortcodes' ),
    "category"                => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
    'as_parent'               => array('only' => 'products_hero, products_scroll'), 
    'content_element'         => true,
    'show_settings_on_create' => true,
    'params'                  => array(

                //BEGIN ADDING PARAMS
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra class name', 'villenoir-shortcodes' ),
                    'param_name' => 'el_class',
                    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'villenoir-shortcodes' ),
                ),
                //END ADDING PARAMS

    ),
    "js_view" => 'VcColumnView'
) );
}

// A must for container functionality, replace timeline_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')) {
    if ( ! class_exists( 'WPBakeryShortCode_Products_Scroll_Gallery' ) ) {
        class WPBakeryShortCode_Products_Scroll_Gallery extends WPBakeryShortCodesContainer {
        }
    }   
}

// Nested Element
vc_map( array(
    'name'            => esc_html__('Products hero', 'okthemes-villenoir-shortcodes'),
    'base'            => 'products_hero',
    'description'     => esc_html__( 'Products hero item', 'okthemes-villenoir-shortcodes' ),
    'icon'            => 'icon-wpb-row',
    'content_element' => true,
    'as_child'        => array('only' => 'products_scroll_gallery'), // Use only|except attributes to limit parent (separate multiple values with comma)
    'params'          => array(
                
                //BEGIN ADDING PARAMS
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Toptitle","okthemes-villenoir-shortcodes"),
                    "param_name" => "products_hero_toptitle",
                    "admin_label" => true,
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title (line #1)","okthemes-villenoir-shortcodes"),
                    "param_name" => "products_hero_title_1",
                    "admin_label" => true,
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title (line #2)","okthemes-villenoir-shortcodes"),
                    "param_name" => "products_hero_title_2",
                    "admin_label" => true,
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title (line #3)","okthemes-villenoir-shortcodes"),
                    "param_name" => "products_hero_title_3",
                    "admin_label" => true,
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => esc_html__( 'Description', 'okthemes-villenoir-shortcodes' ),
                    'param_name' => 'products_hero_description',
                ),
                array(
                     "type" => "attach_image",
                     "heading" => esc_html__("Featured image", "okthemes-villenoir-shortcodes"),
                     "param_name" => "image",
                     "value" => "",
                     "description" => esc_html__("Select image from media library.", "okthemes-villenoir-shortcodes")
                ),
                array(
                    "type" => "vc_link",
                    "heading" => esc_html__("Button", "okthemes-villenoir-shortcodes"),
                    "param_name" => "products_hero_link",
                ),
                array(
                    'type'       => 'css_editor',
                    'heading'    => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
                    'param_name' => 'css',
                    'group'      => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
                )

                //END ADDING PARAMS
    ),
) );

// Replace timeline_inner_item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode'))
{
    if ( ! class_exists( 'WPBakeryShortCode_Products_Hero' ) ) {
        class WPBakeryShortCode_Products_Hero extends WPBakeryShortCode {
        }
    }
}
