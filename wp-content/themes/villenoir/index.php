<?php
/**
 * Description: Default Index template to display loop of blog posts
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<?php

//Get the ID of the page used for Blog posts
$page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : get_the_ID() );

$blog_layout = _get_field('gg_blog_layout','','fitRows');
$blog_layout_style = _get_field('gg_blog_layout_style','','gap');
$blog_columns = _get_field('gg_blog_columns','',1);
$blog_no_posts = _get_field('gg_blog_no_of_posts_to_show','',5);
$blog_pagination = _get_field('gg_blog_pagination','','numbered');

?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container(); ?>">

                <div class="gg_posts_grid">
                <?php if ( have_posts() ) : ?>
                    <ul class="el-grid <?php if($blog_layout_style == 'nogap') echo 'nogap-cols'; ?>" data-layout-mode="<?php echo esc_attr($blog_layout); ?>" data-gap="<?php echo esc_attr($blog_layout_style); ?>" data-pagination="<?php echo esc_attr($blog_pagination); ?>" data-columns="<?php echo esc_attr($blog_columns); ?>">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <li class="isotope-item col-xs-12 col-md-<?php echo esc_attr(floor( 12 / $blog_columns )); ?>"><?php get_template_part( 'parts/post-formats/part', get_post_format() ); ?></li>
                    <?php endwhile; ?>

                    </ul>

                    <?php 
                        if ($blog_pagination == 'ajax_load') { ?>
                            <div class="load-more-anim"></div>
                            <div class="pagination-load-more">
                                <span class="pagination-span">
                                <?php next_posts_link('Load more posts', $wp_query->max_num_pages) ?>
                                </span>
                            </div>

                        <?php } else {
                            
                            if (function_exists("villenoir_pagination")) {
                                villenoir_pagination();
                            }
                        } ?>

                <?php else : ?>

                    <?php get_template_part( 'parts/post-formats/part', 'none' ); ?>

                <?php endif; // end have_posts() check ?>
                </div><!--/ .gg_posts_grid-->
            </div>
            <?php villenoir_page_sidebar(); ?>

        </div><!-- .row -->
    </div><!-- .container -->    
</section>
<?php get_footer(); ?>