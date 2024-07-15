<?php

//Display membership table in My account
  
 add_action( 'init', 'villenoir_memberships_unhook_members_area' );
  
 /**
  * Removes Memberships default Members Area used since v1.9 onwards.
  */
 function villenoir_memberships_unhook_members_area() {
  
    if ( ! is_admin() && function_exists( 'wc_memberships' ) ) {
     
        $frontend     = wc_memberships()->get_frontend_instance();
        $members_area = $frontend ? $frontend->get_members_area_instance() : null; 
     
        if ( $members_area ) {
       
            remove_filter( 'woocommerce_account_menu_items', array( $members_area, 'add_account_members_area_menu_item' ), 999 );
        }
    }
 }

add_action( 'woocommerce_after_template_part', 'villenoir_memberships_output_my_account_dashboard_my_memberships' );

 /**
  * Outputs the Members Area below the My Account Dashboard as in versions before v1.9.
  *
  * @param string $template the current template being displayed by WooCommerce
  */
function villenoir_memberships_output_my_account_dashboard_my_memberships( $template = '' ) {

    if ( 'myaccount/dashboard.php' === $template ) {

        ob_start();
      
        ?>
        <div class="woocommerce">
            <h2 class="my-memberships-title"><?php esc_html_e( 'My Memberships', 'villenoir' ); ?></h2>
            <?php wc_get_template( 'myaccount/my-memberships.php', array(
                  'customer_memberships' => wc_memberships_get_user_memberships(),
                  'user_id'              => get_current_user_id(),
            ) ); ?>
        </div>
        <?php

        echo ob_get_clean();
    }   
}

/**
 * Display a FontAwesome lock icon next to the post title if a member does not have access
 *  with WooCommerce Memberships.
 *
 * @param string $post_title the post title
 * @param int $post_id the WordPress post ID
 * @return string the updated title
 */
function villenoir_wc_memberships_add_post_lock_icon( $title, $post_id ) {

    if ( is_admin() ) {
        return $title;
    }

    // show the lock icon if the post is restricted, or access is delayed
    if (   ! current_user_can( 'wc_memberships_view_delayed_post_content',    $post_id )
        || ! current_user_can( 'wc_memberships_view_restricted_post_content', $post_id ) ) {

        $title = "<i class='fa fa-lock' aria-hidden='true'></i> {$title}";
    }

    return $title;
}
add_filter( 'the_title', 'villenoir_wc_memberships_add_post_lock_icon', 10, 2 );