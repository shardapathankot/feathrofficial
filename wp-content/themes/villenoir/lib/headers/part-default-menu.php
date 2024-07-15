<nav class="navbar navbar-default navbar-expand-lg">
    <div class="container navbar-header-wrapper">

        <div class="navbar-grid" id="main-navbar">

            <!-- primary-mobile-menu -->
            <div class="menu-button-container">

                <?php //Header minicart
                    $header_minicart   = _get_field('gg_header_minicart','option', true);
                    $header_minicart_mobile   = _get_field('gg_header_minicart_mobile','option', true);
                    if ( $header_minicart === true && $header_minicart_mobile === true && villenoir_is_wc_activated() ) {
                        // Add cart link to menu items
                        echo '<ul>';
                        echo '<li class="gg-woo-mini-cart">' . villenoir_wc_minicart() .'</li>';
                        echo '</ul>';
                    }
                ?>

                <button id="primary-mobile-menu" class="button" aria-controls="primary-menu-list" aria-expanded="false">
                    <span class="dropdown-icon open"><span><?php esc_html_e('Menu', 'villenoir'); ?></span>
                        <svg class="svg-icon" width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.5 6H19.5V7.5H4.5V6ZM4.5 12H19.5V13.5H4.5V12ZM19.5 18H4.5V19.5H19.5V18Z" fill="currentColor"></path></svg>
                    </span>
                    <span class="dropdown-icon close"><span><?php esc_html_e('Close', 'villenoir'); ?></span>
                        <svg class="svg-icon" width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 10.9394L5.53033 4.46973L4.46967 5.53039L10.9393 12.0001L4.46967 18.4697L5.53033 19.5304L12 13.0607L18.4697 19.5304L19.5303 18.4697L13.0607 12.0001L19.5303 5.53039L18.4697 4.46973L12 10.9394Z" fill="currentColor"></path></svg>
                    </span>
                </button>

                

            </div>

            <div class="logo-wrapper">
                <?php villenoir_logo(); ?>
            </div><!-- .logo-wrapper -->
            
            <!-- Begin Main Navigation -->
            <?php
            wp_nav_menu(
                array(
                    'theme_location'    => 'main-menu',
                    'container'         => '',
                    'container_class'   => '',
                    'menu_class'        => 'nav navbar-nav navbar-middle',
                    'menu_id'           => 'main-menu',
                    'fallback_cb' => false
                )
            ); ?>

            <!-- End Main Navigation -->

            <!-- Begin Second Navigation -->
            <?php villenoir_secondary_navigation(); ?>
            <!-- End Second Navigation -->

        </div><!-- #main-navbar -->

    </div><!-- .container -->
</nav><!-- nav -->
