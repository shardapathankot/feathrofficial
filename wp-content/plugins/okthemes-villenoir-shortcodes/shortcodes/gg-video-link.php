<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_video_link' ) ) {
class WPBakeryShortCode_gg_video_link extends WPBakeryShortCode {

   	public function __construct() {  
		add_shortcode('video_link', array($this, 'gg_video_link'));  
   	}

   	public function gg_video_link( $atts, $content = null ) { 

		$output = $css_class = $align = $css = $css_animation = $svg_style = '';
		extract(shortcode_atts(array(
			'video_link'        => '',
			'align'             => 'text-align-left',
			'icon_color'        => '',
			'icon_custom_color' => '',
			'icon_size'         => 'icon-size-large',
			'el_class'          => '',
			'css'               => '',
			'css_animation'     => ''
		), $atts));

		$css_class .= $this->getCSSAnimation($css_animation);
		$css_class .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ); 
		$css_class .= ' ' . $align;
		$css_class .= ' ' . $icon_color;
		$css_class .= ' ' . $icon_size;
		$css_class .= ' wow el-to-fade';
		


		if ( $icon_color == 'custom-color' && $icon_custom_color ) {
			$svg_style = '<defs>
		    <style>
		      .custom-color .video-circle-path {
		        fill: '.$icon_custom_color.';
		        fill-rule: evenodd;
		      }

		      .custom-color .video-circle {
		        fill: none;
		        stroke: '.$icon_custom_color.';
		        stroke-width: 1px;
		      }
		    </style>
		  </defs>';
		}

		
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="148" height="148" viewBox="0 0 148 148">'.$svg_style.'
		  <path class="video-circle-path" d="M83,72a1.586,1.586,0,0,1,0,2L66.354,84.64a1.212,1.212,0,0,1-1.714,0V62.354a1.212,1.212,0,0,1,1.714,0Z"/>
		  <circle class="video-circle" cx="74" cy="74" r="72"/>
		</svg>';
		$output .= "\n\t".'<div class="video-button-wrapper '.$css_class.'">';
		$output .= "\n\t".'<a class="video-button" href="'.esc_url($video_link).'" data-lity>'.$svg.'</a>';
		$output .= "\n\t".'</div>';

		return $output;
		 
   	}
}// END class WPBakeryShortCode_gg_video_link

$WPBakeryShortCode_gg_video_link = new WPBakeryShortCode_gg_video_link();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_video_link' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Video link","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Button link with video', 'okthemes-villenoir-shortcodes'),
   "base"              => "video_link",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   'admin_enqueue_js'  => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/custom-vc.js'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
        array(
            'type'       => 'textfield',
            'heading'    => __( 'Video link', 'okthemes-villenoir-shortcodes' ),
            'param_name' => 'video_link',
        ),

        array(
            "type" => "dropdown",
            'edit_field_class' => 'vc_col-sm-6 vc_column',
            "heading" => esc_html__("Icon color", "okthemes-villenoir-shortcodes"),
            "param_name" => "icon_color",
            "value" => $colors_array_extended,
            "std" => "primary",
        ),
        array(
            "type" => "colorpicker",
            'edit_field_class' => 'vc_col-sm-6 vc_column',
            "heading" => esc_html__('Icon custom color', 'okthemes-villenoir-shortcodes'),
            "param_name" => "icon_custom_color",
            "std" => "",
            "dependency" => Array('element' => "icon_color", 'value' => array('custom-color'))
        ),
   		$icon_size_param,
	  	$align_param,
		//Custom design options
		$add_css_animation_extended,
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'okthemes-villenoir-shortcodes' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'okthemes-villenoir-shortcodes' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
		),
    ),
) 
);
}

?>