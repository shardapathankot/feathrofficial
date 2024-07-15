<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_Contact_Us' ) ) {
class WPBakeryShortCode_gg_Widget_Contact_Us extends WPBakeryShortCode {

   public function __construct() {  
         add_shortcode('widget_contact_us', array($this, 'gg_widget_contact_us'));  
   }

   public function gg_widget_contact_us( $atts, $content = null ) { 

         $output = $title = $company = $address = $phone = $fax = $email = '';
         extract(shortcode_atts(array(
             'title'        => '',
             'address'  => '',
             'phone'  => '',
             'fax'     => '',
             'email' => '',
             'extra_class' => ''
         ), $atts));

         
         $output = '<div class="vc_widget vc_widget_contact_us '.$extra_class.'">';
         $type = 'villenoir_Contact_Widget';
         $args = array();

         ob_start();
         the_widget( $type, $atts, $args );
         $output .= ob_get_clean();

         $output .= '</div>';

         return $output;
   }

   }// END class WPBakeryShortCode_gg_Widget_Contact_Us

   $WPBakeryShortCode_gg_Widget_Contact_Us = new WPBakeryShortCode_gg_Widget_Contact_Us();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_Widget_Contact_Us' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Widget: Contact us", "okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Display address, phone, fax, email', 'okthemes-villenoir-shortcodes'),
   "base"              => "widget_contact_us",
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
         "type" => "textarea",
         "heading" => esc_html__("Address", "okthemes-villenoir-shortcodes"),
         "param_name" => "address",
         "admin_label" => true,
         "description" => esc_html__("Insert address here", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Phone", "okthemes-villenoir-shortcodes"),
         "param_name" => "phone",
         "description" => esc_html__("Insert phone here", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Fax", "okthemes-villenoir-shortcodes"),
         "param_name" => "fax",
         "description" => esc_html__("Insert fax here", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Email", "okthemes-villenoir-shortcodes"),
         "param_name" => "email",
         "description" => esc_html__("Insert email here", "okthemes-villenoir-shortcodes")
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