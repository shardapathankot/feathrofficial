<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header();

$gg_title = _get_field('gg_error_page_title', 'option','404 Error');
$gg_desc = _get_field('gg_error_page_description', 'option','It seems we can\'t find what you\'re looking for. Perhaps searching can help.');
?>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="not_found_wrapper">

                    <div class="col-md-12">
                    <?php if ($gg_title) : ?>
                    <h1><?php echo esc_html($gg_title); ?></h1>
                    <?php endif; ?>

                    <?php if ($gg_desc) : ?> 
                    <p class="info-404"><?php echo esc_html($gg_desc); ?></p>
                    <?php endif; ?>
                    
                    <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Go to homepage', 'villenoir'); ?></a>
                    </div>

                    <div class="col-md-6 col-md-offset-3">
                        <?php get_search_form(); ?>
                    </div>

                </div><!-- /.not_found_wrapper -->
            </div>
        </div><!-- /.row .content -->
    </div><!--/.container -->    
</section>

<?php get_footer();