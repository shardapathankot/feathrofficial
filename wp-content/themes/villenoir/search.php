<?php
/**
 * Search Results Template
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container(); ?>">

            <?php if(have_posts()) : ?>

            <?php   
            $last_type="";
            $typecount = 0;

            //Get no of shop columns
            $wc_columns = _get_field('gg_shop_product_columns','option', '3');

            while (have_posts()) :
            the_post();
            if ($last_type != $post->post_type){
                $typecount = $typecount + 1;
                if ($typecount > 1){
                    echo "</ul>";
                    echo '</div><!-- close container -->'; //close type container
                }
                // save the post type.
                $last_type = $post->post_type;
                //open type container
                switch ($post->post_type) {
                    case 'post':
                        echo "<div class=\"postsearch\"><h2>".esc_html__('Blog results','villenoir')."</h2>";
                        echo "<ul class='el-grid' data-columns='2'>";
                        break;
                    case 'product':
                        echo "<div class=\"productsearch woocommerce\"><h2>".esc_html__('Product search results','villenoir')."</h2>";
                        echo "<ul class='products'>";
                        break;

                }
            } 
            ?>

            <?php if('post' == get_post_type()) : ?>
                <li class="isotope-item col-xs-12 col-sm-6 col-md-6"><?php get_template_part( 'parts/post-formats/part', get_post_format() ); ?></li>
            <?php endif; ?>

            <?php if('product' == get_post_type()) : ?>
                <li class="isotope-item col-xs-12 col-sm-6 col-md-<?php echo floor( 12 / $wc_columns ); ?> product"><?php wc_get_template_part( 'content', 'product-vc' ); ?></li>
            <?php endif; ?>


            <?php endwhile; ?>



            <?php // If no content, include the "No posts found" template.
            else :
                get_template_part( 'parts/post-formats/part', 'none' );
            ?>    

            <?php endif; ?>       

            </div><!-- end page container -->
            <?php villenoir_page_sidebar(); ?>

        </div><!-- .row -->
    </div><!-- .container -->    
</section>

<?php get_footer(); ?>