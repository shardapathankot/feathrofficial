<?php
/**
 * Search Form Template
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<form method="get" id="searchform" class="<?php if (is_404()) echo 'form-horizontal'; else echo 'form-inline'; ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
	<input class="form-control <?php if (is_404()) echo 'input-lg'; ?>" type="text" value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search', 'villenoir' ); ?>" name="s" id="s" />
	<span class="input-group-btn">
		<input class="btn btn-primary <?php if (is_404()) echo 'btn-lg'; ?>" type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'villenoir' ); ?>" />
	</span>
	</div>
</form>

