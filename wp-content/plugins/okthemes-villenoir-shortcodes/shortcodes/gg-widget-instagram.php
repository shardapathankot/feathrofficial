<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_Instagram' ) ) {
class WPBakeryShortCode_gg_Widget_Instagram extends WPBakeryShortCode {

   public function __construct() {  
         add_shortcode('widget_instagram', array($this, 'gg_widget_instagram'));  
   }

   public function gg_widget_instagram( $atts, $content = null ) { 

         $output = $title = $username = $posts = '';
         extract(shortcode_atts(array(
               'title'     => '',
               'link'      => 'Follow us',
               'number'    => 6,
               'followers' => '',
               'css' => '',
			      'css_animation' => '',
         ), $atts));

         $css_class = $css_animation;

         $output = '<div class="vc_widget vc_widget_instagram '.$css_class.' '.esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ).'">';
         $type = 'villenoir_Instagram_Widget';
         $args = array();

         ob_start();
         the_widget( $type, $atts, $args );
         $output .= ob_get_clean();

         $output .= '</div>';

         return $output;
   }
}// END class WPBakeryShortCode_gg_Widget_Instagram

$WPBakeryShortCode_gg_Widget_Instagram = new WPBakeryShortCode_gg_Widget_Instagram();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_Instagram' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Widget: Instagram", "okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Display Instagram posts.', 'okthemes-villenoir-shortcodes'),
   "base"              => "widget_instagram",
   "weight"            => -50,
   "icon"              => "gg_vc_icon",
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
      array(
         "type" => "textfield",
         "heading" => esc_html__("Title", "okthemes-villenoir-shortcodes"),
         "param_name" => "title",
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Link", "okthemes-villenoir-shortcodes"),
         "param_name" => "link",
         "description" => esc_html__("Insert your link title here. E.G. Follow us", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Number of posts to display", "okthemes-villenoir-shortcodes"),
         "param_name" => "number",
         "description" => esc_html__("Default: 6 posts", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Number of followers", "okthemes-villenoir-shortcodes"),
         "param_name" => "followers",
         "description" => esc_html__("Insert number of followers.E.g. 24k", "okthemes-villenoir-shortcodes")
      ),
      $add_css_animation_extended,
      array(
         'type'       => 'css_editor',
         'heading'    => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
         'param_name' => 'css',
         'group'      => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
      )
   )
) );
}

?>