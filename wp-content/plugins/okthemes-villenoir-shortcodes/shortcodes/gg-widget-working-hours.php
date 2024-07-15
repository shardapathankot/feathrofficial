<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_Working_Hours' ) ) {
class WPBakeryShortCode_gg_Widget_Working_Hours extends WPBakeryShortCode {

   public function __construct() {  
         add_shortcode('widget_working_hours', array($this, 'gg_widget_working_hours'));  
   }

   public function gg_widget_working_hours( $atts, $content = null ) { 

         $output = $title = $monday_friday = $saturday = $sunday = $other_details = '';
         extract(shortcode_atts(array(
             'title'         => '',
             'monday_friday' => '',
             'saturday'      => '',
             'sunday'        => '',
             'other_details' => '',
             'extra_class'   => ''
         ), $atts));

         
         $output = '<div class="vc_widget vc_widget_working_hours '.$extra_class.'">';
         $type = 'villenoir_Working_Hours_Widget';
         $args = array();

         ob_start();
         the_widget( $type, $atts, $args );
         $output .= ob_get_clean();

         $output .= '</div>';

         return $output;
   }

}// END class WPBakeryShortCode_gg_Widget_Working_Hours

$WPBakeryShortCode_gg_Widget_Working_Hours = new WPBakeryShortCode_gg_Widget_Working_Hours();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_Working_Hours' ) ) { 

if ( function_exists( 'vc_map' ) ) {


vc_map( array(
   "name"              => esc_html__("Widget: Working Hours", "okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Display the working hours', 'okthemes-villenoir-shortcodes'),
   "base"              => "widget_working_hours",
   "weight"            => -50,
   "icon"              => "gg_vc_icon",
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
      array(
         "type" => "textfield",
         "heading" => esc_html__("Title", "okthemes-villenoir-shortcodes"),
         "param_name" => "title",
         "description" => esc_html__("Insert title here", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Monday - Friday", "okthemes-villenoir-shortcodes"),
         "param_name" => "monday_friday",
         "admin_label" => true,
         "description" => esc_html__("Insert the hours for monday to friday", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Saturday", "okthemes-villenoir-shortcodes"),
         "param_name" => "saturday",
         "description" => esc_html__("Insert the hours for Saturday", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Sunday", "okthemes-villenoir-shortcodes"),
         "param_name" => "sunday",
         "description" => esc_html__("Insert the hours for Sunday", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textarea",
         "heading" => esc_html__("Other details", "okthemes-villenoir-shortcodes"),
         "param_name" => "other_details",
         "description" => esc_html__("Insert other details here", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Extra class", "okthemes-villenoir-shortcodes"),
         "param_name" => "extra_class",
         "description" => esc_html__("Insert an extra class to style the widget differently. This widget has already a class styled for dark background: contact_widget_dark ", "okthemes-villenoir-shortcodes")
      ),

   )
) );
}

?>