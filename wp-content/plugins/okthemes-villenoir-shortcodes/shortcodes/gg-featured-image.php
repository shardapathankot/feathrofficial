<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_featured_image' ) ) {
class WPBakeryShortCode_gg_featured_image extends WPBakeryShortCode {

   public function __construct() {  
		 add_shortcode('featured_image', array($this, 'gg_featured_image'));  
   }

   public function gg_featured_image( $atts, $content = null ) { 

		 $output = $featured_link_open = $featured_link_close = $img_style_class = $featured_box_style_cls = $featured_title = $featured_toptitle = $image = $thumbnail = $css_tablet = $css_mobile = '';
		 extract(shortcode_atts(array(
			'featured_toptitle'        => '', 
			'featured_title'           => '',
			 'featured_desc'           => '',
			 'featured_link'           => '',
			 'featured_box_style'      => 'normal',
			 'featured_box_text_align' => 'left',
			 'image'                   => $image,
			 'img_size'                => 'fullsize',
			 'img_style'               => 'default',
			 'customsize_width'        => '',
			 'customsize_height'       => '',
			 'el_class'                => '',
			 'css_animation'           => '',
			 'css'                     => '',
			 'css_tablet'              => '',
			 'css_mobile'              => ''
		 ), $atts));

		 //Generate mobile custom_css
		$css = villenoir_generate_dummy_css_for_mobile_check($css, $css_mobile, $css_tablet);
		$output .= villenoir_generate_custom_css($css_tablet, $css, 'tablet');
		$output .= villenoir_generate_custom_css($css_mobile, $css, 'mobile');

		 $img_id = preg_replace('/[^\d]/', '', $image);

		 if ($img_id > 0) {
			$attachment_url = wp_get_attachment_url($img_id , 'full');
			$alt_text = get_post_meta($img_id, '_wp_attachment_image_alt', true);
			if ($img_size !== 'fullsize') {
				$thumbnail = ' <img class="wp-post-image '.$img_style.'" src="'.villenoir_aq_resize( $img_id, $customsize_width, $customsize_height, true, true ).'" alt="'.$alt_text.'" /> ';
			} else {
				$thumbnail = ' <img class="wp-post-image '.$img_style.'" src="'.esc_url($attachment_url).'" alt="'.esc_html($alt_text).'" /> ';          
			}
		}

		$css_class = $this->getCSSAnimation($css_animation);
		$css_class .= ' text-align-'.$featured_box_text_align;

		if ($featured_box_style == 'overlay') {
			$featured_box_style_cls .= ' sadie';
		} else {
			$featured_box_style_cls .= '';
		}

		$url = vc_build_link( $featured_link );
		
		if ( strlen( $featured_link ) > 0 && strlen( $url['url'] ) > 0 ) {
			$featured_link_open = '<a href="' . esc_url( $url['url'] ) . '" title="' . esc_attr( $url['title'] ) . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '">';
			$featured_link_close = '</a>';
			$featured_title_html = $featured_link_open.$featured_title.$featured_link_close;
			$thumbnail = $featured_link_open.$thumbnail.$featured_link_close;
		} else {
			$featured_title_html = $featured_title;
		}
		 
		$output = "\n\t".'<div class="featured-image-box '.$css_class.' ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '">';

		$output .= "\n\t\t".'<figure class="'.$featured_box_style_cls.' '.$img_style.'">';
		$output .= "\n\t\t\t".$thumbnail;
		$output .= "\n\t\t".'<figcaption>';

		if ($featured_box_style == 'overlay') {
			$output .= "\n\t\t\t".$featured_link_open.$featured_link_close;
			$output .= "\n\t\t\t".'<span class="villenoir-icon icon-next-article-arrow">
			<svg version="1.1" width="54" height="6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 54.7 6" style="enable-background:new 0 0 54.7 6;" xml:space="preserve">
			<polygon points="0,3.5 50.6,3.5 50.6,6 54.7,3 50.6,0 50.6,2.5 0,2.5 "></polygon>
			</svg></span>';

			$output .= "\n\t\t\t".'<span class="toptitle">'.$featured_toptitle.'</span>';
			$output .= "\n\t\t\t".'<h4>'.$featured_title.'</h4>';
		} else {
			$output .= "\n\t\t\t".'<span class="toptitle">'.$featured_toptitle.'</span>';
			$output .= "\n\t\t\t".'<h4>'.$featured_title_html.'</h4>'; 
		}

		if ( $featured_desc ) {
			$output .= "\n\t\t\t".'<p>'.$featured_desc.'</p>';
		}

		$output .= "\n\t\t".'</figcaption>';
		$output .= "\n\t\t".'</figure>';
		 
		$output .= "\n\t".'</div> ';

		return $output;
		 
   }
}// END class WPBakeryShortCode_gg_featured_image

$WPBakeryShortCode_gg_featured_image = new WPBakeryShortCode_gg_featured_image();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_featured_image' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Image box","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Image box with title and description.', 'okthemes-villenoir-shortcodes'),
   "base"              => "featured_image",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   'admin_enqueue_js'  => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/custom-vc.js'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
	  array(
			"type" => "dropdown",
			"heading" => esc_html__("Box style", "okthemes-villenoir-shortcodes"),
			"param_name" => "featured_box_style",
			"value" => array(esc_html__("Normal (text under the image)", "okthemes-villenoir-shortcodes") => "normal", esc_html__("Overlay (text on the image)", "okthemes-villenoir-shortcodes") => "overlay"),
			"description" => esc_html__("Choose the image size", "okthemes-villenoir-shortcodes")
	  ),
	  array(
			"type" => "dropdown",
			"heading" => esc_html__("Box text align", "okthemes-villenoir-shortcodes"),
			"param_name" => "featured_box_text_align",
			"value" => array(esc_html__("Left", "okthemes-villenoir-shortcodes") => "left", esc_html__("Right", "okthemes-villenoir-shortcodes") => "right", esc_html__("Center", "okthemes-villenoir-shortcodes") => "center" ),
			"description" => esc_html__("Choose the image size", "okthemes-villenoir-shortcodes")
	  ),
	  array(
		"type" => "textfield",
		"heading" => esc_html__("Top title","okthemes-villenoir-shortcodes"),
		"param_name" => "featured_toptitle",
		"admin_label" => true,
	 ), 
	  array(
		 "type" => "textfield",
		 "heading" => esc_html__("Title","okthemes-villenoir-shortcodes"),
		 "param_name" => "featured_title",
		 "admin_label" => true,
	  ),
	  array(
		 "type" => "textarea",
		 "heading" => esc_html__("Description","okthemes-villenoir-shortcodes"),
		 "param_name" => "featured_desc",
	  ),
	  array(
		"type" => "vc_link",
		"heading" => esc_html__("URL (Link)", "okthemes-villenoir-shortcodes"),
		"param_name" => "featured_link",
		"description" => esc_html__("Insert the link.", "okthemes-villenoir-shortcodes"),
		"dependency" => Array('element' => "featured_title", 'not_empty' => true)
	  ),
	  array(
		 "type" => "attach_image",
		 "heading" => esc_html__("Featured image", "okthemes-villenoir-shortcodes"),
		 "param_name" => "image",
		 "value" => "",
		 "description" => esc_html__("Select image from media library.", "okthemes-villenoir-shortcodes")
	  ),
	  //Image size
	  array(
			"type" => "dropdown",
			"heading" => esc_html__("Image size", "okthemes-villenoir-shortcodes"),
			"param_name" => "img_size",
			"value" => array(esc_html__("Full size", "okthemes-villenoir-shortcodes") => "fullsize", esc_html__("Custom size", "okthemes-villenoir-shortcodes") => "customsize"),
			"description" => esc_html__("Choose the image size", "okthemes-villenoir-shortcodes")
	  ),
	  array(
		  "type" => "dropdown",
		  "heading" => esc_html__("Image style", "okthemes-villenoir-shortcodes"),
		  "param_name" => "img_style",
		  "value" => $img_style_arr,
		  "std" => "default",
		  "description" => esc_html__("Choose the image style", "okthemes-villenoir-shortcodes")
	  ),
	  array(
			"type" => "textfield",
			"heading" => esc_html__("Custom size - width", "okthemes-villenoir-shortcodes"),
			"param_name" => "customsize_width",
			"description" => esc_html__("Insert the width of the image", "okthemes-villenoir-shortcodes"),
			"dependency" => Array('element' => "img_size", 'value' => array('customsize'))
	  ),
	  array(
			"type" => "textfield",
			"heading" => esc_html__("Custom size - height", "okthemes-villenoir-shortcodes"),
			"param_name" => "customsize_height",
			"description" => esc_html__("Insert the height of the image", "okthemes-villenoir-shortcodes"),
			"dependency" => Array('element' => "img_size", 'value' => array('customsize'))
	  ),
	  $add_css_animation_extended,
	  $css_editor_array_extended_desktop,
	  $css_editor_array_extended_tablet,
	  $css_editor_array_extended_mobile
   ),
   'js_view'  => 'okthemes-villenoir-shortcodesVcFeaturedImageView',
) );
}

?>