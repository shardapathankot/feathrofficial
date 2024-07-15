<?php
// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'villenoir_product_year_field' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'villenoir_product_year_field_save' );

function villenoir_product_year_field() {

    global $woocommerce, $post;

    $year_field_label = _get_field('gg_store_year_field_label', 'option', esc_html__( 'Year', 'villenoir' ) );
    $bottle_size_field_label = _get_field('gg_store_bottle_size_field_label', 'option', esc_html__( 'Bottle size', 'villenoir' ) );
    
    // Year Field
    woocommerce_wp_text_input( 
        array( 
            'id'                => '_year_field', 
            'label'             => $year_field_label, 
            'placeholder'       => '', 
            'type'              => 'text'
        )
    );
    // Bottle size Field
    woocommerce_wp_text_input( 
        array( 
            'id'                => '_bottle_size_field', 
            'label'             => $bottle_size_field_label, 
            'placeholder'       => '', 
            'type'              => 'text', 
        )
    );
}
function villenoir_product_year_field_save( $post_id ){
    $product = wc_get_product($post_id);
    // Year
    $woocommerce_year_field = isset($_POST['_year_field']) ? $_POST['_year_field'] : '';
    $product->update_meta_data('_year_field', sanitize_text_field($woocommerce_year_field));
    // Bottle size
    $woocommerce_bottle_size = isset($_POST['_bottle_size_field']) ? $_POST['_bottle_size_field'] : '';
    $product->update_meta_data('_bottle_size_field', sanitize_text_field($woocommerce_bottle_size));
    
    $product->save();
}
?>