<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_team_member' ) ) {
class WPBakeryShortCode_gg_team_member extends WPBakeryShortCode {

   public function __construct() {  
		 add_shortcode('team_member', array($this, 'gg_team_member'));  
   }

   public function gg_team_member( $atts, $content = null ) { 

		$output = $css_class = $member_name = $image = '';
		extract(shortcode_atts(array(
                'member_name'     => '',
                'member_position' => '',
                'align'           => 'text-align-left',
                'image'           => $image,
                'img_size'        => 'full',
                'el_class'        => '',
                'css_animation'   => '',
                'css'             => ''
		), $atts));

        $css_class .= vc_shortcode_custom_css_class( $css, ' ' ) . $el_class . $css_animation;
        $css_class .= ' ' .$align;
        
        //Build image
        $img_id = preg_replace( '/[^\d]/', '', $image );
        $img = wpb_getImageBySize( array(
            'attach_id' => $img_id,
            'thumb_size' => $img_size,
            'class' => 'vc_single_image-img',
        ) );     
		 
		$output = "\n\t".'<div class="team-member-box '.$css_class.'">';

		$output .= "\n\t\t".'<figure>';
        $output .= "\n\t\t".'<div class="team-image-wrapper">';
		$output .= "\n\t\t\t".$img['thumbnail'];
        $output .= "\n\t\t".'</div>';

		$output .= "\n\t\t".'<figcaption>';
        
        if ( $member_position ) {
            $output .= "\n\t\t\t".'<div class="member-position">'.esc_html($member_position).'</div> ';
        }
        if ( $member_name ) {
            $output .= "\n\t\t\t".'<h3>'.$member_name.'</h3>';
        }
               

        if ( $content ) {
            $output .= "\n\t\t".'<span class="gg-horizontal-line"></span>';
            $output .= "\n\t\t\t".'<div class="member-description">' . wpb_js_remove_wpautop( $content, true ) . '</div>';
        }
		$output .= "\n\t\t".'</figcaption>';
		$output .= "\n\t\t".'</figure>';
		 
		$output .= "\n\t".'</div> ';

		return $output;
		 
   }
}// END class WPBakeryShortCode_gg_team_member

$WPBakeryShortCode_gg_team_member = new WPBakeryShortCode_gg_team_member();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_team_member' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Team member","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Add a team member', 'okthemes-villenoir-shortcodes'),
   "base"              => "team_member",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   'admin_enqueue_js'  => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/custom-vc.js'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__("Member name","okthemes-villenoir-shortcodes"),
            "param_name" => "member_name",
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Member position","okthemes-villenoir-shortcodes"),
            "param_name" => "member_position",
            "admin_label" => true,
        ),
        array(
            "type" => "attach_image",
            "heading" => esc_html__("Member image", "okthemes-villenoir-shortcodes"),
            "param_name" => "image",
            "value" => "",
            "description" => esc_html__("Select image from media library.", "okthemes-villenoir-shortcodes")
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Image size', 'okthemes-villenoir-shortcodes' ),
            'param_name' => 'img_size',
            'value' => 'full',
            'description' => __( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'okthemes-villenoir-shortcodes' ),
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => esc_html__( 'Description', 'okthemes-villenoir-shortcodes' ),
            'param_name' => 'content',
            'value' => esc_html__( 'I am message box. Click edit button to change this text.', 'okthemes-villenoir-shortcodes' ),
        ),

        $align_param,
        $add_css_animation_extended,
        array(
            'type' => 'el_id',
            'heading' => __( 'Element ID', 'okthemes-villenoir-shortcodes' ),
            'param_name' => 'el_id',
            'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'okthemes-villenoir-shortcodes' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Extra class name', 'okthemes-villenoir-shortcodes' ),
            'param_name' => 'el_class',
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'okthemes-villenoir-shortcodes' ),
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
            'param_name' => 'css',
            'group'      => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
        ),
   ),
   //'js_view'  => 'okthemes-villenoir-shortcodesVcFeaturedImageView',
) );
}

?>