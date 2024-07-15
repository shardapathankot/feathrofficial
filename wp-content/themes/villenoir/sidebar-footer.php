<?php
/**
 * The dynamically generated footer sidebar
 */
?>

<?php 
// count the active widgets to determine column sizes
$footerwidgets = is_active_sidebar('sidebar-footer-first') + is_active_sidebar('sidebar-footer-second') + is_active_sidebar('sidebar-footer-third') + is_active_sidebar('sidebar-footer-fourth');
// default
$footergrid = "col-xs-12 col-sm-6 col-md-3";
// if only one
if ($footerwidgets == "1") {
$footergrid = "col-xs-12 col-sm-12 col-md-12";
// if two, split in half
} elseif ($footerwidgets == "2") {
$footergrid = "col-xs-6 col-sm-6 col-md-6";
// if three, divide in thirds
} elseif ($footerwidgets == "3") {
$footergrid = "col-xs-4 col-sm-4 col-md-4";
// if four, split in fourths
} elseif ($footerwidgets == "4") {
$footergrid = "col-xs-12 col-sm-6 col-md-3";
}

?>

<?php if ($footerwidgets) : ?>

<div class="footer-widgets-holder">	
	<div class="row">	

		<?php if (is_active_sidebar('sidebar-footer-first')) : ?>
		<div class="<?php echo esc_attr($footergrid);?>">
			<?php dynamic_sidebar('sidebar-footer-first'); ?>
		</div>
		<?php endif;?>

		<?php if (is_active_sidebar('sidebar-footer-second')) : ?>
		<div class="<?php echo esc_attr($footergrid);?>">
			  <?php dynamic_sidebar('sidebar-footer-second'); ?>
		</div>
		<?php endif;?>

		<?php if (is_active_sidebar('sidebar-footer-third')) : ?>
		<div class="<?php echo esc_attr($footergrid);?>">
			  <?php dynamic_sidebar('sidebar-footer-third'); ?>
		</div>
		<?php endif;?>

		<?php if (is_active_sidebar('sidebar-footer-fourth')) : ?>
		<div class="<?php echo esc_attr($footergrid);?>">
				  <?php dynamic_sidebar('sidebar-footer-fourth'); ?>
		</div>
		<?php endif;?>

	</div>
</div>

<?php endif;?>