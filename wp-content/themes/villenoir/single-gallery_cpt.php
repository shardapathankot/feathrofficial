<?php
/**
 * Default Gallery Post Template
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container(); ?>">

                <?php while (have_posts()) : the_post(); ?>
                    
                <?php get_template_part( 'parts/gallery/part', 'gallery-single' ); ?>

                <?php endwhile; // end of the loop. ?>
                          
            </div><!-- end page container -->
            <?php villenoir_page_sidebar(); ?>

        </div>
    </div>    
</section>

<?php get_footer(); ?>