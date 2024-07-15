<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_posts_grid' ) ) {
class WPBakeryShortCode_gg_posts_grid extends WPBakeryShortCode {

   public function __construct() {  
         add_shortcode('posts_grid', array($this, 'gg_posts_grid'));  
   }

   public function gg_posts_grid( $atts, $content = null ) { 

         $output = $badge_title = $link_html = $image = $el_class = $isotope_item = $is_carousel = $carousel_data = $is_unlimited = $carousel_data_html = '';
         extract(shortcode_atts(array(
            'posts_grid_col_select'   => '',
            'posts_grid_no_posts'     => '',
            'posts_grid_terms'        => '',
            'posts_in'                => '',
            'posts_not_in'            => '',
            'orderby'                 => '',
            'order'                   => '',
            'grid_layout_mode'        => 'fitRows',
            'grid_layout_style'       => 'default',
            'el_class'                => '',
            'css_animation'           => '',
            'category_filter'         => '',
            'slides_per_view'         => '1',
            'transition_style'        => 'fade',
            'wrap'                    => '',
            'carousel_nav'             => '',
            'carousel_pag'             => '',
            'carousel_autoplay'        => '',
            'speed'                   => '200',
            'css_animation'           => '',
            'badge_title'             => ''
         ), $atts));

         //Defaults
         global $gg_is_vc;
         $convert_ul = 'ul';
         $convert_li = 'li';

         //Apply columns class based on column selection 
         switch ($posts_grid_col_select) {
            case "4":
               $posts_grid_col_class = 'col-xs-12 col-sm-6 col-md-3';
            break;
            case "3":
               $posts_grid_col_class = 'col-xs-12 col-sm-6 col-md-4';
            break;
            case "2":
               $posts_grid_col_class = 'col-xs-12 col-sm-6 col-md-6';
            break;
            case "1":
               $posts_grid_col_class = 'col-xs-12 col-sm-12 col-md-12';
            break;
         }

         if ( $grid_layout_mode == 'fitRows' || $grid_layout_mode == 'masonry') {
            
            $isotope_item = ' isotope-item';
            $is_carousel = '';
            $convert_ul = 'ul';
            $convert_li = 'li';
            $data_pagination = 'ajax_load';
            
         } else if ( $grid_layout_mode == 'carousel' ) {
          
          //Load carousel

          $isotope_item          = '';
          $is_carousel           = 'gg-slick-carousel';
          $convert_ul            = 'div';
          $convert_li            = 'div';
          $posts_grid_col_class  ='';
          $posts_grid_no_posts   = '-1';
          $posts_grid_pagination = false;
          $data_pagination       = 'none';

          $carousel_data .= ' "slidesToShow": '.$slides_per_view.', ';
          $carousel_data .= ' "arrows": '.($carousel_nav == 'yes' ? 'true' : 'false').', ';
          $carousel_data .= ' "dots": '.($carousel_pag == 'yes' ? 'true' : 'false').', ';
          $carousel_data .= ' "autoplay": '.($carousel_autoplay == 'yes' ? 'true' : 'false').', ';
          $carousel_data .= ' "infinite": true, ';
          $carousel_data .= ' "slidesToScroll": 1, ';
          if (is_rtl()) {
            $carousel_data .= ' "rtl": true, ';
          }
          $carousel_data .= ' "responsive": [{"breakpoint": 600, "settings": {"slidesToShow": 1, "slidesToScroll": 1}}, {"breakpoint": 480, "settings": {"slidesToShow": 1, "slidesToScroll": 1}}] ';

          $carousel_data_html .= 'data-visible-slides="'.$slides_per_view.'" data-slick=\'{ '.$carousel_data.' }\'';

         }

         //Animation
         $css_class = $this->getCSSAnimation($css_animation);
         $css_class .= ' vc-gg-blog-posts';

         //Start the insanity
         $output .= "\n\t".'<div class="'.$css_class.'">';

         if (($grid_layout_mode == 'carousel') && ($slides_per_view == '1') && ($badge_title != '') ) {
          $output .= "\n\t".'<div class="posts-badge">'.$badge_title.'</div>';
         }

         $output .= "\n\t".'<div class="gg_posts_grid">';

         //Grid filter
         if (($category_filter == 'use_category_filter') && ($grid_layout_mode != 'carousel')) {
               
            $output .= "\n\t\t\t\t".'<ul class="gg_filter '.$is_carousel.'">';
            $output .= "\n\t\t\t\t\t".'<li class="active"><a href="#" data-filter="*">'.esc_html__("All", "okthemes-villenoir-shortcodes").'</a></li>';

            $terms = get_terms('category');
            foreach ( $terms as $term ) {
            $output .= "\n\t\t\t\t\t".'<li><a data-filter=".grid-cat-'.$term->slug.'">' . $term->name . ' </a></li>';
            }

            $output .= "\n\t\t\t\t".'</ul>';
         }

         // WP_Query arguments
         $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
         $args = array (
            'taxonomy'               => 'category',
            'posts_per_page'         => $posts_grid_no_posts,
            'orderby'                => $orderby,
            'order'                  => $order,
            'ignore_sticky_posts'    => true,
            'paged' => $paged
         );

         //If posts_grid terms are selected and carousel is not active - use terms
         if (($posts_grid_terms != '') && ($category_filter != 'use_category_filter')) {
            $args['cat'] = $posts_grid_terms;
         }

         $not_in = array();
         if ( $posts_not_in != '' ) {
            $posts_not_in = str_ireplace(" ", "", $posts_not_in);
            $not_in = explode(",", $posts_not_in);
         }

         //exclude current post from query
         if ( $posts_in == '' ) {
            global $post;
            array_push($not_in, $post->ID);
         }
         else if ( $posts_in != '' ) {
            $posts_in = str_ireplace(" ", "", $posts_in);
            $args['post__in'] = explode(",", $posts_in);
         }
         if ( $posts_in == '' || $posts_not_in != '' ) {
            $args['post__not_in'] = $not_in;
         }

         // The Query
         $posts_grid_query = new WP_Query( $args );

         // The Loop
         if ( $posts_grid_query->have_posts() ) {

         $output .= "\n\t".'<'.$convert_ul.' '.$carousel_data_html.' class="el-grid row '.$is_carousel.'" data-layout-mode="'.$grid_layout_mode.'" data-pagination="none">';

         while ( $posts_grid_query->have_posts() ) : $posts_grid_query->the_post();

         //Retrieve variables from the metabox
         global $post; 

         $output .= "\n\t".'<'.$convert_li.' class=" '.$isotope_item.' '.$posts_grid_col_class.' '.villenoir_shortcodes_tax_terms_slug('category').' ">';
         
          ob_start();
          if ($grid_layout_style == 'default') {
            get_template_part( 'parts/post-formats/part', 'vc-default' );
          } elseif ($grid_layout_style == 'default_no_img') {
            get_template_part( 'parts/post-formats/part', 'vc-default-no-img' );
          } else {
            get_template_part( 'parts/post-formats/part', 'vc-overlay' );
          }
          
          $output .= "\n\t".ob_get_contents();  
          ob_end_clean();  

         $output .= "\n\t".'</'.$convert_li.'>';

         endwhile;
         
         $output .= "\n\t".'</'.$convert_ul.'>';

         if (function_exists("pagination")) {
         ob_start(); 
         pagination($posts_grid_query);
         $output .= "\n\t".ob_get_contents(); 
         ob_end_clean();
        }

         } else {
         
         $output .= "\n\t".'<p>No posts found</p>';
         
         }

         wp_reset_postdata();

         
         $output .= "\n\t".'</div>';
         $output .= "\n\t".'</div>';

         return $output;
   }

}// END class WPBakeryShortCode_gg_posts_grid

$WPBakeryShortCode_gg_posts_grid = new WPBakeryShortCode_gg_posts_grid();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_posts_grid' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Posts grid","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('Display grid posts.', 'okthemes-villenoir-shortcodes'),
   "base"              => "posts_grid",
   "icon"              => "gg_vc_icon",
   "weight"            => -50,
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   "params" => array(
      array(
        "type" => "dropdown",
        "heading" => esc_html__("Layout mode", "okthemes-villenoir-shortcodes"),
        "param_name" => "grid_layout_mode",
        "value" => array(
            esc_html__("Grid Fit rows", "okthemes-villenoir-shortcodes") => "fitRows", 
            esc_html__('Grid Masonry', "okthemes-villenoir-shortcodes")  => 'masonry', 
            esc_html__('Carousel', "okthemes-villenoir-shortcodes")      => 'carousel'
          ),
        "description" => esc_html__("Layout template.", "okthemes-villenoir-shortcodes")
      ),
      array(
        "type" => "dropdown",
        "heading" => esc_html__("Layout style", "okthemes-villenoir-shortcodes"),
        "param_name" => "grid_layout_style",
        "value" => array(
            esc_html__("Default (with image)", "okthemes-villenoir-shortcodes")    => "default", 
            esc_html__("Default (without image)", "okthemes-villenoir-shortcodes") => "default_no_img", 
            esc_html__('Overlay', "okthemes-villenoir-shortcodes")                 => 'overlay'
          ),
        "description" => esc_html__("Post style.", "okthemes-villenoir-shortcodes")
      ),
      //Carousel options
      array(
          "type" => "dropdown",
          "heading" => esc_html__("Slides per view", "okthemes-villenoir-shortcodes"),
          "param_name" => "slides_per_view",
          "value" => array(1, 2, 3, 4),
          "description" => esc_html__("Set numbers of slides you want to display at the same time on slider's container for carousel mode.", "okthemes-villenoir-shortcodes"),
          "dependency" => Array('element' => 'grid_layout_mode', 'value' => array('carousel'))
      ),
       array(
        "type" => "checkbox",
        "heading" => esc_html__("Use navigation?","okthemes-villenoir-shortcodes"),
        "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "yes" ),
        "param_name" => "carousel_nav",
        "description" => esc_html__("Show the carousel next/prev arrows","okthemes-villenoir-shortcodes"),
        "dependency" => Array('element' => 'grid_layout_mode', 'value' => array('carousel'))
      ),
      array(
        "type" => "checkbox",
        "heading" => esc_html__("Use pagination?","okthemes-villenoir-shortcodes"),
        "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "yes" ),
        "param_name" => "carousel_pag",
        "description" => esc_html__("Show the carousel dots navigation","okthemes-villenoir-shortcodes"),
        "dependency" => Array('element' => 'grid_layout_mode', 'value' => array('carousel'))
      ),
      array(
        "type" => "checkbox",
        "heading" => esc_html__("Use autoplay?","okthemes-villenoir-shortcodes"),
        "value" => array(esc_html__("Yes, please","okthemes-villenoir-shortcodes") => "yes" ),
        "param_name" => "carousel_autoplay",
        "description" => esc_html__("Make the carousel autoplay","okthemes-villenoir-shortcodes"),
        "dependency" => Array('element' => 'grid_layout_mode', 'value' => array('carousel'))
      ),
      array(
         "type" => "dropdown",
         "heading" => esc_html__("Columns count", "okthemes-villenoir-shortcodes"),
         "param_name" => "posts_grid_col_select",
         "value" => array(1, 2, 3, 4),
         "admin_label" => true,
         "description" => esc_html__("Select columns count.", "okthemes-villenoir-shortcodes"),
         "dependency" => Array('element' => 'grid_layout_mode', 'value' => array('fitRows','masonry'))
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Number of posts","okthemes-villenoir-shortcodes"),
         "param_name" => "posts_grid_no_posts",
         "value" => '9',
         "description" => esc_html__("Insert the number of posts to display. Default: 9","okthemes-villenoir-shortcodes")
      ),
      array(
           "type" => "checkbox",
           "heading" => esc_html__("Display category filter?","okthemes-villenoir-shortcodes"),
           "value" => array(esc_html__("Display category filter","okthemes-villenoir-shortcodes") => "use_category_filter" ),
           "param_name" => "category_filter",
           "dependency" => Array('element' => 'grid_layout_mode', 'value' => array('fitRows','masonry'))
      ),
      array(
         "type" => "gg_taxonomy",
         "taxonomy" => "category",
         "heading" => esc_html__("Posts grid terms", "okthemes-villenoir-shortcodes"),
         "param_name" => "posts_grid_terms",
         "description" => esc_html__("Select posts_grid terms to display. By default it displays posts from all terms.", "okthemes-villenoir-shortcodes"),
         "dependency" => Array('element' => "category_filter", 'is_empty' => true)
      ),
      array(
         "type" => "textfield",
         "heading" => esc_html__("Post IDs", "okthemes-villenoir-shortcodes"),
         "param_name" => "posts_in",
         "description" => esc_html__('Fill this field with posts IDs separated by commas (,) to retrieve only them.', "okthemes-villenoir-shortcodes")
      ),
       array(
         "type" => "textfield",
         "heading" => esc_html__("Exclude Post IDs", "okthemes-villenoir-shortcodes"),
         "param_name" => "posts_not_in",
         "description" => esc_html__('Fill this field with posts IDs separated by commas (,) to exclude them from query.', "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "dropdown",
         "heading" => esc_html__("Order by", "okthemes-villenoir-shortcodes"),
         "param_name" => "orderby",
         "value" => array(
            "Date" => "date",
            "Author" => "author",
            "Title" => "title",
            "Slug" => "name",
            "Date modified" => "modified",
            "ID" => "id"
         ),
         "description" => esc_html__("Select how to sort retrieved posts.", "okthemes-villenoir-shortcodes")
      ),
      array(
         "type" => "dropdown",
         "heading" => esc_html__("Order way", "okthemes-villenoir-shortcodes"),
         "param_name" => "order",
         "value" => array(
            "Descending" => "desc",
            "Ascending" => "asc"
         ),
         "description" => esc_html__("Designates the ascending or descending order.", "okthemes-villenoir-shortcodes")
      ),
      $add_css_animation_extended
   )
) );
}

?>