<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_counter' ) ) {

	class WPBakeryShortCode_gg_counter extends WPBakeryShortCode {

		public function __construct() {  
			add_shortcode('counter', array($this, 'gg_counter'));  
		}

		public function gg_counter( $atts, $content = null ) { 

			 $output = $type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypoicons = $icon_linecons = $title = $subtitle = $is_box = $background_style = $padding_style = '';
			 $defaults = array(
				 'title'            => '',
				 'subtitle'         => '',
				 'align'            => 'left',
				 'number'           => '',
				 'font_size'        => '60',
				 'font_color'       => '',
				 'css_animation'    => '',
				 'interval'         => '100',
				 'speed'            => '1500',
				 'size'             => '',
				 'add_icon'         => '',
				 'add_box'          => '',
				 'add_box'          => '',
				 'title_color'      => '',
				 'subtitle_color'   => '',
				 'icon_color'       => '',
				 'box_background'   => '',
				 'padding'          => '',
				 'type' => 'fontawesome',
				'icon_fontawesome' => 'fa fa-adjust',
				'icon_openiconic' => '',
				'icon_typicons' => '',
				'icon_entypoicons' => '',
				'icon_linecons' => '',
				'icon_entypo' => '',
			);

			  /** @var array $atts - shortcode attributes */
			$atts = vc_shortcode_attribute_parse( $defaults, $atts );
			extract( $atts );

			 wp_enqueue_script('waypoints');

			 // Enqueue needed icon font.
			vc_icon_element_fonts_enqueue( $type );
			$iconClass = isset( ${"icon_" . $type} ) ? esc_attr( ${"icon_" . $type} ) : 'fa fa-adjust';

			if($css_animation != ""){
			  $clsss_css_animation =  $css_animation;
			} else {
			  $clsss_css_animation =  "";
			}

			$style = '';
			
			if ($font_size != '') {
			  $style .= ' font-size:'.$font_size.'px;';
			}

			if ($font_color != '') {
			  $style .= ' color:'.$font_color.';';
			}

			if ($title != '') {
			  if ($title_color != "") {
				$title_color = 'style="color:'.$title_color.'"';  
			  }
			  $title = '<p '.$title_color.'>'.$title.'</p>';
			}

			if ($add_box == 'use_box') {
			  
			  $is_box = 'is_box';

			  if( $padding != '' ) {
				$padding_style = ' padding: '.(preg_match('/(px|em|\%|pt|cm)$/', $padding) ? $padding : $padding.'px').';';
			  }

			  if( $box_background != '' ) {
				$background_style = 'background:'.$box_background.';';
			  }
			}
			
			$output  = "\n\t".'<div class="counter-holder media '.$clsss_css_animation.' '.$is_box.'" style="text-align:'.$align.'; '.$padding_style.' '.$background_style.' ">';
			 

			if ($add_icon == 'use_icon') {
			$output .= "\n\t".'<div class="vc_icon_element vc_icon_element-outer vc_icon_element-align-'.esc_attr( $align ).'">';
			$output .= "\n\t".'<div class="vc_icon_element-inner vc_icon_element-size-'.esc_attr( $size ).'">';
			$output .= "\n\t".'<span class="vc_icon_element-icon '.esc_attr( $iconClass ).'" '.( $icon_color != '' ? 'style="color:' . esc_attr( $icon_color ) . ' !important"' : '' ).'></span>';
			$output .= "\n\t".'</div></div>';

			}


			 $output .= "\n\t\t\t".'<div class="counter-content media-body">';
			 $output .= "\n\t\t\t\t".'<span style="'.$style.'" class="counter" data-number="'.$number.'" data-interval="'.$interval.'" data-speed="'.$speed.'">'.$number.'</span>';
			 $output .= "\n\t\t\t\t".$title;
			 $output .= "\n\t\t\t".'</div>';
			 $output .= "\n\t".'</div> ';

			 return $output;
			 
		}

	}// END class WPBakeryShortCode_gg_counter

	$WPBakeryShortCode_gg_counter = new WPBakeryShortCode_gg_counter();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_counter' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Counter","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('A counter from 0 to a specified number.', 'okthemes-villenoir-shortcodes'),
   "base"              => "counter",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
	  array(
		 "type" => "textfield",
		 "heading" => esc_html__("Title","okthemes-villenoir-shortcodes"),
		 "param_name" => "title",
		 "admin_label" => true,
		 "description" => esc_html__("Insert the title here","okthemes-villenoir-shortcodes")
	  ),
	  array(
		"type" => "colorpicker",
		"heading" => esc_html__("Title color","okthemes-villenoir-shortcodes"),
		"param_name" => "title_color",
		"dependency" => Array('element' => "title", 'not_empty' => true)
	  ),
	  array(
		"type" => "textfield",
		"heading" => esc_html__("Number", "okthemes-villenoir-shortcodes"),
		"param_name" => "number"
	  ),
	  array(
		"type" => "colorpicker",
		"heading" => esc_html__("Number color", "okthemes-villenoir-shortcodes"),
		"param_name" => "font_color",
		"dependency" => Array('element' => "number", 'not_empty' => true)
	  ),
	  array(
		"type" => "textfield",
		"heading" => esc_html__("Number font size (px)", "okthemes-villenoir-shortcodes"),
		"param_name" => "font_size",
		"value" => '60',
		"dependency" => Array('element' => "number", 'not_empty' => true)
	  ),
	  array(
		 "type" => "checkbox",
		 "heading" => esc_html__("Icon?","okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Use an icon for your counter.","okthemes-villenoir-shortcodes") => "use_icon" ),
		 "param_name" => "add_icon"
	  ),
	  array(
		'type' => 'dropdown',
		'heading' => esc_html__( 'Icon library', 'okthemes-villenoir-shortcodes' ),
		'value' => array(
		  esc_html__( 'Font Awesome', 'okthemes-villenoir-shortcodes' ) => 'fontawesome',
		  esc_html__( 'Open Iconic', 'okthemes-villenoir-shortcodes' ) => 'openiconic',
		  esc_html__( 'Typicons', 'okthemes-villenoir-shortcodes' ) => 'typicons',
		  esc_html__( 'Entypo', 'okthemes-villenoir-shortcodes' ) => 'entypo',
		  esc_html__( 'Linecons', 'okthemes-villenoir-shortcodes' ) => 'linecons',
		),
		'param_name' => 'type',
		'description' => esc_html__( 'Select icon library.', 'okthemes-villenoir-shortcodes' ),
		"dependency" => Array('element' => "add_icon", 'value' => array('use_icon'))
	  ),
	  array(
		'type' => 'iconpicker',
		'heading' => esc_html__( 'Icon', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'icon_fontawesome',
		'value' => 'fa fa-adjust', // default value to backend editor admin_label
		'settings' => array(
		  'emptyIcon' => false, // default true, display an "EMPTY" icon?
		  'iconsPerPage' => 4000, // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
		),
		'dependency' => array(
		  'element' => 'type',
		  'value' => 'fontawesome',
		),
		'description' => esc_html__( 'Select icon from library.', 'okthemes-villenoir-shortcodes' ),
	  ),
	  array(
		'type' => 'iconpicker',
		'heading' => esc_html__( 'Icon', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'icon_openiconic',
		'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
		'settings' => array(
		  'emptyIcon' => false, // default true, display an "EMPTY" icon?
		  'type' => 'openiconic',
		  'iconsPerPage' => 4000, // default 100, how many icons per/page to display
		),
		'dependency' => array(
		  'element' => 'type',
		  'value' => 'openiconic',
		),
		'description' => esc_html__( 'Select icon from library.', 'okthemes-villenoir-shortcodes' ),
	  ),
	  array(
		'type' => 'iconpicker',
		'heading' => esc_html__( 'Icon', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'icon_typicons',
		'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
		'settings' => array(
		  'emptyIcon' => false, // default true, display an "EMPTY" icon?
		  'type' => 'typicons',
		  'iconsPerPage' => 4000, // default 100, how many icons per/page to display
		),
		'dependency' => array(
		  'element' => 'type',
		  'value' => 'typicons',
		),
		'description' => esc_html__( 'Select icon from library.', 'okthemes-villenoir-shortcodes' ),
	  ),
	  array(
		'type' => 'iconpicker',
		'heading' => esc_html__( 'Icon', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'icon_entypo',
		'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
		'settings' => array(
		  'emptyIcon' => false, // default true, display an "EMPTY" icon?
		  'type' => 'entypo',
		  'iconsPerPage' => 4000, // default 100, how many icons per/page to display
		),
		'dependency' => array(
		  'element' => 'type',
		  'value' => 'entypo',
		),
	  ),
	  array(
		'type' => 'iconpicker',
		'heading' => esc_html__( 'Icon', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'icon_linecons',
		'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
		'settings' => array(
		  'emptyIcon' => false, // default true, display an "EMPTY" icon?
		  'type' => 'linecons',
		  'iconsPerPage' => 4000, // default 100, how many icons per/page to display
		),
		'dependency' => array(
		  'element' => 'type',
		  'value' => 'linecons',
		),
		'description' => esc_html__( 'Select icon from library.', 'okthemes-villenoir-shortcodes' ),
	  ),
	  array(
		'type' => 'dropdown',
		'heading' => esc_html__( 'Size', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'size',
		'value' => array(
		  esc_html__( 'Small', 'okthemes-villenoir-shortcodes' ) => 'sm',
		  esc_html__( 'Normal', 'okthemes-villenoir-shortcodes' ) => 'md',
		  esc_html__( 'Large', 'okthemes-villenoir-shortcodes' ) => 'lg',
		  esc_html__( 'Extra Large', 'okthemes-villenoir-shortcodes' ) => 'xl',
		),
		'std' => 'md',
		'description' => esc_html__( 'Icon size.', 'okthemes-villenoir-shortcodes' ),
		"dependency" => Array('element' => "add_icon", 'value' => array('use_icon'))
	  ),
	  array(
		"type" => "colorpicker",
		"heading" => esc_html__("Icon color","okthemes-villenoir-shortcodes"),
		"param_name" => "icon_color",
		"dependency" => Array('element' => "add_icon", 'value' => array('use_icon'))
	  ),
	  array(
		 "type" => "checkbox",
		 "heading" => esc_html__("Box?","okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Display the counter in a box","okthemes-villenoir-shortcodes") => "use_box" ),
		 "param_name" => "add_box"
	  ),
	  array(
		"type" => "colorpicker",
		"heading" => esc_html__("Box background","okthemes-villenoir-shortcodes"),
		"param_name" => "box_background",
		"description" => esc_html__("Choose your background color.", "okthemes-villenoir-shortcodes"),
		"dependency" => Array('element' => "add_box", 'value' => array('use_box'))
	  ),
	  array(
		  "type" => "textfield",
		  "heading" => esc_html__('Box Padding', 'okthemes-villenoir-shortcodes'),
		  "param_name" => "padding",
		  "description" => esc_html__("You can use px, em, %, etc. or enter just number and it will use pixels. ", "okthemes-villenoir-shortcodes"),
		  "dependency" => Array('element' => "add_box", 'value' => array('use_box'))
	  ),
	  array(
		"type" => "textfield",
		"heading" => esc_html__("Refresh interval", "okthemes-villenoir-shortcodes"),
		"param_name" => "interval",
		"value" => '100'
	  ),
	  array(
		"type" => "textfield",
		"heading" => esc_html__("Speed", "okthemes-villenoir-shortcodes"),
		"param_name" => "speed",
		"value" => '1500'
	  ),
	  array(
		 "type" => "dropdown",
		 "heading" => esc_html__("Align", "okthemes-villenoir-shortcodes"),
		 "param_name" => "align",
		 "value" => array(esc_html__("Align left", "okthemes-villenoir-shortcodes") => "left", esc_html__("Align right", "okthemes-villenoir-shortcodes") => "right", esc_html__("Align center", "okthemes-villenoir-shortcodes") => "center"),
		 "description" => esc_html__("Set the alignment", "okthemes-villenoir-shortcodes")
	  ),

	  $add_css_animation_extended,
	  array(
        'type'       => 'css_editor',
        'heading'    => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
        'param_name' => 'css',
        'group'      => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
	  ),

   )
) );

}

?>