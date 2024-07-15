<?php
/**
 * The template for displaying Archive pages.
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<?php

//Get the ID of the page used for Blog posts
$blog_layout       = 'fitRows';
$blog_layout_style = 'gap';
$blog_columns      = 1;

$page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : '' );
if ($page_id) {
    $blog_layout       = _get_field('gg_blog_layout',$page_id,'fitRows');
    $blog_layout_style = _get_field('gg_blog_layout_style',$page_id,'gap');
    $blog_columns      = _get_field('gg_blog_columns',$page_id,1);
}

?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container($page_id); ?>">

            <?php if (have_posts()) :
            // Queue the first post.
            the_post();
            // Rewind the loop back
            rewind_posts();
            ?>
            <div class="gg_posts_grid">
                <ul class="el-grid <?php if($blog_layout_style == 'nogap') echo 'nogap-cols'; ?>" data-layout-mode="<?php echo esc_attr($blog_layout); ?>" data-gap="<?php echo esc_attr($blog_layout_style); ?>" data-columns="<?php echo esc_attr($blog_columns); ?>">
                <?php while (have_posts()) : the_post(); ?>

                    <li class="isotope-item col-xs-12 col-md-<?php echo esc_attr(floor( 12 / $blog_columns )); ?>">
                        <?php get_template_part( 'parts/post-formats/part', get_post_format() ); ?>
                    </li>      

                <?php endwhile; ?>

                </ul>
            </div>

            <?php if (function_exists("villenoir_pagination")) {
                villenoir_pagination();
            } ?>

            <?php // If no content, include the "No posts found" template.
            else :
                get_template_part( 'parts/post-formats/part', 'none' );
            endif;
            ?>

            </div>
            <?php villenoir_page_sidebar($page_id); ?>

        </div><!-- .row -->
    </div><!-- .container -->    
</section>

<?php get_footer(); ?>