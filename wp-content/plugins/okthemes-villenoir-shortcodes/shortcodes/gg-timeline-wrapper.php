<?php
if(!function_exists('timeline_item_output')){
    
    function timeline_item_output( $atts, $content = null){

        $atts =  extract(shortcode_atts( 
            array( 
            'timeline_style'             => 'horizontal',
        ),$atts )) ;

        $output = '';
        $output .= '<section id="cd-timeline" class="cd-container '.$timeline_style.'">';

        //The content
        $output .= do_shortcode( $content );

        $output .= '</section>';

        return $output;
    }

    add_shortcode( 'timeline_item' , 'timeline_item_output' );
}

if(!function_exists('timeline_inner_item_output')){
    
    function timeline_inner_item_output($atts, $content = null){
        
        $output = $timeline_list_item = $header_style = $title = $subtitle = '';
         extract(shortcode_atts(array(
            'title'       => '',
            'date'        => '',
            'description' => '',
            'image'       => '',
            'css_animation' => '',
         ), $atts));


        $css_class = $css_animation;
        $unique_id = rand();

        $img_id = preg_replace('/[^\d]/', '', $image);

        if ($img_id > 0) {
            $attachment_url = wp_get_attachment_url($img_id , 'full');
            $alt_text = get_post_meta($img_id, '_wp_attachment_image_alt', true);
            $thumbnail = ' <img class="wp-post-image " src="'.esc_url($attachment_url).'" alt="'.$alt_text.'" /> ';          
        }

        //The content
        $output  = "\n\t".'<div class="cd-timeline-block">';

        $output .= "\n\t\t".'<div class="cd-timeline-img cd-picture kd-animated '.$css_class.'">';
        $output .= "\n\t\t".'<img src="'.get_template_directory_uri().'/images/cd-icon-location.svg" alt="Location">';
        $output .= "\n\t\t".'</div>';

        $output .= "\n\t\t".'<div class="cd-timeline-content kd-animated '.$css_class.'">';
        $output .= "\n\t\t".$thumbnail;
        $output .= "\n\t\t".'<div class="cd-timeline-content-wrapper">';
        $output .= "\n\t\t".'<p class="cd-title">'.$title.'</p>';
        $output .= "\n\t\t".'<p>'.$description.'</p>';
        $output .= "\n\t\t".'<h4 class="cd-date">'.$date.'</h4>';
        $output .= "\n\t\t".'</div>';
        $output .= "\n\t\t".'</div>';

        $output .= "\n\t".'</div>';

        return $output;
    }

    add_shortcode( 'timeline_inner_item' , 'timeline_inner_item_output' );
}

if ( function_exists( 'vc_map' ) ) {
// Parent container
vc_map( array(
    'name'                    => esc_html__( 'Timeline' , 'okthemes-villenoir-shortcodes' ),
    'base'                    => 'timeline_item',
    'icon'                    => 'icon-wpb-row',
    "icon"                    => "gg_vc_icon",
    "weight"                  => -50,
    'admin_enqueue_css'       => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
    'description'             => esc_html__( 'Timeline container', 'okthemes-villenoir-shortcodes' ),
    "category"                => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
    'as_parent'               => array('only' => 'timeline_inner_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    'content_element'         => true,
    'show_settings_on_create' => true,
    'params'                  => array(

                //BEGIN ADDING PARAMS
                array(
                      "type" => "dropdown",
                      "heading" => esc_html__("Timeline style", "okthemes-villenoir-shortcodes"),
                      "param_name" => "timeline_style",
                      "value" => array(esc_html__("Horizontal", "okthemes-villenoir-shortcodes") => "horizontal", esc_html__("Vertical", "okthemes-villenoir-shortcodes") => "vertical"),
                      "description" => esc_html__("Choose the timeline style", "okthemes-villenoir-shortcodes")
                ),

                //END ADDING PARAMS

    ),
    "js_view" => 'VcColumnView'
) );

// Nested Element
vc_map( array(
    'name'            => esc_html__('Timeline Items', 'okthemes-villenoir-shortcodes'),
    'base'            => 'timeline_inner_item',
    'description'     => esc_html__( 'Insert an item', 'okthemes-villenoir-shortcodes' ),
    'icon'            => 'icon-wpb-row',
    'content_element' => true,
    'as_child'        => array('only' => 'timeline_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
    'params'          => array(
                
                //BEGIN ADDING PARAMS
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Date","okthemes-villenoir-shortcodes"),
                    "param_name" => "date",
                    "admin_label" => true,
                    "description" => esc_html__("Insert the date/year here","okthemes-villenoir-shortcodes")
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Title","okthemes-villenoir-shortcodes"),
                    "param_name" => "title",
                    "admin_label" => true,
                    "description" => esc_html__("Insert the title here","okthemes-villenoir-shortcodes")
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => esc_html__( 'Description', 'okthemes-villenoir-shortcodes' ),
                    'param_name' => 'description',
                    "description" => esc_html__("Insert the description here","okthemes-villenoir-shortcodes")
                ),
                array(
                     "type" => "attach_image",
                     "heading" => esc_html__("Featured image", "okthemes-villenoir-shortcodes"),
                     "param_name" => "image",
                     "value" => "",
                     "description" => esc_html__("Select image from media library.", "okthemes-villenoir-shortcodes")
                ),
                $add_css_animation_extended
                //END ADDING PARAMS
    ),
) );
}

// A must for container functionality, replace timeline_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer'))
{
    if ( ! class_exists( 'WPBakeryShortCode_Timeline_Item' ) ) {
        class WPBakeryShortCode_Timeline_Item extends WPBakeryShortCodesContainer {
        }
    }   
}

// Replace timeline_inner_item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode'))
{
    if ( ! class_exists( 'WPBakeryShortCode_Timeline_Inner_Item' ) ) {
        class WPBakeryShortCode_Timeline_Inner_Item extends WPBakeryShortCode {
        }
    }
}


?>