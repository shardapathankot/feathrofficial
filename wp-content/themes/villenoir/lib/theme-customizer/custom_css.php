<?php
/*Custom CSS*/

//Theme style
$theme_style = _get_field('gg_theme_style', 'option','light');

//Font
$gg_headings_font = _get_field('gg_headings_font', 'option', array('font' => 'Playfair Display' ));
$gg_body_font = _get_field('gg_body_font', 'option',array('font' => 'Lato' ));


/* ------------------------ Light ---------------------- */

//Text color
$gg_text_body_color = _get_field('gg_text_body_color', 'option','#000000');
$gg_headings_color = _get_field('gg_headings_color', 'option','#000000');
$gg_link_color = _get_field('gg_link_color', 'option','#b0976d');

//Primary color
$gg_primary_color = _get_field('gg_primary_color', 'option','#121212');
//Primary color alt
$gg_primary_color_alt = _get_field('gg_primary_color_alt', 'option','#000000');
//Modules background color
$gg_modules_color = _get_field('gg_modules_color', 'option','#f2f2f2');

/* ------------------------ Dark ---------------------- */

//Text color
$gg_text_body_color_dark = _get_field('gg_text_body_color_dark', 'option','#ffffff');
$gg_headings_color_dark = _get_field('gg_headings_color_dark', 'option','#ffffff');
$gg_link_color_dark = _get_field('gg_link_color_dark', 'option','#b0976d');

//Primary color
$gg_primary_color_dark = _get_field('gg_primary_color_dark', 'option','#121212');
//Primary color alt
$gg_primary_color_alt_dark = _get_field('gg_primary_color_alt_dark', 'option','#000000');
//Modules background color
$gg_modules_color_dark = _get_field('gg_modules_color_dark', 'option','#121212');

?>

<?php if ( $theme_style == 'light' ) : ?>

:root, 
html.gg-theme-style-light {
<?php if ( $gg_headings_font['font'] != 'Playfair Display' ) : ?>
	--headings-font: <?php echo esc_html($gg_headings_font['font']); ?>;
<?php endif; ?>

<?php if ( $gg_body_font['font'] != 'Lato' ) : ?>
	--body-font: <?php echo esc_html($gg_body_font['font']); ?>;
<?php endif; ?>

<?php if ( ($gg_text_body_color !='') && ($gg_text_body_color != '#000000') ) : ?>
	--text-body-color: <?php echo esc_html($gg_text_body_color); ?>;
<?php endif; ?>

<?php if ( ($gg_headings_color != '') && ($gg_headings_color != '#000000') ) : ?>
	--text-headings-color: <?php echo esc_html($gg_headings_color); ?>;
<?php endif; ?>

<?php if ( ($gg_link_color != '') && ($gg_link_color != '#b0976d') ) : ?>
	--links-elements-color: <?php echo esc_html($gg_link_color); ?>;
<?php endif; ?>

<?php if ( ($gg_primary_color != '') && ($gg_primary_color != '#121212') ) : ?>
	--primary-color: <?php echo esc_html($gg_primary_color); ?>;
<?php endif; ?>

<?php if ( ($gg_primary_color_alt !='') && ($gg_primary_color_alt != '#000000') ) : ?>
	--primary-color-alt: <?php echo esc_html($gg_primary_color_alt); ?>;
<?php endif; ?>

<?php if ( ($gg_modules_color !='') && ($gg_modules_color != '#f2f2f2') ) : ?>
	--modules-background-color: <?php echo esc_html($gg_modules_color); ?>;
<?php endif; ?>
}

<?php elseif ( $theme_style == 'dark' ) : ?>

html.gg-theme-style-dark { 
	<?php if ( $gg_headings_font['font'] != 'Playfair Display' ) : ?>
	--headings-font: <?php echo esc_html($gg_headings_font['font']); ?>;
	<?php endif; ?>

	<?php if ( $gg_body_font['font'] != 'Lato' ) : ?>
		--body-font: <?php echo esc_html($gg_body_font['font']); ?>;
	<?php endif; ?>

	<?php if ( ($gg_text_body_color_dark !='') && ($gg_text_body_color_dark != '#ffffff') ) : ?>
		--text-body-color: <?php echo esc_html($gg_text_body_color_dark); ?>;
	<?php endif; ?>

	<?php if ( ($gg_headings_color_dark != '') && ($gg_headings_color_dark != '#ffffff') ) : ?>
		--text-headings-color: <?php echo esc_html($gg_headings_color_dark); ?>;
	<?php endif; ?>

	<?php if ( ($gg_link_color_dark != '') && ($gg_link_color_dark != '#b0976d') ) : ?>
		--links-elements-color: <?php echo esc_html($gg_link_color_dark); ?>;
	<?php endif; ?>

	<?php if ( ($gg_primary_color_dark != '') && ($gg_primary_color_dark != '#121212') ) : ?>
		--primary-color: <?php echo esc_html($gg_primary_color_dark); ?>;
	<?php endif; ?>

	<?php if ( ($gg_primary_color_alt_dark !='') && ($gg_primary_color_alt_dark != '#000000') ) : ?>
		--primary-color-alt: <?php echo esc_html($gg_primary_color_alt_dark); ?>;
	<?php endif; ?>

	<?php if ( ($gg_modules_color_dark !='') && ($gg_modules_color_dark != '#121212') ) : ?>
		--modules-background-color: <?php echo esc_html($gg_modules_color_dark); ?>;
	<?php endif; ?>
}	
<?php endif; ?>
