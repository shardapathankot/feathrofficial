<?php
//Inline custom_css print
if (!function_exists('villenoir_generate_dynamic_css')):
    function villenoir_generate_dynamic_css() {
        /** Capture CSS output **/
        ob_start();// Capture all output into buffer
        require(get_template_directory() . '/lib/theme-customizer/custom_css.php');
        $css = ob_get_clean();// Store output in a variable, then flush the buffer

        if( function_exists('villenoir_minify_css') ) {
            $css = wp_strip_all_tags( villenoir_minify_css( $css ) );
        } else {
            $css = wp_strip_all_tags( $css );
        }

        return $css;
    }
endif;
?>