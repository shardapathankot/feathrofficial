<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_featured_icon' ) ) {
	class WPBakeryShortCode_gg_featured_icon extends WPBakeryShortCode {

	   public function __construct() {  
			 add_shortcode('featured_icon', array($this, 'gg_featured_icon'));  
	   }

	   public function gg_featured_icon( $atts, $content = null ) { 

			 $output = $margin_style = $style = $icon_box_style_start = $icon_box_style_end = $icon_size_css = $icon_color_css = $featured_title = $image = $align_center = $icon_box_css = $icon_box_back = $icon_border_style = '';
			 $icon = $color = $new_color = $align = $el_class = $custom_color = $link = $background_style = $background_color = $type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypoicons = $icon_linecons = $css_animation = $read_more_link = $featured_icon_check = '';
			 extract(shortcode_atts(array(
				 'featured_title'      => '',
				 'featured_desc'       => '',
				 'link'                => '',
				 'featured_icon'       => '',
				 'read_more'           => '',
				 'read_more_link'      => '',
				 'align'               => 'pull-left',
				 'icon_box'            => '',
				 'icon_box_color'      => '',
				 'el_class'            => '',
				 'css_animation'       => '',
				 'icon_size'           => 'normal',
				 'icon_box_style'      => '',
				 'icon_color'          => '',
				 'icon_border'         => '',
				 'featured_icon_check' => '',
				 'type'                => 'fontawesome',
				 'icon_fontawesome'    => 'fa fa-adjust',
				 'icon_openiconic'     => '',
				 'icon_typicons'       => '',
				 'icon_entypoicons'    => '',
				 'icon_linecons'       => '',
				 'icon_entypo'         => '',
				 'css'                 => '',
				 'style'               => 'style_1',
				 'replace_color'       => '',
				 'custom_color'       => ''
			 ), $atts));

			$class = $this->getExtraClass( $el_class );
			$css_class = $class;
			$css_class .= $this->getCSSAnimation( $css_animation );
			$css_class .= $style;
			
			// Enqueue needed icon font.
			vc_icon_element_fonts_enqueue( $type ); 

			$url = vc_build_link( $link );
			$read_more_url = vc_build_link( $read_more_link );
			$has_style = false;

			if ($replace_color) {
			  $new_color = 'style="color:'.$replace_color.';"';
			}


			$output .= "\n\t".'<div class="featured-icon-box '.esc_attr( $css_class ).'">';

			if ($featured_icon_check == 'use_featured_icon') {
			$output .= "\n\t".'<div class="vc_icon_element vc_icon_element-outer vc_icon_element-align-pull-left">';
			$output .= "\n\t".'<div class="vc_icon_element-inner vc_icon_element-size-md">';
			$output .= "\n\t".'<span class="vc_icon_element-icon '.esc_attr( ${"icon_" . $type} ).'" '.( $custom_color ? 'style="color:' . esc_attr( $custom_color ) . ' !important"' : '' ).'></span>';
			
			  if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
				$output .= "\n\t".'<a class="vc_icon_element-link" href="' . esc_url( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '"></a>';
			  }

			$output .= "\n\t".'</div></div>';

			}

			if ( strlen( $url['url'] ) > 0 ) {
			  $output .= "\n\t".'<h3 '.$new_color.'><a '.$new_color.' href="' . esc_url( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '">'.esc_html($featured_title).'</a></h3>';
			} else {
			  $output .= "\n\t".'<h3 '.$new_color.' >'.esc_html($featured_title).'</h3>';
			}

			if ( strlen( $featured_desc ) > 0 ) {
			  $output .= "\n\t". '<div class="clearfix"></div><div class="featured-icon-desc" '.$new_color.'>'. wp_kses_post( $featured_desc ).'</div>';
			}

			if ($read_more == 'use_read_more') {
			  if ( strlen( $read_more_link ) > 0 && strlen( $read_more_url['url'] ) > 0 ) {
				  if ($style == 'style_1') {
					$output .= "\n\t".'<a class="btn btn-default" href="' . esc_url( $read_more_url['url'] ) . '" title="' . esc_attr( $read_more_url['title'] ) . '" target="' . ( strlen( $read_more_url['target'] ) > 0 ? esc_attr( $read_more_url['target'] ) : '_self' ) . '">' . esc_attr( $read_more_url['title'] ) . '</a>';
				  } else {
					$output .= "\n\t".'<a class="more-link" '.$new_color.' href="' . esc_url( $read_more_url['url'] ) . '" title="' . esc_attr( $read_more_url['title'] ) . '" target="' . ( strlen( $read_more_url['target'] ) > 0 ? esc_attr( $read_more_url['target'] ) : '_self' ) . '">' . esc_attr( $read_more_url['title'] ) . '</a>';
				  }
				  
				}
			}
			
			$output .= "\n\t".'</div>';

			return $output;
			 
	   }
	   
	}// END class WPBakeryShortCode_gg_featured_icon

	$WPBakeryShortCode_gg_featured_icon = new WPBakeryShortCode_gg_featured_icon();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_featured_icon' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name" => esc_html__("Icon box","okthemes-villenoir-shortcodes"),
   "description" => esc_html__('Box with title, description and icon', 'okthemes-villenoir-shortcodes'),
   "base" => "featured_icon",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   'admin_enqueue_js' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/custom-vc.js'),
   "category" => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),

   "params" => array(
	  array(
		'type' => 'dropdown',
		'heading' => esc_html__( 'Style', 'okthemes-villenoir-shortcodes' ),
		'value' => array(
		  esc_html__( 'Style 1', 'okthemes-villenoir-shortcodes' ) => 'style_1',
		  esc_html__( 'Style 2', 'okthemes-villenoir-shortcodes' ) => 'style_2',
		  esc_html__( 'Style 3', 'okthemes-villenoir-shortcodes' ) => 'style_3',
		),
		'param_name' => 'style',
	  ),
	  array(
		 "type" => "textfield",
		 "heading" => esc_html__("Title","okthemes-villenoir-shortcodes"),
		 "param_name" => "featured_title",
		 "admin_label" => true,
		 "description" => esc_html__("Insert title here","okthemes-villenoir-shortcodes")
	  ),
	  array(
		"type" => "vc_link",
		"heading" => esc_html__("URL (Link)", "okthemes-villenoir-shortcodes"),
		"param_name" => "link",
		"description" => esc_html__("Insert the link.", "okthemes-villenoir-shortcodes"),
		"dependency" => Array('element' => "featured_title", 'not_empty' => true)
	  ),
	  array(
		 "type" => "textarea",
		 "heading" => esc_html__("Description","okthemes-villenoir-shortcodes"),
		 "param_name" => "featured_desc",
		 "description" => esc_html__("Insert short description here","okthemes-villenoir-shortcodes"),
		 "dependency" => Array('element' => "featured_title", 'not_empty' => true)
	  ),

	  array(
		 "type" => "checkbox",
		 "heading" => esc_html__("Use an Read more link?","okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "use_read_more" ),
		 "param_name" => "read_more",
		 "dependency" => Array('element' => "featured_desc", 'not_empty' => true)
	  ),

	  array(
		"type" => "vc_link",
		"heading" => esc_html__("Read more URL (Link)", "okthemes-villenoir-shortcodes"),
		"param_name" => "read_more_link",
		"description" => esc_html__("Insert the link.", "okthemes-villenoir-shortcodes"),
		"dependency" => Array('element' => "read_more", 'value' => array('use_read_more'))
	  ),

	  array(
		'type' => 'colorpicker',
		'heading' => esc_html__( 'Overwrite default colors', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'replace_color',
		'description' => esc_html__( 'Set the new color.', 'okthemes-villenoir-shortcodes' )
	  ),

	  array(
		 "type" => "checkbox",
		 "heading" => esc_html__("Use an icon?","okthemes-villenoir-shortcodes"),
		 "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "use_featured_icon" ),
		 "param_name" => "featured_icon_check"
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
		"dependency" => Array('element' => "featured_icon_check", 'value' => array('use_featured_icon'))
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
		'type' => 'colorpicker',
		'heading' => esc_html__( 'Custom Icon Color', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'custom_color',
		'description' => esc_html__( 'Select custom icon color.', 'okthemes-villenoir-shortcodes' ),
		"dependency" => Array('element' => "featured_icon_check", 'value' => array('use_featured_icon'))
	  ),
	  array(
	  'type' => 'textfield',
	  'heading' => esc_html__( 'Extra class name', 'okthemes-villenoir-shortcodes' ),
	  'param_name' => 'el_class',
	  'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'okthemes-villenoir-shortcodes' )
	  ),
	  $add_css_animation_extended,
	  array(
		'type' => 'css_editor',
		'heading' => esc_html__( 'Css', 'okthemes-villenoir-shortcodes' ),
		'param_name' => 'css',
		'group' => esc_html__( 'Design options', 'okthemes-villenoir-shortcodes' ),
		),

   ),
  //'js_view'  => 'okthemes-villenoir-shortcodesVcFeaturedIconView',
) );
}
?>