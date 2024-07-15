<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_clubbox' ) ) {
class WPBakeryShortCode_gg_clubbox extends WPBakeryShortCode {

   public function __construct() {  
		 add_shortcode('clubbox', array($this, 'gg_clubbox'));  
   }


   public function gg_clubbox( $atts, $content = null ) { 

		$infotitle = $title_color = $description = $description_color = $description_color_style = $title_color_style = $top_title_color_style = $price_color_style = $price_affix_color_style = $align = $thumbnail = '';

		extract(shortcode_atts(array(
                'top_title'         => '',
                'top_title_color'   => '',
                'title'             => '',
                'title_color'       => '',
                'description_color' => '',
                'align'             => 'left',
                'price'             => '',
                'price_color'       => '',
                'price_affix'       => '',
                'price_affix_color' => '',
                'link'              => '',
                'image'             => '',
                'css'               => '',
		), $atts));

        $url = vc_build_link( $link );
        $img_id = preg_replace('/[^\d]/', '', $image);

         if ($img_id > 0) {
            $attachment_url = wp_get_attachment_url($img_id , 'full');
            $alt_text = get_post_meta($img_id, '_wp_attachment_image_alt', true);
            $thumbnail = ' <img class="wp-post-image" src=" '.esc_url( $attachment_url ).' " alt="'.$alt_text.'" /> ';
        }

        if ($top_title_color != '') {
            $top_title_color_style = 'style="color: '.$top_title_color.';"';
        }

		if ($title_color != '') {
            $title_color_style = 'style="color: '.$title_color.';"';
		}

        if ($price_color != '') {
            $price_color_style = 'style="color: '.$price_color.';"';
        }
        if ($price_affix_color != '') {
            $price_affix_color_style = 'style="color: '.$price_affix_color.';"';
        }

		if ($description_color != '') {
            $description_color_style = 'style="color: '.$description_color.';"';
		}

        //Change btn style in relation with the theme style
        $btn_style = 'btn-primary';
        if (_get_field('gg_theme_style', 'option','light') == 'dark') {
            $btn_style = 'btn-secondary';
        }
	   
		$output = "\n\t".'<div class="gg-clubbox ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '" style="text-align:'.$align.';">';
		$output .= "\n\t\t".'<div class="club-image">'.$thumbnail.'</div>';
        $output .= "\n\t\t".'<div class="gg-clubbox-content gg-'.$align.'">';
        $output .= "\n\t\t".'<span class="club-top-title" '.$top_title_color_style.'>'.$top_title.'</span>';
        $output .= "\n\t\t".'<h4 class="club-title" '.$title_color_style.'>'.$title.'</h4>';
        $output .= "\n\t\t".'<div class="club-description" '.$description_color_style.'>' . wpb_js_remove_wpautop( $content, true ) . '</div>';
        $output .= "\n\t\t".'<p class="club-price" '.$price_color_style.'>'.$price.' <span class="club-price-affix" '.$price_affix_color_style.'>'.$price_affix.'</span></p>';

        if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
            $output .= "\n\t".'<div class="clearfix"></div><a class="club-link button '.$btn_style.'" href="' . esc_url( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '">' . esc_attr( $url['title'] ) . '</a>';
        }
        $output .= "\n\t".'</div>';
		$output .= "\n\t".'</div>';

		return $output;
		 
   }
}// END class WPBakeryShortCode_gg_clubbox

$WPBakeryShortCode_gg_clubbox = new WPBakeryShortCode_gg_clubbox();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_clubbox' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Club box","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('A club box module', 'okthemes-villenoir-shortcodes'),
   "base"              => "clubbox",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
	array(
        "type"             => "textfield",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Top title","okthemes-villenoir-shortcodes"),
        "param_name"       => "top_title",
        "description"      => esc_html__("E.G.: Club","okthemes-villenoir-shortcodes")
    ),
    array(
        "type"             => "colorpicker",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Top title color", "okthemes-villenoir-shortcodes"),
        "param_name"       => "top_title_color"
    ),
    array(
        "type"             => "textfield",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Title","okthemes-villenoir-shortcodes"),
        "param_name"       => "title",
        "admin_label"      => true,
        "description"      => esc_html__("E.G.: Silver","okthemes-villenoir-shortcodes")
	),
	array(
        "type"             => "colorpicker",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Title color", "okthemes-villenoir-shortcodes"),
        "param_name"       => "title_color"
	),
    array(
        "type"             => "textfield",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Price","okthemes-villenoir-shortcodes"),
        "param_name"       => "price",
        "description"      => esc_html__("E.G.: $65-$85","okthemes-villenoir-shortcodes"),
        "admin_label"      => true,
    ),
    array(
        "type"             => "colorpicker",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Price color", "okthemes-villenoir-shortcodes"),
        "param_name"       => "price_color"
    ),
    array(
        "type"             => "textfield",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Price affix","okthemes-villenoir-shortcodes"),
        "param_name"       => "price_affix",
        "description"      => esc_html__("E.G.: /shipment","okthemes-villenoir-shortcodes"),
    ),
    array(
        "type"             => "colorpicker",
        "edit_field_class" => "vc_col-sm-6 vc_column",
        "heading"          => esc_html__("Price affix color", "okthemes-villenoir-shortcodes"),
        "param_name"       => "price_affix_color"
    ),
	array(
        'type' => 'textarea_html',
        'holder' => 'div',
        'heading' => esc_html__( 'Description', 'okthemes-villenoir-shortcodes' ),
        'param_name' => 'content',
        'value' => esc_html__( 'I am message box. Click edit button to change this text.', 'okthemes-villenoir-shortcodes' ),
	),
	array(
		"type" => "colorpicker",
		"heading" => esc_html__("Description color", "okthemes-villenoir-shortcodes"),
		"param_name" => "description_color"
	  ),
	array(
		 "type" => "dropdown",
		 "heading" => esc_html__("Align", "okthemes-villenoir-shortcodes"),
		 "param_name" => "align",
		 "value" => array(esc_html__("Align left", "okthemes-villenoir-shortcodes") => "left", esc_html__("Align right", "okthemes-villenoir-shortcodes") => "right", esc_html__("Align center", "okthemes-villenoir-shortcodes") => "center"),
		 "description" => esc_html__("Set the alignment", "okthemes-villenoir-shortcodes")
	),
    array(
        "type" => "vc_link",
        "heading" => esc_html__("URL (Link)", "okthemes-villenoir-shortcodes"),
        "param_name" => "link",
        "description" => esc_html__("Insert the link. If used with Memberships plugin please select the corresponding club product.", "okthemes-villenoir-shortcodes"),
    ),
    array(
        "type" => "attach_image",
        "heading" => esc_html__("Featured image", "okthemes-villenoir-shortcodes"),
        "param_name" => "image",
        "value" => "",
    ),
	array(
		'type' => 'css_editor',
		'heading' => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'css',
		'group' => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
	)
   )
) );
}

?>