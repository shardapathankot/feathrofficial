<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_title_subtitle' ) ) {
class WPBakeryShortCode_gg_title_subtitle extends WPBakeryShortCode {

   public function __construct() {  
		 add_shortcode('title_subtitle', array($this, 'gg_title_subtitle'));  
   }

   public function gg_title_subtitle( $atts, $content = null ) { 

		 $output = $title = $title_html = $style = $padding = $subtitle = $font_size = $t_font_style = $t_font_transform = $t_font_white_space = $t_font_color = $s_font_color = $animation_delay =  $css = $css_tablet = $css_mobile =  '';
		 extract(shortcode_atts(array(
			 'title'                => '',
			 'title_type'           => 'h1',
			 'subtitle'             => '',
			 'add_subtitle'         => '',
			 'custom_font_size'     => '',
			 'custom_font_size_px'  => '',
			 'title_font_color'     => '',
			 'subtitle_font_color'  => '',
			 'padding'              => '',
			 'el_class'             => '',
			 'align'                => 'left',
			 'add_underline'        => '',
			 'underline_color'      => '',
			 'margin_bottom'        => '',
			 'font_style'           => 'normal',
			 'font_transform'       => '',
			 'font_white_space'     => '',
			 'title_special_style'  => '',
			 'css' => '',
			 'css_mobile' => '',
			 'css_tablet' => '',
			 'css_animation' => '',
		 ), $atts));

		$el_class = $this->getExtraClass( $el_class );
        $css_animation = $this->getExtraClass( $css_animation );
		$css_class = vc_shortcode_custom_css_class( $css, ' ' ) . vc_shortcode_custom_css_class( $css_tablet, ' ' ) . vc_shortcode_custom_css_class( $css_mobile, ' ' ) . $el_class . $css_animation;
	 
		 if( $padding != '' ) {
			$style .= ' padding: '.(preg_match('/(px|em|\%|pt|cm)$/', $padding) ? $padding : $padding.'px').';';
		 }
		 if( $margin_bottom != '' ) {
			$style .= ' margin-bottom:'.$margin_bottom.'px;';
		 }

		 if( $align != '') {

			if (is_rtl() && $align == 'left')
				$style .= ' text-align: right;';
			elseif (is_rtl() && $align == 'right')
				$style .= ' text-align: else;';
			else
				$style .= ' text-align: '.$align.';';
		 }

		 if( $font_style != '' ) {
			$t_font_style = ' font-style: '.$font_style.';';
		 }

		 if( $font_transform != '' ) {
			$t_font_transform = ' text-transform: '.$font_transform.';';
		 }
		 if( $font_white_space != '' ) {
			$t_font_white_space = ' white-space: '.$font_white_space.';';
		 }

		 if ( $custom_font_size == 'use_custom_font_size' ) {
			$font_size = ' font-size:'.$custom_font_size_px.'px; line-height: normal;';
		 }

		 if (!empty($title_font_color)) {
			$t_font_color = ' color:'.$title_font_color.'; ';
		 }

		 if (!empty($subtitle_font_color)) {
			$s_font_color = ' color:'.$subtitle_font_color.'; ';
		 }

		 if ($title_special_style == 'line_over_text') {
			$css_class .= ' '.$title_special_style;
		 }

		 $title_style_array = 'style="'.$font_size.$t_font_color.$t_font_style.$t_font_transform.$t_font_white_space.'"';
		 $subtitle_style_array = 'style="'.$s_font_color.'"'; 
		 
		 $output  .= "\n\t".'<div class="title-subtitle-box '.esc_attr( $css_class ).'" style="'.$style.'">';
		 //Subtitle
		 if( $add_subtitle == 'use_subtitle' && $subtitle != '' ) {
			$output .= "\n\t\t".'<p  '.$subtitle_style_array.'>'.$subtitle.'</p>';
		 }
		 //Title
		 if( $title != '' ) {
			$output .= "\n\t\t".'<'.$title_type.' '.$title_style_array.'>'.$title.'</'.$title_type.'>';
		 }

		 if ( $add_underline == 'use_underline' ) {
			$output .= "\n\t\t".'<hr class="has-underline" '.($underline_color !== '' ? 'style="background: '.$underline_color.'"' : '').' />';
		 }

		  $output .= "\n\t".'</div> ';

		 return $output;
		 
   }

}// END class WPBakeryShortCode_gg_title_subtitle

$WPBakeryShortCode_gg_title_subtitle = new WPBakeryShortCode_gg_title_subtitle();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_title_subtitle' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Title and Subtitle", "okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Display a title and a subtitle.', 'okthemes-villenoir-shortcodes'),
   "base"              => "title_subtitle",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   'admin_enqueue_js'  => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/custom-vc.js'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
	  array(
		 "type" => "textarea",
		 "heading" => esc_html__("Title", "okthemes-villenoir-shortcodes"),
		 "param_name" => "title",
		 "admin_label" => true,
	  ),
	  array(
		 "type" => "dropdown",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__("Title type", "okthemes-villenoir-shortcodes"),
		 "param_name" => "title_type",
		 "value" => $headings_array,
		 "description" => esc_html__("Choose the heading type", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	  ),

	  array(
		 "type" => "dropdown",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__("Align", "okthemes-villenoir-shortcodes"),
		 "param_name" => "align",
		 "value" => array(esc_html__("Align left", "okthemes-villenoir-shortcodes") => "left", esc_html__("Align right", "okthemes-villenoir-shortcodes") => "right", esc_html__("Align center", "okthemes-villenoir-shortcodes") => "center"),
		 "description" => esc_html__("Set the alignment", "okthemes-villenoir-shortcodes")
	 ),
	  
	  array(
		 "type" => "dropdown",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__("Title font style", "okthemes-villenoir-shortcodes"),
		 "param_name" => "font_style",
		 "value" => array(esc_html__("Normal", "okthemes-villenoir-shortcodes") => "normal", esc_html__("Bold", "okthemes-villenoir-shortcodes") => "bold", esc_html__("Italic", "okthemes-villenoir-shortcodes") => "italic"),
		 "description" => esc_html__("Set the font style", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	 ),
	array(
		 "type" => "dropdown",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__("Title font transform", "okthemes-villenoir-shortcodes"),
		 "param_name" => "font_transform",
		 "value" => array(esc_html__("None", "okthemes-villenoir-shortcodes") => "",esc_html__("Uppercase", "okthemes-villenoir-shortcodes") => "uppercase", esc_html__("Lowercase", "okthemes-villenoir-shortcodes") => "lowercase", esc_html__("Capitalize", "okthemes-villenoir-shortcodes") => "capitalize"),
		 "description" => esc_html__("Set the font transform", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	 ),
	array(
		 "type" => "dropdown",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__("Title white space", "okthemes-villenoir-shortcodes"),
		 "param_name" => "font_white_space",
		 "value" => array(esc_html__("Normal", "okthemes-villenoir-shortcodes") => "normal",esc_html__("Nowrap", "okthemes-villenoir-shortcodes") => "nowrap", esc_html__("Pre", "okthemes-villenoir-shortcodes") => "pre"),
		 "description" => esc_html__("Set the font white space", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	 ),
	array(
		 "type" => "dropdown",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__("Title special style", "okthemes-villenoir-shortcodes"),
		 "param_name" => "title_special_style",
		 "value" => array(
			esc_html__("None", "okthemes-villenoir-shortcodes") => "", 
			esc_html__("Line over text", "okthemes-villenoir-shortcodes") => "line_over_text",
		),
		 "std" => "",
		 "description" => esc_html__("Choose a special title style", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	 ),

	  array(
		 "type" => "colorpicker",
		 "heading" => esc_html__('Title font color', 'okthemes-villenoir-shortcodes'),
		 "param_name" => "title_font_color",
		 "description" => esc_html__("Select the title font color", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	  ),
	  array(
		 "type" => "checkbox",
		 "class" => "",
		 "heading" => esc_html__("Custom font size for title?", "okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Yes, please", "okthemes-villenoir-shortcodes") => "use_custom_font_size" ),
		 "param_name" => "custom_font_size",
		 "dependency" => Array('element' => "title", 'not_empty' => true)
	  ),
	  array(
		 "type" => "textfield",
		 "heading" => esc_html__("Insert the title font size", "okthemes-villenoir-shortcodes"),
		 "param_name" => "custom_font_size_px",
		 "description" => esc_html__("Insert the custom font size in px.", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "custom_font_size", 'value' => array('use_custom_font_size'))
	  ),
	  array(
		 "type" => "checkbox",
		 "heading" => esc_html__("Add toptitle?", "okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Yes, please", "okthemes-villenoir-shortcodes") => "use_subtitle" ),
		 "param_name" => "add_subtitle"
	  ),
	  array(
		 "type" => "textarea",
		 "heading" => esc_html__("Toptitle", "okthemes-villenoir-shortcodes"),
		 "param_name" => "subtitle",
		 "admin_label" => true,
		 "dependency" => Array('element' => "add_subtitle", 'value' => array('use_subtitle'))
	  ),
	  array(
		 "type" => "colorpicker",
		 "heading" => esc_html__('Subtitle font color', 'okthemes-villenoir-shortcodes'),
		 "param_name" => "subtitle_font_color",
		 "description" => esc_html__("Select the subtitle font color", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "add_subtitle", 'value' => array('use_subtitle'))
	  ),
	  array(
		 "type" => "checkbox",
		 "class" => "",
		 "heading" => esc_html__("Add underline?", "okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Yes, please", "okthemes-villenoir-shortcodes") => "use_underline" ),
		 "param_name" => "add_underline"
	  ),
	  array(
		 "type" => "colorpicker",
		 "heading" => esc_html__('Underline color', 'okthemes-villenoir-shortcodes'),
		 "param_name" => "underline_color",
		 "description" => esc_html__("Select the underline color", "okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "add_underline", 'value' => array('use_underline'))
	  ),
	  
	  array(
		 "type" => "textfield",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__('Padding', 'okthemes-villenoir-shortcodes'),
		 "param_name" => "padding",
		 "description" => esc_html__("You can use px, em, %, etc. or enter just number and it will use pixels. ", "okthemes-villenoir-shortcodes")
	  ),
	  array(
		 "type" => "textfield",
		 'edit_field_class' => 'vc_col-sm-6 vc_column',
		 "heading" => esc_html__('Margin bottom', 'okthemes-villenoir-shortcodes'),
		 "param_name" => "margin_bottom",
		 "description" => esc_html__("Insert the margin bottom in pixels. E.g.: 20", "okthemes-villenoir-shortcodes")
	  ),
	  array(
		'type' => 'el_id',
		"edit_field_class" => "vc_col-sm-6 vc_column",
		'heading' => __( 'Element ID', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'el_id',
		'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'okthemes-villenoir-shortcodes' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
	  ),
	  array(
		'type' => 'textfield',
		"edit_field_class" => "vc_col-sm-6 vc_column",
		'heading' => __( 'Extra class name', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'el_class',
		'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'okthemes-villenoir-shortcodes' ),
	  ),
	$add_css_animation_extended,
	$css_editor_array_extended_desktop,
	$css_editor_array_extended_tablet,
	$css_editor_array_extended_mobile
   ),
  'js_view'  => 'okthemes-villenoir-shortcodesVcTitleSubtitleView',
) );
}