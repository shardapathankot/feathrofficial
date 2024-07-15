<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_products_scroll' ) ) {
class WPBakeryShortCode_gg_products_scroll extends WPBakeryShortCode {

   public function __construct() {  
         add_shortcode('products_scroll', array($this, 'gg_products_scroll'));  
   }

   public function gg_products_scroll( $atts, $content = null ) { 

         $output = $first_frame_text_line_1 = $first_frame_text_line_2 = $first_frame_text_line_3 = $carousel_data = $carousel_data_html = $product_featured = $product_onsale = $product_best_seller = $link_html = $image = $el_class = '';
         extract(shortcode_atts(array(
            'first_frame_text_line_1'      => '',
            'first_frame_text_line_2'      => '',
            'first_frame_text_line_3'      => '',
            'products_scroll_no_posts'   => '',
            'products_scroll_terms'      => '',
            'posts_in'                 => '',
            'posts_not_in'             => '',
            'orderby'                  => '',
            'order'                    => '',
            'meta_key'                 => '',
            'el_class'                 => '',
            'css_animation'            => '',
         ), $atts));
       

        //Defaults
        global $gg_is_vc;

        //If the user does not insert the no of posts display all by default
        if ($products_scroll_no_posts == '') {
          $products_scroll_no_posts = '-1';  
        }

        //Animation
        $css_class = $this->getCSSAnimation($css_animation);
        //Extra class
        $css_class .= $this->getExtraClass( $el_class );

        //Grid filter
        if ( $products_scroll_terms ) {
          $ids = explode( ',', $products_scroll_terms );
          $ids = array_map( 'trim', $ids );
        } else {
            //If post_in display in filter the categories that the post is in
            if ( $posts_in != '' ) {
              $posts_in_arr = explode( ',', $posts_in );
              $posts_in_arr = array_map( 'trim', $posts_in_arr );
              foreach ($posts_in_arr as $posts_in_arr_id) {
                $ids_in[] = wp_get_post_terms($posts_in_arr_id, 'product_cat', array("fields" => "ids"));
                foreach ($ids_in as $key => $value) {
                  $ids[] = implode( ',', $value );
                }
              }
              $ids = implode( ',', $ids );
              $ids = array_unique(explode( ',', $ids ));
            } else {
              $ids = get_terms( 'product_cat', 'fields=ids');
            }
          
        }

        $paged = 1;
        if(get_query_var('paged')) {
          $paged = get_query_var('paged');
        } elseif(get_query_var('page')) {
          $paged = get_query_var('page');
        }
       
        $args = array (
            'post_type'              => 'product',
            'post_status'            => 'publish',
            'posts_per_page'         => $products_scroll_no_posts,
            'orderby'                => $orderby,
            'meta_key'               => $orderby == 'meta_key' ? $meta_key : '',
            'order'                  => $order,
            'ignore_sticky_posts'    => true,
            'paged'                  => $paged,
        );
        
        if (($products_scroll_terms != '')) {
            $args['tax_query'] = array(
              array(
                'taxonomy' => 'product_cat',
                //'field' => 'slug',
                'include_children' => false,
                'terms' => $ids,
              ),
            );
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
        } else if ( $posts_in != '' ) {
            $posts_in = str_ireplace(" ", "", $posts_in);
            $args['post__in'] = explode(",", $posts_in);
        }

        if ( $posts_in == '' || $posts_not_in != '' ) {
            $args['post__not_in'] = $not_in;
        }

        // The Query
        $wc_products_query = new WP_Query( $args );


        // The Loop
        if ( $wc_products_query->have_posts() ) {

          while ( $wc_products_query->have_posts() ) : $wc_products_query->the_post();

            $output .= "\n\t".'<div class="gallery-item-product">';

            ob_start(); 
            wc_get_template_part( 'content', 'product-scroll' );
            $output .= "\n\t".ob_get_contents();
            ob_end_clean();

            $output .= "\n\t".'</div>';
           
          endwhile; 
          wp_reset_postdata();
          

        } else {
         
          $output .= "\n\t".'<p>No posts found</p>';
         
        }

        return $output;
   }

}// END class WPBakeryShortCode_gg_products_scroll

$WPBakeryShortCode_gg_products_scroll = new WPBakeryShortCode_gg_products_scroll();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_products_scroll' ) ) { 

if ( function_exists( 'vc_map' ) ) {

vc_map( array(
   "name"              => esc_html__("Products scroll","okthemes-villenoir-shortcodes"),
   "description"       => esc_html__('List of products with horizontal scroll', 'okthemes-villenoir-shortcodes'),
   "base"              => "products_scroll",
   "weight"            => -50,
   "icon"              => "gg_vc_icon",
   'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
   "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
   'as_child'        => array('only' => 'products_scroll_gallery'),
   "params" => array(

      array(
         "type" => "textfield",
         "heading" => esc_html__("Number of posts","okthemes-villenoir-shortcodes"),
         "param_name" => "products_scroll_no_posts",
         "value" => '-1',
         "description" => esc_html__("Insert the number of posts to display. Leave empty or insert -1 to display all. Default: -1","okthemes-villenoir-shortcodes"),
      ),

      array(
          'type' => 'autocomplete',
          'heading' => esc_html__( 'Categories', 'okthemes-villenoir-shortcodes' ),
          'param_name' => 'products_scroll_terms',
          'settings' => array(
            'multiple' => true,
            'sortable' => true,
          ),
          'group' => esc_html__( 'Data filter', 'okthemes-villenoir-shortcodes' ),
          'description' => esc_html__( 'List of product categories', 'okthemes-villenoir-shortcodes' ),
      ),
      
      array(
         "type" => "textfield",
         "heading" => esc_html__("Post IDs", "okthemes-villenoir-shortcodes"),
         "param_name" => "posts_in",
         "description" => esc_html__('Fill this field with posts IDs separated by commas (,) to retrieve only them.', "okthemes-villenoir-shortcodes"),
         'group' => esc_html__( 'Data filter', 'okthemes-villenoir-shortcodes' ),
      ),
       array(
         "type" => "textfield",
         "heading" => esc_html__("Exclude Post IDs", "okthemes-villenoir-shortcodes"),
         "param_name" => "posts_not_in",
         "description" => esc_html__('Fill this field with posts IDs separated by commas (,) to exclude them from query.', "okthemes-villenoir-shortcodes"),
         'group' => esc_html__( 'Data filter', 'okthemes-villenoir-shortcodes' ),
      ),
      array(
         "type" => "dropdown",
         "heading" => esc_html__("Order by", "okthemes-villenoir-shortcodes"),
         "param_name" => "orderby",
         "value" => array(
            esc_html__( 'Date', 'okthemes-villenoir-shortcodes' ) => 'date',
            esc_html__( 'Order by post ID', 'okthemes-villenoir-shortcodes' ) => 'ID',
            esc_html__( 'Author', 'okthemes-villenoir-shortcodes' ) => 'author',
            esc_html__( 'Title', 'okthemes-villenoir-shortcodes' ) => 'title',
            esc_html__( 'Last modified date', 'okthemes-villenoir-shortcodes' ) => 'modified',
            esc_html__( 'Post/page parent ID', 'okthemes-villenoir-shortcodes' ) => 'parent',
            esc_html__( 'Number of comments', 'okthemes-villenoir-shortcodes' ) => 'comment_count',
            esc_html__( 'Menu order/Page Order', 'okthemes-villenoir-shortcodes' ) => 'menu_order',
            esc_html__( 'Meta value', 'okthemes-villenoir-shortcodes' ) => 'meta_value',
            esc_html__( 'Meta value number', 'okthemes-villenoir-shortcodes' ) => 'meta_value_num',
            // esc_html__('Matches same order you passed in via the 'include' parameter.', 'okthemes-villenoir-shortcodes') => 'post__in'
            esc_html__( 'Random order', 'okthemes-villenoir-shortcodes' ) => 'rand',
         ),
         "description" => esc_html__("Select how to sort retrieved posts.", "okthemes-villenoir-shortcodes"),
         'group' => esc_html__( 'Data filter', 'okthemes-villenoir-shortcodes' ),
      ),
      array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Meta key', 'okthemes-villenoir-shortcodes' ),
          'param_name' => 'meta_key',
          'description' => esc_html__( 'Input meta key for grid ordering.', 'okthemes-villenoir-shortcodes' ),
          'group' => esc_html__( 'Data filter', 'okthemes-villenoir-shortcodes' ),
          'dependency' => array(
            'element' => 'orderby',
            'value' => array( 'meta_value', 'meta_value_num' ),
          ),
        ),
      array(
         "type" => "dropdown",
         "heading" => esc_html__("Sorting", "okthemes-villenoir-shortcodes"),
         "param_name" => "order",
         "value" => array(
            "Descending" => "desc",
            "Ascending" => "asc"
         ),
         "description" => esc_html__("Designates the ascending or descending order.", "okthemes-villenoir-shortcodes"),
         'group' => esc_html__( 'Data filter', 'okthemes-villenoir-shortcodes' ),
      ),
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Extra class name', 'okthemes-villenoir-shortcodes' ),
        'param_name' => 'el_class',
        'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file. For example add "rem_filter_border_top" to remove the filter border-top style.', 'okthemes-villenoir-shortcodes' )
      ),
      $add_css_animation_extended,

   )
) );
}
//Filters For autocomplete param:
    //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
    add_filter( 'vc_autocomplete_products_scroll_products_scroll_terms_callback','productCategoryCategoryAutocompleteSuggesterVillenoir',
  10, 1 ); // Get suggestion(find). Must return an array
    add_filter( 'vc_autocomplete_products_scroll_products_scroll_terms_render','productCategoryCategoryRenderByIdExactVillenoir',
  10, 1 ); // Render exact category by id. Must return an array (label,value)

