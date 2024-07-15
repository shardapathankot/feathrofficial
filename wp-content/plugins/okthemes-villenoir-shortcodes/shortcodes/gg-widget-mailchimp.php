<?php

if ( class_exists('MC4WP_Form_Widget')  || ! class_exists( 'WPBakeryShortCode_gg_Widget_MailChimp' )) {

class WPBakeryShortCode_gg_Widget_MailChimp extends WPBakeryShortCode {

   public function __construct() {  
         add_shortcode('widget_mailchimp', array($this, 'gg_widget_mailchimp'));  
   }

   public function gg_widget_mailchimp( $atts, $content = null ) { 

         $output = $title = '';
         extract(shortcode_atts(array(
             'title'        => '',
             'extra_class' => '',
             'css' => '',
			    'css_animation' => '',
         ), $atts));

         $css_class = $css_animation.' '.$extra_class;
         
         $output = '<div class="vc_widget vc_widget_mailchimp '.$css_class.' '.esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ).'">';
         $type = 'MC4WP_Form_Widget';
         $args = array();
         $atts = array(
            'title' => '',
        );

         ob_start();
         the_widget( $type, $atts, $args );
         $output .= ob_get_clean();

         $output .= '</div>';

         return $output;
   }
}// END class WPBakeryShortCode_gg_Widget_MailChimp

$WPBakeryShortCode_gg_Widget_MailChimp = new WPBakeryShortCode_gg_Widget_MailChimp();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_MailChimp' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Widget: MailChimp", "okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Display a MailChimp newsletter form', 'okthemes-villenoir-shortcodes'),
   "base"              => "widget_mailchimp",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
      array(
         "type" => "textfield",
         "heading" => esc_html__("Extra class", "okthemes-villenoir-shortcodes"),
         "param_name" => "extra_class",
         "description" => esc_html__("Insert an extra class to style the widget differently. This widget has already a class styled for dark background: contact_widget_dark ", "okthemes-villenoir-shortcodes")
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