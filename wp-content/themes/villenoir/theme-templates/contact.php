<?php
/**
 * Template Name: Contact
 *
 * @package WordPress
 * @subpackage villenoir
 */
get_header(); ?>

<?php
$contact_form = _get_field( 'gg_contact_form', '', true );
$contact_map = _get_field( 'gg_contact_show_map', '', true );
?>

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

                <div class="clearfix"></div>

                <?php if ($contact_map) : ?>
                <div class="col-xs-12 col-md-6 col-md-offset-3 gg-view-map-wrapper">
                <a class="lightbox-el gg-popup" data-effect="mfp-zoom-in" href="#gg-map-popup"> <?php esc_html_e('View map', 'villenoir');?>
                </a>
                </div>
                <?php endif; ?>

                <div class="clearfix"></div>

                <?php if ($contact_form) : ?>
                <div class="contact-form-wrapper col-xs-12 col-md-6 col-md-offset-3">
                <?php get_template_part( 'parts/forms/part','contact-form' ); ?>
                </div><!--Close .contact-form-wrapper -->
                <?php endif; ?>

            </div><!-- end page container -->
            <?php villenoir_page_sidebar(); ?>

        </div><!-- .row -->
    </div><!-- .container -->     
</section>


<?php if ($contact_map) : ?>
<?php if( have_rows('gg_contact_addresses') ): ?>

<?php $map_marker = get_template_directory_uri() .'/images/map-marker.png'; ?>

<!-- Map script -->
<script type="text/javascript">
;(function ($, window, undefined) {
$(document).ready(function() {
    var myOptions = {
        mapTypeId: google.maps.MapTypeId.ROADMAP //ROADMAP , SATELLITE , HYBRID , TERRAIN 
    };
    var contact_map = new Maplace({
        locations:
        [
            <?php while ( have_rows('gg_contact_addresses') ) : the_row(); ?>

                {
                    <?php if (get_sub_field('gg_contact_map_latitude')) : ?>
                    lat: <?php the_sub_field('gg_contact_map_latitude'); ?>,
                    <?php endif; ?>

                    <?php if (get_sub_field('gg_contact_map_longitude')) : ?>
                    lon: <?php the_sub_field('gg_contact_map_longitude'); ?>,
                    <?php endif; ?>

                    icon : <?php echo json_encode($map_marker); ?>,

                    <?php if (get_sub_field('gg_contact_map_infowindow')): ?>
                    html: ['<?php echo preg_replace('/[\s]+/', '', get_sub_field('gg_contact_map_infowindow')); ?>'].join(''),
                    <?php endif; ?>

                    <?php if (get_sub_field('gg_contact_map_zoom')): ?>
                    zoom: <?php the_sub_field('gg_contact_map_zoom'); ?>,
                    <?php endif; ?>

                    <?php if (get_sub_field('gg_contact_map_menu_title')): ?>
                    title: '<?php the_sub_field('gg_contact_map_menu_title'); ?>'
                    <?php endif; ?>

                },

            <?php endwhile; ?>
        ],

        map_div: '#contact-map',
        map_options: myOptions

    });

    if($('a.gg-popup').length > 0) {
        $('a.gg-popup').click(function(event){
            contact_map.Load();
        });
    }

});//ready

})(jQuery, this);

</script>

<div id="gg-map-popup" class="mfp-with-anim mfp-hide">
    <div id="contact-map"></div>
</div>


<?php endif; ?>
<?php endif; ?>

<?php get_footer(); ?>