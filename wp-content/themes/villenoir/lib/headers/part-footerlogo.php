<div class="col-md-12">
<?php 
    // Displays H1 or DIV based on whether we are on the home page or not (SEO)
    $heading_tag = ( is_home() || is_front_page() ) ? 'p' : 'div';
    
    if ( _get_field('gg_display_footer_image_logo', 'option', false) ) {    
        
        $class="graphic";
        $margins_style = '';

        //Theme Logo
        $default_logo = array(
            'url' => get_template_directory_uri() . '/images/footer-logo.png',
            'width' => '230',
            'height' => '64',
        );

        $logo = _get_field('gg_footer_logo_image', 'option', $default_logo);
        //If logo is not yet imported display default logo
        if ($logo == false)
            $logo = $default_logo;

        $margins = _get_field('gg_footer_logo_margins', 'option','');

        if ($margins) {
            $margins_style = 'style="margin: '.esc_attr($margins).';"';
        }

        echo '<a class="brand" href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'" rel="home">'. "\n";
        echo '<img '.$margins_style.' class="brand" src="'.esc_url($logo['url']).'" width="'.esc_attr($logo['width']).'" height="'.esc_attr($logo['height']).'" alt="'.esc_attr( get_bloginfo('name','display')).'" />'. "\n";
        echo '</a>'. "\n";
    } else {
        $class="text site-title"; 
        echo '<'.$heading_tag.' class="'.esc_attr($class).'">';
        echo '<a class="brand" href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'" rel="home">'.get_bloginfo('name').'</a>';
        echo '</'.$heading_tag.'>'. "\n";     
    } 
    
?>
</div>