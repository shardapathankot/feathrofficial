<?php
/**
 * WooCommerce
 * Description: Page template for WooCommerce
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container('special_page'); ?>">
                <?php woocommerce_content(); ?>
            </div><!-- /.villenoir_page_container() -->

            <?php villenoir_page_sidebar('special_page'); ?>

        </div><!-- .row -->
    </div><!-- .container -->    
</section>

<?php get_footer(); ?>