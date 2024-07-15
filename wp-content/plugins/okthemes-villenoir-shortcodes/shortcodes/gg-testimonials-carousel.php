<?php
if(!function_exists('gg_testimonials_carousel')){
    
    function gg_testimonials_carousel( $atts, $content = null){

        $atts =  extract(shortcode_atts( 
            array( 
                'el_class'          => '',
                'carousel_nav'      => '',
                'carousel_pag'      => '',
                'carousel_autoplay' => '',
                'carousel_columns'  => '',
        ),$atts )) ;

        $output = $carousel_data = $carousel_data_html = '';

        $carousel_data .= ' "slidesToShow": '.$carousel_columns.', ';
        $carousel_data .= ' "arrows": '.($carousel_nav == 'yes' ? 'true' : 'false').', ';
        $carousel_data .= ' "dots": '.($carousel_pag == 'yes' ? 'true' : 'false').', ';
        $carousel_data .= ' "autoplay": '.($carousel_autoplay == 'yes' ? 'true' : 'false').', ';
        $carousel_data .= ' "infinite": true, "adaptiveHeight": true, ';
        $carousel_data .= ' "slidesToScroll": 1, ';
        if (is_rtl()) {
        $carousel_data .= ' "rtl": true, ';
        }
        $carousel_data .= ' "responsive": [{"breakpoint": 1024, "settings": {"slidesToShow": '.$carousel_columns.', "slidesToScroll": 1}}, {"breakpoint": 600, "settings": {"slidesToShow": 2, "slidesToScroll": 1}}, {"breakpoint": 480, "settings": {"slidesToShow": 1, "slidesToScroll": 1}}] ';

        $carousel_data_html .= ' data-slick=\'{ '.$carousel_data.' }\' ';

        $output .= "\n\t".'<div class="gg-testimonials-carousel gg-slick-carousel '.$el_class.' columns-'.$carousel_columns.'" '.$carousel_data_html.'>';

        //The content
        $output .= do_shortcode( $content );

        $output .= '</div>';

        return $output;
    }

    add_shortcode( 'gg_testimonials_carousel' , 'gg_testimonials_carousel' );
}

if ( function_exists( 'vc_map' ) ) {
// Parent container
vc_map( array(
    'name'                    => esc_html__( 'Testimonials carousel' , 'okthemes-villenoir-shortcodes' ),
    'base'                    => 'gg_testimonials_carousel',
    'icon'                    => 'icon-wpb-row',
    "icon"                    => "gg_vc_icon",
    "weight"                  => -50,
    'admin_enqueue_css'       => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
    'description'             => esc_html__( 'Testimonials carousel', 'okthemes-villenoir-shortcodes' ),
    "category"                => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
    'as_parent'               => array('only' => 'blockquote'), 
    'content_element'         => true,
    'show_settings_on_create' => true,
    'params'                  => array(

                //BEGIN ADDING PARAMS
                array(
                    "type" => "checkbox",
                    "heading" => esc_html__("Use navigation?","okthemes-villenoir-shortcodes"),
                    "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "yes" ),
                    "param_name" => "carousel_nav",
                    "description" => esc_html__("Show the carousel next/prev arrows","okthemes-villenoir-shortcodes"),
                  ),
                  array(
                    "type" => "checkbox",
                    "heading" => esc_html__("Use pagination?","okthemes-villenoir-shortcodes"),
                    "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "yes" ),
                    "param_name" => "carousel_pag",
                    "description" => esc_html__("Show the carousel dots navigation","okthemes-villenoir-shortcodes"),
                  ),
                  array(
                    "type" => "checkbox",
                    "heading" => esc_html__("Use autoplay?","okthemes-villenoir-shortcodes"),
                    "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "yes" ),
                    "param_name" => "carousel_autoplay",
                    "description" => esc_html__("Make the carousel autoplay","okthemes-villenoir-shortcodes"),
                  ),
                  array(
                     "type" => "dropdown",
                     "heading" => esc_html__("Columns count", "okthemes-villenoir-shortcodes"),
                     "param_name" => "carousel_columns",
                     "value" => array(1,2,3),
                     "admin_label" => true,
                     "description" => esc_html__("Select columns count.", "okthemes-villenoir-shortcodes"),
                  ),
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
    if ( ! class_exists( 'WPBakeryShortCode_GG_Testimonials_Carousel' ) ) {
        class WPBakeryShortCode_GG_Testimonials_Carousel extends WPBakeryShortCodesContainer {
        }
    }   
}