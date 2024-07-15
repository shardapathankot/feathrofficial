<?php
/**
 * The Sidebars
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<?php 
if (function_exists('dynamic_sidebar')) {
	
	if( is_page_template('theme-templates/contact_v1.php') || is_page_template('theme-templates/contact_v2.php') ) {
		
		$dynamic_sidebar = 'sidebar-contact';

	} elseif( villenoir_is_wc_activated() && is_product() ) {
		
		$dynamic_sidebar = 'sidebar-product';

	} elseif( villenoir_is_wc_activated() && is_woocommerce() ) {
		
		$dynamic_sidebar = 'sidebar-shop';

	} elseif( is_search() ) {
		
		$dynamic_sidebar = 'sidebar-search';			

	} elseif( is_single() || is_home() || is_category() || is_archive() ) {
		
		$dynamic_sidebar = 'sidebar-posts';

	} else { //else default sidebar
		
		$dynamic_sidebar = 'sidebar-page';

	}
}

//Get the user generated sidebars
$sidebar = _get_field('gg_select_sidebar','','default-sidebar');
//Conditional check for user and dynamic generated sidebars
if ( !is_array($sidebar) && $sidebar != 'default-sidebar' ) {
	dynamic_sidebar($sidebar);
} elseif ( is_active_sidebar( $dynamic_sidebar ) ) {
    dynamic_sidebar( $dynamic_sidebar );
}