<?php
/**
 * Default Page
 * Description: Page template with a content container and right sidebar.
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container(); ?>">

                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'parts/part', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                // End the loop.
                endwhile;
                ?>

            </div><!-- end page container -->
            <?php villenoir_page_sidebar(); ?>

        </div><!-- .row -->
    </div><!-- .container -->    
</section>

<?php get_footer(); ?>