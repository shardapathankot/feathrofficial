<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_infoboxes' ) ) {
class WPBakeryShortCode_gg_infoboxes extends WPBakeryShortCode {

   public function __construct() {  
		 add_shortcode('infoboxes', array($this, 'gg_infoboxes'));  
   }


   public function gg_infoboxes( $atts, $content = null ) { 

		$infotitle = $title_color = $description = $description_color = $description_color_style = $title_color_style = $align = '';

		extract(shortcode_atts(array(
				'infotitle'         => '',
				'title_color'       => '',
				'description_color' => '',
				'align'             => 'left',
				'css'               => '',
		), $atts));

		if ($title_color != '') {
		  $title_color_style = 'style="color: '.$title_color.';"';
		}

		if ($description_color != '') {
		  $description_color_style = 'style="color: '.$description_color.';"';
		}
	   
		$output = "\n\t".'<div class="gg-infobox ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '" style="text-align:'.$align.';">';
		$output .= "\n\t\t".'<p class="subtitle" '.$title_color_style.'>'.$infotitle.'</p>';
		$output .= "\n\t\t".'<div class="description" '.$description_color_style.'>' . wpb_js_remove_wpautop( $content, true ) . '</div>';
		$output .= "\n\t".'</div> ';

		return $output;
		 
   }
}// END class WPBakeryShortCode_gg_infoboxes

$WPBakeryShortCode_gg_infoboxes = new WPBakeryShortCode_gg_infoboxes();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_infoboxes' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Infobox","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('A infobox box module', 'okthemes-villenoir-shortcodes'),
   "base"              => "infoboxes",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
	array(
		 "type" => "textfield",
		 "heading" => esc_html__("Title","okthemes-villenoir-shortcodes"),
		 "param_name" => "infotitle",
		 "admin_label" => true,
		 "description" => esc_html__("Insert title here","okthemes-villenoir-shortcodes")
	  ),
	array(
		"type" => "colorpicker",
		"heading" => esc_html__("Title color", "okthemes-villenoir-shortcodes"),
		"param_name" => "title_color"
	  ),
	array(
	  'type' => 'textarea_html',
	  'holder' => 'div',
	  'heading' => esc_html__( 'Message text', 'okthemes-villenoir-shortcodes' ),
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
		'type' => 'css_editor',
		'heading' => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'css',
		'group' => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
	)
   )
) );
}

?>