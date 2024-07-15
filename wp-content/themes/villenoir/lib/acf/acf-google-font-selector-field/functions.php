<?php

/**
 * Common Functions
 *
 * This file contains the common functions shared between the ACF 5 and ACF 4
 * versions.
 *
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */


 /**
  * Get Fonts To Enqueue
  *
  * Retrieves the fonts to enqueue on the current page
  *
  * @return array The array of fonts to enqueue
  * @author Daniel Pataki
  * @since 3.0.0
  *
  */
 function villenoir_get_fonts_to_enqueue() {
     if( is_singular() ) {
         global $post;
         $post_fields = get_field_objects( $post->ID );
     }
     $post_fields = ( empty( $post_fields ) ) ? array() : $post_fields;
     $option_fields = get_field_objects( 'options' );
     $option_fields = ( empty( $option_fields ) ) ? array() : $option_fields;
     $fields = array_merge( $post_fields, $option_fields );
     $font_fields = array();
     foreach( $fields as $field ) {
         if( !empty( $field['type'] ) && 'google_font_selector' == $field['type'] && !empty( $field['value'] ) ) {
             $font_fields[] = $field['value'];
         }
     }

     $font_fields = apply_filters( 'acfgfs/enqueued_fonts', $font_fields );

     return $font_fields;
 }

 /**
  * Enqueue Fonts
  *
  * Retrieves the fonts to enqueue on the current page
  *
  * @uses villenoir_get_fonts_to_enqueue()
  * @author Daniel Pataki
  * @since 3.0.0
  *
  */
function villenoir_google_font_enqueue(){
    $fonts = villenoir_get_fonts_to_enqueue();

    if( empty( $fonts ) ) {

      //Load default fonts if none exists
      $fonts = array ( 
        0 => array ( 
          'font' => 'Open Sans', 
          'variants' => array ( 
            0 => '300',
            1 => '400',
            2 => '600', 
            3 => '700', 
          ), 
          'subsets' => array ( 
            0 => 'latin', 
          ),
        ), 
        1 => array ( 
          'font' => 'Lato', 
          'variants' => array ( 
            0 => '300', 
            1 => '400',
            2 => '700',
          ), 
          'subsets' => array ( 
            0 => 'latin',
          ),
        ),
        2 => array ( 
          'font' => 'Nothing You Could Do', 
          'variants' => array ( 
            0 => '400', 
          ), 
          'subsets' => array ( 
            0 => 'latin',
          ),
        ), 
      );
    }
    //End default fonts

    $subsets = array();
    $font_element = array();
    foreach( $fonts as $font ) {

        if ( ! villenoir_check_web_safe_fonts($font['font']) ) :

        $subsets = array_merge( $subsets, $font['subsets'] );
        $font_name = str_replace(' ', '+', $font['font'] );
        if( $font['variants'] == array( 'regular' ) ) {
            $font_element[] = $font_name;
        } else {
            $regular_variant = array_search( 'regular', $font['variants'] );
            if( $regular_variant !== false ) {
                $font['variants'][$regular_variant] = '400';
            }
            $font_element[] = $font_name . ':' . implode( ',', $font['variants'] );
        }

        endif;
    }
    $subsets = ( empty( $subsets ) ) ? array('latin') : array_unique( $subsets );
    $subset_string = implode( ',', $subsets );
    $font_string = implode( '|', $font_element );
    
    //Construct the font URL    
    $font_url = add_query_arg( 'family', $font_string . '&subset=' . $subset_string . '&display=swap', "//fonts.googleapis.com/css" );
    
    wp_enqueue_style( 'acfgfs-enqueue-fonts', $font_url, array(), '1.0.0' );
}



/**
 * Font Dropdown Array
 *
 * Retrieves a list of fonts as an array. The array uses the
 * font name as the key and value. Uses the villenoir_get_web_safe_fonts()
 * function to add a list of we safe fonts if the user enabled it in the
 * options.
 *
 * @param array $field The field data
 * @uses villenoir_get_web_safe_fonts()
 * @return array The array of fonts
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_get_font_dropdown_array( $field = null ) {
    $fonts = villenoir_get_fonts();

    $font_array = array();
    foreach( $fonts as $font => $data ) {
        $font_array[$font] = $font;
    }

    if( !empty( $field['include_web_safe_fonts'] ) ) {
        $web_safe = villenoir_get_web_safe_fonts();
        foreach( $web_safe as $font ) {
            $font_array[$font] = $font;
        }
    }

    asort( $font_array );

    $font_array = apply_filters( 'acfgfs/font_dropdown_array', $font_array );

    return $font_array;
}

/**
 * Get Font
 *
 * Retrieves the details of a single font
 *
 * @param string $font The name of the font to retrieve
 * @uses villenoir_get_fonts()
 * @return array The details of the font
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_get_font( $font ) {
    $fonts = villenoir_get_fonts();
    return isset($fonts[$font]) ? $fonts[$font] : false;
}

/**
 * Get Fonts
 *
 * Gets all fonts. It first checks a transient. If the transient doesn't
 * exist it gets all fonts from Google. If this fails for some reason we
 * fall back on a file which has a font list.
 *
 * We then create a special format that works for us and finally
 * add the web safe fonts if they are needed.
 *
 * @uses villenoir_retrieve_fonts()
 * @uses villenoir_get_web_safe_fonts()
 * @return array The final font list
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_get_fonts() {

    $fonts = include( 'font-list.php' );

    $acfgfs_fonts = array();
    foreach( $fonts['items'] as $font ) {
        $acfgfs_fonts[$font['family']] = array(
            'variants' => $font['variants'],
            'subsets' => $font['subsets']
        );
    }

    if( !empty( $field['include_web_safe_fonts'] ) ) {
        $web_safe = villenoir_get_web_safe_fonts();
        foreach( $web_safe as $font ) {
            $acfgfs_fonts[$font] = array(
                'variants' => array( 'regular', '700' ),
                'subsets' => array( 'latin' )
            );
        }
    }

    return $acfgfs_fonts;
}


/**
 * Web Safe Fonts
 *
 * A simple array of web safe fonts
 *
 * @return array Array of web safe fonts
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_get_web_safe_fonts() {
    $web_safe = array( 'Georgia', 'Palatino Linotype', 'Book Antiqua', 'Palatino', 'Times New Roman', 'Times', 'Arial', 'Helvetica', 'Arial Black', 'Gadget', 'Impact', 'Charcoal', 'Lucida Sans Unicode', 'Lucida Grande', 'Tahoma', 'Geneva', 'Trebuchet MS', 'Helvetica', 'Verdana', 'Geneva', 'Courier New', 'Courier', 'Lucida Console', 'Monaco' );
    return $web_safe;
}

/**
 * Web Safe Font Check
 *
 * Checks to see if Current Font is a Web Safe Font
 *
 * @return array Boolean true if web safe false if otherwise
 * @author Parapxl
 * @since 3.0.0
 *
 */
function villenoir_check_web_safe_fonts( $font ) {
    $web_safe = villenoir_get_web_safe_fonts();
    return array_search($font, $web_safe) !== false ? true : false;
}


/**
 * Font Variant Array
 *
 * Gets an array of font variants for a given font
 *
 * @param string $font The font to retrieve variants for
 * @uses villenoir_get_font()
 * @return array The variant list for this font
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_get_font_variant_array( $font ) {
    $font = villenoir_get_font( $font );
    return isset($font['variants']) ? $font['variants'] : false;
}


/**
 * Font Subset Array
 *
 * Gets an array of font subsets for a given font
 *
 * @param string $font The font to retrieve variants for
 * @uses villenoir_get_font()
 * @return array The subset list for this font
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_get_font_subset_array( $font ) {
    $font = villenoir_get_font( $font );
    return isset($font['subsets']) ? $font['subsets'] : false;
}

/**
 * Display Variant List
 *
 * Displays a checkbox list of font variants. If only a field is given it
 * looks up the current font (or uses the default). The second parameter
 * is used when a new font is selected and we grab a variant list via AJAX.
 *
 * At this stage the new font is not saved but we still need to show the
 * variant list for that font. When the $new_font parameter is given the
 * value of $field is not used.
 *
 * @param string $field The field to retrieve variants for
 * @param string $new_font The font to retrieve variants for
 * @uses villenoir_get_font()
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_display_variant_list( $field, $new_font = null ) {
    $font = $new_font;
    if( empty( $new_font ) ) {
        $font = ( empty( $field['value']['font'] ) ) ? $field['default_font'] :     $field['value']['font'];
    }

    $font = villenoir_get_font( $font );
    $font['variants'] = (empty( $font['variants'] )) ? array() : $font['variants'];
    $i = 1;
    foreach( $font['variants'] as $variant ) :
        if( empty( $new_font ) ) {
          
          if ( ( empty( $field['value'] ) || ( !empty( $field['value'] ) && in_array( $variant, $field['value']['variants'] ) ) ) ) {
        ?>
            <input checked="checked" type="checkbox" id="<?php echo esc_attr($field['key']); ?>_variants_<?php echo esc_attr($i); ?>" name="<?php echo esc_attr($field['key']); ?>_variants[]" value="<?php echo esc_attr($variant); ?>">
        <?php
          } else { ?>
            <input type="checkbox" id="<?php echo esc_attr($field['key']); ?>_variants_<?php echo esc_attr($i); ?>" name="<?php echo esc_attr($field['key']); ?>_variants[]" value="<?php echo esc_attr($variant); ?>">
          <?php }
        } else { ?>
            <input <?php checked( $variant, 'regular' ); ?> type="checkbox" id="<?php echo esc_attr($field['key']); ?>_variants_<?php echo esc_attr($i); ?>" name="<?php echo esc_attr($field['key']); ?>_variants[]" value="<?php echo esc_attr($variant); ?>">
        <?php } ?>
        
        <label for="<?php echo esc_attr($field['key']); ?>_variants_<?php echo esc_attr($i); ?>">
        <?php echo esc_html($variant); ?>
        </label>
        <br>

        <?php $i++; endforeach;

}


/**
 * Display Subset List
 *
 * Displays a checkbox list of font subsets. If only a field is given it
 * looks up the current font (or uses the default). The second parameter
 * is used when a new font is selected and we grab a subset list via AJAX.
 *
 * At this stage the new font is not saved but we still need to show the
 * subset list for that font. When the $new_font parameter is given the
 * value of $field is not used.
 *
 * @param string $field The field to retrieve subsets for
 * @param string $new_font The font to retrieve subsets for
 * @uses villenoir_get_font()
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_display_subset_list( $field, $new_font = null ) {

    $font = $new_font;
    if( empty( $new_font ) ) {
        $font = ( empty( $field['value']['font'] ) ) ? $field['default_font'] : $field['value']['font'];
    }

    $font = villenoir_get_font( $font );
    $font['subsets'] = (empty( $font['subsets'] )) ? array() : $font['subsets'];
    $i = 1;
    foreach( $font['subsets'] as $subset ) : ?>
        <?php 
        if( empty( $new_font ) ) {
          if ( ( empty( $field['value'] ) || ( !empty( $field['value'] ) && in_array( $subset, $field['value']['subsets'] ) ) ) ) {
        ?>
            <input checked="checked" type="checkbox" id="<?php echo esc_attr($field['key']); ?>_subsets_<?php echo esc_attr($i) ?>" name="<?php echo esc_attr($field['key']); ?>_subsets[]" value="<?php echo esc_html($subset); ?>">
        <?php
          } else { ?>
            <input type="checkbox" id="<?php echo esc_attr($field['key']); ?>_subsets_<?php echo esc_attr($i) ?>" name="<?php echo esc_attr($field['key']); ?>_subsets[]" value="<?php echo esc_html($subset); ?>">
          <?php }
        } else { ?>
        <input <?php checked( $subset, 'latin' ); ?>  type="checkbox" id="<?php echo esc_attr($field['key']); ?>_subsets_<?php echo esc_attr($i) ?>" name="<?php echo esc_attr($field['key']); ?>_subsets[]" value="<?php echo esc_html($subset); ?>">
        <?php } ?>
        
        <label for="<?php echo esc_attr($field['key']); ?>_subsets_<?php echo esc_attr($i) ?>"><?php echo esc_html($subset); ?></label> <br>

        <?php $i++; endforeach;

}

/**
 * Get Font Details
 *
 * Used in AJAX requests to output the HTML needed to display the UI for
 * the newly chosen font.
 *
 * @uses villenoir_display_subset_list()
 * @uses villenoir_display_variant_list()
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
function villenoir_action_get_font_details() {
    $details = array();
    $field = json_decode( stripslashes( $_POST['data'] ), true );
    unset( $field['value'] );

    ob_start();
    villenoir_display_subset_list( $field, $_POST['font_family'] );
    $details['subsets'] = ob_get_clean();

    ob_start();
    villenoir_display_variant_list( $field, $_POST['font_family'] );
    $details['variants'] = ob_get_clean();

    echo json_encode( $details );

    die();
}
