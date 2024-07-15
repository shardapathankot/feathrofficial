<?php
/**
 * Default Post Template
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<?php
$post_share_box = _get_field('gg_post_social_share','',true);
$post_nav       = _get_field('gg_post_navigation','',true);
?>

<?php while (have_posts()) : the_post(); ?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="<?php villenoir_page_container(); ?>">

                <?php get_template_part( 'parts/post-formats/part', get_post_format() ); ?>

                <?php
                $previous   = get_previous_post_link( '<div class="nav-previous">%link</div>', esc_html__( 'Previous post', 'villenoir' ) );
                $next       = get_next_post_link( '<div class="nav-next">%link</div>', esc_html__( 'Next post', 'villenoir' ) );
                ?>

                <?php if ( $post_share_box || $post_nav ) : ?>
                <div class="btn-group btn-group-justified pagination-wrapper">
                    <?php if ( $post_nav ) : ?>
                    <div class="btn-group" role="group">
                        <?php echo wp_kses_post($previous); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ( $post_share_box ) : ?>
                    <div class="btn-group" role="group">
                        <?php get_template_part( 'parts/part', 'socialshare' ); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( $post_nav ) : ?>
                    <div class="btn-group" role="group">
                        <?php echo wp_kses_post($next); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php endwhile; // end of the loop. ?>
                
                <?php comments_template( '', true ); ?>

            </div><!-- end page container -->
            <?php villenoir_page_sidebar(); ?>

        </div><!-- /.row -->
    </div><!--/.container -->    
</section>

<?php get_footer(); ?>