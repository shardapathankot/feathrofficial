<?php

function villenoir_generate_dummy_css_for_mobile_check($css, $css_mobile, $css_tablet) {
   if (empty($css) && (!empty($css_mobile) || !empty($css_tablet))) {
       // Generate a random number between 1000 and 9999
       $random_number = rand(1000, 9999);

       // Create the custom class using the random number
       $css = '.vc_custom_' . rand(1000, 999999) .'{padding: 0;}';
   }

   return $css;
}

/* Generate custom_css class for tablet/mobile */
function villenoir_generate_custom_css($mobile_device, $desktop_css_class, $mode) {
   // If $mobile_device is empty, return nothing
   if (!$mobile_device) {
       return;
   }
   
   // Remove the numeric part of the custom CSS class for mobile
   $mobile_css = preg_replace('/\.vc_custom_\d+\{/', '{', $mobile_device);

   // Determine the media query based on $mode
   $media_query = ($mode === 'tablet') ? '(max-width: 768px)' : '(max-width: 480px)';

   // Convert the desktop CSS class to a format compatible with Visual Composer
   $desktop_class = vc_shortcode_custom_css_class($desktop_css_class, '.');

   // Concatenate the mobile CSS and desktop CSS within a media query
   $output = "<style>@media $media_query { $desktop_class $mobile_css }</style>";

   // Return the generated CSS
   return $output;
}

/**
 * Get tax term slug
 */
if (!function_exists('villenoir_shortcodes_tax_terms_slug')) :
	function villenoir_shortcodes_tax_terms_slug($taxonomy) {
		global $post, $post_id;
		$return = '';
		// get post by post id
		$post = get_post($post->ID);
		// get post type by post
		$post_type = $post->post_type;
		// get post type taxonomies
		$terms = get_the_terms( $post->ID, $taxonomy );
		if ( !empty( $terms ) ) {
			$out = array();
			foreach ( $terms as $term )
				$out[] = 'grid-cat-' . $term->slug;
			$return = join( ' ', $out );
		}
		return $return;
	}
endif;

// Initialising Shortcodes
if ( class_exists('WPBakeryVisualComposerAbstract') ) {

   /**
    * Taxonomy checkbox list field.
    *
    */
   if ( ! function_exists( 'gg_taxonomy_settings_field' ) ) {
    
   function gg_taxonomy_settings_field($settings, $value) {
      $terms_fields = array();
      $terms_slugs = array();

      $value_arr = $value;
      if ( !is_array($value_arr) ) {
         $value_arr = array_map( 'trim', explode(',', $value_arr) );
      }

      if ( !empty($settings['taxonomy']) ) {

         $terms = get_terms( $settings['taxonomy'] );
         if ( $terms && !is_wp_error($terms) ) {

            foreach( $terms as $term ) {
               $terms_slugs[] = $term->slug;

               $terms_fields[] = sprintf(
                  '<label><input id="%s" class="%s" type="checkbox" name="%s" value="%s" %s/>%s</label>',
                  $settings['param_name'] . '-' . $term->slug,
                  $settings['param_name'].' '.$settings['type'],
                  $settings['param_name'],
                  $term->term_id,
                  checked( in_array( $term->term_id, $value_arr ), true, false ),
                  $term->name
               );
            }
         }
      }

      return '<div class="gg_taxonomy_block">'
            .'<input type="hidden" name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-checkboxes '.$settings['param_name'].' '.$settings['type'].'_field" value="'.$value.'" />'
             .'<div class="gg_taxonomy_terms">'
             .implode( $terms_fields )
             .'</div>'
          .'</div>';
   }
   vc_add_shortcode_param('gg_taxonomy', 'gg_taxonomy_settings_field', VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/gg-taxonomy.js' );
   }

   /**
    * Posts checkbox list field.
    *
    */
   if ( ! function_exists( 'gg_posttype_settings_field' ) ) {
   function gg_posttype_settings_field($settings, $value) {
      $posts_fields = array();
      $terms_slugs = array();

      $value_arr = $value;
      if ( !is_array($value_arr) ) {
         $value_arr = array_map( 'trim', explode(',', $value_arr) );
      }

      if ( !empty($settings['posttype']) ) {

         $args = array(
            'no_found_rows' => 1,
            'ignore_sticky_posts' => 1,
            'posts_per_page' => -1,
            'post_type' => $settings['posttype'],
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
         );

         $gg_query = new WP_Query( $args );
         if ( $gg_query->have_posts() ) {

            foreach( $gg_query->posts as $p ) {

               $posts_fields[] = sprintf(
                  '<label><input id="%s" class="%s" type="checkbox" name="%s" value="%s" %s/>%s</label>',
                  $settings['param_name'] . '-' . $p->post_name,
                  $settings['param_name'] . ' ' . $settings['type'],
                  $settings['param_name'],
                  $p->post_name,
                  checked( in_array( $p->post_name, $value_arr ), true, false ),
                  $p->post_title
               );
            }
         }
      }

      return '<div class="gg_posttype_block">'
            .'<input type="hidden" name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-checkboxes '.$settings['param_name'].' '.$settings['type'].'_field" value="'.$value.'" />'
             .'<div class="gg_posttype_post">'
             .implode( $posts_fields )
             .'</div>'
          .'</div>';
   }
   
   vc_add_shortcode_param('gg_posttype', 'gg_posttype_settings_field', VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/gg-posttype.js' );
  }
}

function productCategoryCategoryAutocompleteSuggesterVillenoir( $query, $slug = false ) {
    global $wpdb;
    $cat_id = (int) $query;
    $query = trim( $query );
    $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
            FROM {$wpdb->term_taxonomy} AS a
            INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
            WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

    $result = array();
    if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
      foreach ( $post_meta_infos as $value ) {
        $data = array();
        $data['value'] = $slug ? $value['slug'] : $value['id'];
        $data['label'] = esc_html__( 'Id', 'js_composer' ) . ': ' . $value['id'] . ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . esc_html__( 'Name', 'js_composer' ) . ': ' . $value['name'] : '' ) . ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . esc_html__( 'Slug', 'js_composer' ) . ': ' . $value['slug'] : '' );
        $result[] = $data;
      }
    }

    return $result;
  }

  function productCategoryCategoryRenderByIdExactVillenoir( $query ) {
    $query = $query['value'];
    $cat_id = (int) $query;
    $term = get_term( $cat_id, 'product_cat' );

    return productCategoryTermOutputVillenoir( $term );
  }

  function productCategoryTermOutputVillenoir( $term ) {
    $term_slug = $term->slug;
    $term_title = $term->name;
    $term_id = $term->term_id;

    $term_slug_display = '';
    if ( ! empty( $term_slug ) ) {
      $term_slug_display = ' - ' . esc_html__( 'Sku', 'js_composer' ) . ': ' . $term_slug;
    }

    $term_title_display = '';
    if ( ! empty( $term_title ) ) {
      $term_title_display = ' - ' . esc_html__( 'Title', 'js_composer' ) . ': ' . $term_title;
    }

    $term_id_display = esc_html__( 'Id', 'js_composer' ) . ': ' . $term_id;

    $data = array();
    $data['value'] = $term_id;
    $data['label'] = $term_id_display . $term_title_display . $term_slug_display;

    return ! empty( $data ) ? $data : false;
  }