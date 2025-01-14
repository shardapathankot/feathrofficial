<?php
/**
 * Fullscreen Search Form
 *
 * @package WordPress
 * @subpackage villenoir
 */
?>

<div id="fullscreen-searchform">
    <button type="button" class="close">
    	<svg xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="12" height="12" viewBox="1.1 1.1 12 12" enable-background="new 1.1 1.1 12 12" xml:space="preserve"><path d="M8.3 7.1l4.6-4.6c0.3-0.3 0.3-0.8 0-1.2 -0.3-0.3-0.8-0.3-1.2 0L7.1 5.9 2.5 1.3c-0.3-0.3-0.8-0.3-1.2 0 -0.3 0.3-0.3 0.8 0 1.2L5.9 7.1l-4.6 4.6c-0.3 0.3-0.3 0.8 0 1.2s0.8 0.3 1.2 0L7.1 8.3l4.6 4.6c0.3 0.3 0.8 0.3 1.2 0 0.3-0.3 0.3-0.8 0-1.2L8.3 7.1z"></path></svg>
    </button>
    <form method="get" id="searchform" class="" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search for products', 'villenoir' ); ?>" name="s" id="s" />
		<button type="submit" id="searchsubmit" class="btn btn-primary"><?php esc_attr_e( 'Search', 'villenoir' ); ?></button>
	</form>
</div>
