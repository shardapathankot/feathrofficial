<?php
/**
 * Template Name: Blog page
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<?php
$blog_layout       = _get_field('gg_blog_layout','','fitRows');
$blog_layout_style = _get_field('gg_blog_layout_style','','gap');
$blog_columns      = _get_field('gg_blog_columns','',1);
$blog_no_posts     = _get_field('gg_blog_no_of_posts_to_show','',5);
$blog_pagination   = _get_field('gg_blog_pagination','','numbered');

global $more;

// WP_Query arguments
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$args = array (
    'post_type'              => 'post',
    'posts_per_page'         => $blog_no_posts,
    'paged' => $paged
);
// The Query
$blog_query = new WP_Query( $args );
?>


<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container(); ?>">

                <div class="gg_posts_grid">
                    <?php if ( $blog_query->have_posts() ) : ?>
                        <ul class="el-grid <?php if($blog_layout_style == 'nogap') echo 'nogap-cols'; ?>" data-layout-mode="<?php echo esc_attr($blog_layout); ?>" data-gap="<?php echo esc_attr($blog_layout_style); ?>" data-pagination="<?php echo esc_attr($blog_pagination); ?>" data-columns="<?php echo esc_attr($blog_columns); ?>">
                        <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); $more = 0; ?>
                            <li class="isotope-item col-xs-6 col-md-<?php echo esc_attr(floor( 12 / $blog_columns )); ?>">
                                <?php get_template_part( 'parts/post-formats/part', get_post_format() ); ?>
                            </li>
                        <?php endwhile; ?>
                        </ul>

                        <?php 
                        if ($blog_pagination == 'ajax_load') { ?>
                            <div class="load-more-anim"></div>
                            <div class="pagination-load-more">
                                <span class="pagination-span">
                                <?php next_posts_link('Load more posts', $blog_query->max_num_pages) ?>
                                </span>
                            </div>

                        <?php } else {
                            
                            if (function_exists("villenoir_pagination")) {
                                villenoir_pagination($blog_query);
                            }
                        } ?>

                    <?php 
                    // clean up after the query and pagination
                    wp_reset_postdata(); 
                    ?>

                    <?php else : ?>

                        <?php get_template_part( 'parts/post-formats/part', 'none' ); ?>

                    <?php endif; // end have_posts() check ?>
                </div><!--/ .gg_posts_grid-->
        
            </div><!-- end page container -->
            <?php villenoir_page_sidebar(); ?>

        </div><!-- .row -->
    </div><!-- .container -->    
</section>
<?php get_footer(); ?>