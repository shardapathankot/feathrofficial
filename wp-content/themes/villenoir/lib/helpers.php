<?php
// Check if extension is activated
function villenoir_is_extension_activated( $extension = '' ) {
    return class_exists( $extension ) ? true : false;
}

// Verify if woocommerce is active
if ( ! function_exists( 'villenoir_is_wc_activated' ) ) {
	function villenoir_is_wc_activated() {
		if ( class_exists( 'woocommerce' ) ) { 
			return true;
		} else { 
			return false;
		}
	}
}

// Verify if WPML is active
if ( ! function_exists( 'villenoir_is_wpml_activated' ) ) {
	function villenoir_is_wpml_activated() {
		if ( class_exists( 'SitePressLanguageSwitcher' ) ) { 
			return true; 
		} else { 
			return false;
		}
	}
}

if ( villenoir_is_wpml_activated() ) {
    //Disable WPML styles
    define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
    define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
    define('ICL_DONT_LOAD_LANGUAGES_JS', true);
}

// Verify if Polylang is active
if ( ! function_exists( 'villenoir_is_polylang_activated' ) ) {
    function villenoir_is_polylang_activated() {
        if ( function_exists( 'pll_current_language' ) ) {
            return true;
        } else {
            return false;
        }
    }
}

// Set VC as part of the theme
if( function_exists('vc_set_as_theme') ) {

  add_action( 'vc_before_init', 'villenoir_vcSetAsTheme' );
  function villenoir_vcSetAsTheme() {
	  vc_set_as_theme();
  }

	$list = array(
		'page',
		'product',
	);
	vc_set_default_editor_post_types( $list );
}

// Hide activation and update specific parts of Slider Revolution
if( function_exists('set_revslider_as_theme') ) {

	add_action( 'init', 'villenoir_rev_slider' );
	function villenoir_rev_slider() {
		set_revslider_as_theme();
	}

	//Disable notices
	remove_action('admin_notices', array('RevSliderAdmin', 'add_plugins_page_notices'));
}

// Verify if is Blog page and Infinite loading is set
if ( ! function_exists( 'villenoir_blog_infinite_loading' ) ) {
	function villenoir_blog_infinite_loading() {
		//Get the ID of the page used for Blog posts
		$page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : get_the_ID() );
		$blog_pagination   = _get_field('gg_blog_pagination',$page_id,'numbered');
		if ( $blog_pagination == 'ajax_load' ) { 
			return true; 
		} else { 
			return false;
		}
	}
}

//Include the forms
include (get_template_directory() . '/lib/forms/cf_ajax.php');
include (get_template_directory() . '/lib/forms/cmf_ajax.php');

//Page container
if (! function_exists('villenoir_page_container')) :
function villenoir_page_container( $post_id = NULL ) {
		
		global $post, $product;
		

		if ( $post_id != FALSE || $post_id == 'special_page' ) {
			
			if (is_singular('product')) {
				$post = $post;
			} else {
				$post = get_post( $post_id );
			}
			
		}
		
		//Get page layout options
		if ( is_search() ) {
			$page_layout = _get_field('gg_search_page_layout', 'option', 'no_sidebar');
		} elseif ( villenoir_is_wc_activated() && ( is_shop() || is_product_category() ) ) {
			$page_layout = _get_field('gg_page_layout_select', get_option( 'woocommerce_shop_page_id' ), 'with_left_sidebar');
		//} elseif ( is_archive() || is_category() || is_tag() ) {
			//$page_layout = _get_field('gg_cat_page_layout', 'option', 'with_left_sidebar');
		} elseif ( is_singular('product') ) {
			//$page_layout = 'no_sidebar';
			$page_layout = _get_field('gg_page_layout_select', $post->ID, 'no_sidebar');
		} else {
			$page_layout = _get_field('gg_page_layout_select', $post->ID, 'no_sidebar');
		}


  switch ($page_layout) {
	  case "with_right_sidebar":
		  $page_content_class = 'col-xs-12 col-md-9 pull-left';
		  break;
	  case "with_left_sidebar":
		  $page_content_class = 'col-xs-12 col-md-9 pull-right';
		  break;
	  case "no_sidebar":
		  $page_content_class = 'col-xs-12 col-md-12';
		  break;
	  case NULL:
		  $page_content_class = 'col-xs-12 col-md-9 pull-left';
		  break;        
  }

  echo esc_attr($page_content_class);
}
endif;

//Page sidebar
if (! function_exists('villenoir_page_sidebar')) :
function villenoir_page_sidebar( $post_id = NULL ) {
	
		global $post, $product;

		if ( $post_id != FALSE || $post_id == 'special_page' ) {
			
			if (is_singular('product')) {
				$post = $post;
			} else {
				$post = get_post( $post_id );
			}
			
		}
		
		//Get page layout options
		if ( is_search() ) {
			$page_layout = _get_field('gg_search_page_layout', 'option','no_sidebar');
		} elseif ( villenoir_is_wc_activated() && ( is_shop() || is_product_category() ) ) {
			$page_layout = _get_field('gg_page_layout_select', get_option( 'woocommerce_shop_page_id' ), 'with_left_sidebar');
		//} elseif ( is_archive() || is_category() || is_tag() ) {
			//$page_layout = _get_field('gg_cat_page_layout', 'option','with_left_sidebar');
		} elseif ( is_singular('product') ) {
			//$page_layout = 'no_sidebar';
			$page_layout = _get_field('gg_page_layout_select', $post->ID, 'no_sidebar');
		} else {
			$page_layout = _get_field('gg_page_layout_select', $post->ID, 'no_sidebar');
		}

  switch ($page_layout) {
	  case "with_right_sidebar":
		  $page_sidebar_class = 'col-xs-12 col-md-3 pull-right';
		  break;
	  case "with_left_sidebar":
		   $page_sidebar_class = 'col-xs-12 col-md-3 pull-left';
		  break;
	  case "no_sidebar":
		  $page_sidebar_class = '';
		  break;
	  case NULL:
		  $page_sidebar_class = 'col-xs-12 col-md-3 pull-right';
		  break;        
  }

  //Add sticky option to sidebar
  $page_sticky_sidebar = _get_field('gg_site_sticky_sidebar', 'option',false);
  if ( $page_sticky_sidebar ) {
  	$page_sidebar_class .= ' sticky-sidebar';
  }
  

  if ($page_layout !== 'no_sidebar') {
  ?>
  <div class="<?php echo esc_attr($page_sidebar_class); ?>">
	  <aside class="sidebar-nav">
		  <?php get_sidebar(); ?>
	  </aside>
	  <!--/aside .sidebar-nav -->
  </div><!-- /.col-3 col-sm-3 col-lg-3 -->
  <?php } ?>
<?php }
endif;



//Get the global page id
if (! function_exists('villenoir_global_page_id')) :
	function villenoir_global_page_id() {
		$page_id = '';
	  
		if (villenoir_is_wc_activated() && is_shop()) {
		  $page_id = get_option('woocommerce_shop_page_id');
		} elseif (is_single() || is_page() || is_home()) {
		  $page_id = get_queried_object_id();
		} elseif (is_front_page() && 'page' == get_option('show_on_front')) {
		  $page_id = get_option('page_on_front');
		} elseif (is_home() && 'page' == get_option('show_on_front')) {
		  $page_id = get_option('page_for_posts');
		}
	  
		return $page_id;
	  }
endif;

if (! function_exists('villenoir_wp_title')) :
function villenoir_wp_title( $display = true ) {
  global $page, $paged;

  $search = get_query_var('s');
  $title = '';

  // If there is a post
  if ( is_single() || ( is_home() && !is_front_page() ) || ( is_page() && !is_front_page() ) ) {
	$title = single_post_title( '', false );
  }

  // If there's a post type archive
  if ( is_post_type_archive() ) {
	$post_type = get_query_var( 'post_type' );
	if ( is_array( $post_type ) )
	  $post_type = reset( $post_type );
	$post_type_object = get_post_type_object( $post_type );
	if ( ! $post_type_object->has_archive )
	  $title = post_type_archive_title( '', false );
  }

  // If there's a category or tag
  if ( is_category() || is_tag() ) {
	$title = single_term_title( '', false );
  }

  // If there's a taxonomy
  if ( is_tax() ) {
	$term = get_queried_object();
	if ( $term ) {
	  $tax = get_taxonomy( $term->taxonomy );
	  $title = single_term_title( false );
	}
  }

  // If there's an author
  if ( is_author() && ! is_post_type_archive() ) {
	$author = get_queried_object();
	if ( $author )
	  $title = $author->display_name;
  }

  // Post type archives with has_archive should override terms.
  if ( is_post_type_archive() && $post_type_object->has_archive )
	$title = post_type_archive_title( '', false );

  if ( is_year() || is_month() || is_day() ) {
	$title = get_the_archive_title();
  }

  // If it's a search
  if ( is_search() ) {
	/* translators: 1 search phrase */
	$title = sprintf(esc_html__('Search Results For: %1$s','villenoir'), strip_tags($search));
  }

  if ( villenoir_is_wc_activated() && (is_shop()) ) {
	$title = get_the_title(wc_get_page_id( 'shop' ));
  }

  if ( villenoir_is_wc_activated() && is_page() && is_wc_endpoint_url() ) {
	$endpoint = WC()->query->get_current_endpoint();
	if ( $endpoint_title = WC()->query->get_endpoint_title( $endpoint ) ) {
		$title = $endpoint_title;
	}
  }

  /**
   * Filter the text of the page title.
   *
   * @since 2.0.0
   *
   * @param string $title       Page title.
   */
  $title = apply_filters( 'villenoir_wp_title', $title );

  // Send it out
  if ( $display )
	echo $title;
  else
	return $title;

}

endif;

function villenoir_add_wpb_to_body_if_shortcode($classes) {
	global $post;
	if (isset($post->post_content) && false !== stripos($post->post_content, '[vc_row')) {
		array_push($classes, 'wpb-is-on');
	} else {
		array_push($classes, 'wpb-is-off');
	}
	return $classes;
}
add_filter('body_class', 'villenoir_add_wpb_to_body_if_shortcode', 100, 1);



/**
 * Display an optional post thumbnail.
 */
if ( ! function_exists( 'villenoir_post_thumbnail' ) ) :
function villenoir_post_thumbnail($single_image_position = '') {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }

    if ( is_single() && $single_image_position !== 'post_header' ) :
    ?>

    <div class="post-thumbnail">
        <?php the_post_thumbnail(); ?>
    </div><!-- .post-thumbnail -->

    <?php elseif ( !is_single() ) : ?>

    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
        <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) ); ?>
    </a>

    <?php endif; // End is_singular()
}
endif;

/**
 * Add class to next post link
 */
function villenoir_next_posts_link_css($content) { 
	return 'class="btn btn-primary"';
}
add_filter('next_posts_link_attributes', 'villenoir_next_posts_link_css' );


/**
 * Prints HTML with meta information for the categories, tags.
 */
if ( ! function_exists( 'villenoir_entry_meta' ) ) :
function villenoir_entry_meta() {
  if ( is_sticky() && is_home() && ! is_paged() ) {
	printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'villenoir' ) );
  }

  $format = get_post_format();
  if ( current_theme_supports( 'post-formats', $format ) ) {
	printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
	  sprintf( '<span>%s: </span>', _x( 'Format', 'Used before post format.', 'villenoir' ) ),
	  esc_url( get_post_format_link( $format ) ),
	  get_post_format_string( $format )
	);
  }

  if ( 'post' == get_post_type() ) {
	$tags_list = get_the_tag_list();
	if ( $tags_list ) {
	  printf( '<span class="tags-links">%1$s</span>',
		$tags_list
	  );
	}
  }

  if ( is_attachment() && wp_attachment_is_image() ) {
	// Retrieve attachment metadata.
	$metadata = wp_get_attachment_metadata();

	printf( '<span class="full-size-link"><span>%1$s: </span><a href="%2$s">%3$s &times; %4$s</a></span>',
	  _x( 'Full size', 'Used before full size attachment link.', 'villenoir' ),
	  esc_url( wp_get_attachment_url() ),
	  $metadata['width'],
	  $metadata['height']
	);
  }

  if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
	echo '<span class="comments-link">';
	comments_popup_link( esc_html__( 'Leave a comment', 'villenoir' ), esc_html__( '1 Comment', 'villenoir' ), esc_html__( '% Comments', 'villenoir' ) );
	echo '</span>';
  }
}
endif;


/**
 * Excerpt read more
 *
 */

function villenoir_excerpt_more( $more ) {
	return '<br class="read-more-spacer"> <a class="more-link" href="'. esc_url(get_permalink( get_the_ID() )) . '">' . esc_html__('Read More', 'villenoir') . '</a>';
}
add_filter( 'excerpt_more', 'villenoir_excerpt_more' );

/**
 * Tags widget style
 *
 */
function villenoir_tag_cloud_widget($args) {
	$args['number'] = 0; //adding a 0 will display all tags
	$args['largest'] = 11; //largest tag
	$args['smallest'] = 11; //smallest tag
	$args['unit'] = 'px'; //tag font unit
	$args['format'] = 'list'; //ul with a class of wp-tag-cloud
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'villenoir_tag_cloud_widget' );

/**
 * Determine whether blog/site has more than one category.
 *
 */
function villenoir_categorized_blog() {
  if ( false === ( $all_the_cool_cats = get_transient( 'gg_categories' ) ) ) {
	// Create an array of all the categories that are attached to posts.
	$all_the_cool_cats = get_categories( array(
	  'fields'     => 'ids',
	  'hide_empty' => 1,

	  // We only need to know if there is more than one category.
	  'number'     => 2,
	) );

	// Count the number of categories that are attached to the posts.
	$all_the_cool_cats = count( $all_the_cool_cats );

	set_transient( 'gg_categories', $all_the_cool_cats );
  }

  if ( $all_the_cool_cats > 1 ) {
	// This blog has more than 1 category so villenoir_categorized_blog should return true.
	return true;
  } else {
	// This blog has only 1 category so villenoir_categorized_blog should return false.
	return false;
  }
}

/**
 * Flush out the transients used in {@see villenoir_categorized_blog()}.
 *
 */
function villenoir_category_transient_flusher() {
  // Like, beat it. Dig?
  delete_transient( 'gg_categories' );
}
add_action( 'edit_category', 'villenoir_category_transient_flusher' );
add_action( 'save_post',     'villenoir_category_transient_flusher' );



function villenoir_check_email($email) {
	if (is_email($email)) {
		return 1;
	} else {
		return esc_html__( 'The entered email address isn\'t valid.', 'villenoir' );
	}
}

if (! function_exists('villenoir_hex_r')) :
function villenoir_hex_r($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
   //return $rgb; // returns an array with the rgb values
}
endif;

/**
 * Color shift a hex value by a specific percentage factor
 *
 * @param string $supplied_hex Any valid hex value. Short forms e.g. #333 accepted.
 * @param string $shift_method How to shift the value e.g( +,up,lighter,>)
 * @param integer $percentage Percentage in range of [0-100] to shift provided hex value by
 * @return string shifted hex value
 * @version 1.0 2008-03-28
 */
if (!function_exists('villenoir_hex_shift')) :
	function villenoir_hex_shift( $supplied_hex, $shift_method, $percentage = 50 ) {
		$shifted_hex_value = null;
		$valid_shift_option = FALSE;
		$current_set = 1;
		$RGB_values = array( );
		$valid_shift_up_args = array( 'up', '+', 'lighter', '>' );
		$valid_shift_down_args = array( 'down', '-', 'darker', '<' );
		$shift_method = strtolower( trim( $shift_method ) );

		// Check Factor
		if ( !is_numeric( $percentage ) || ($percentage = ( int ) $percentage) < 0 || $percentage > 100 ) {
			trigger_error( "Invalid factor", E_USER_ERROR );
		}

		// Check shift method
		foreach ( array( $valid_shift_down_args, $valid_shift_up_args ) as $options ) {
			foreach ( $options as $method ) {
				if ( $method == $shift_method ) {
					$valid_shift_option = !$valid_shift_option;
					$shift_method = ( $current_set === 1 ) ? '+' : '-';
					break 2;
				}
			}
			++$current_set;
		}

		if ( !$valid_shift_option ) {
			trigger_error( "Invalid shift method", E_USER_ERROR );
		}

		// Check Hex string
		switch ( strlen( $supplied_hex = ( str_replace( '#', '', trim( $supplied_hex ) ) ) ) ) {
			case 3:
				if ( preg_match( '/^([0-9a-f])([0-9a-f])([0-9a-f])/i', $supplied_hex ) ) {
					$supplied_hex = preg_replace( '/^([0-9a-f])([0-9a-f])([0-9a-f])/i', '\\1\\1\\2\\2\\3\\3', $supplied_hex );
				} else {
					trigger_error( "Invalid hex color value", E_USER_ERROR );
				}
				break;
			case 6:
				if ( !preg_match( '/^[0-9a-f]{2}[0-9a-f]{2}[0-9a-f]{2}$/i', $supplied_hex ) ) {
					trigger_error( "Invalid hex color value", E_USER_ERROR );
				}
				break;
			default:
				trigger_error( "Invalid hex color length", E_USER_ERROR );
		}

		// Start shifting
		$RGB_values['R'] = hexdec( $supplied_hex[0] . $supplied_hex[1] );
		$RGB_values['G'] = hexdec( $supplied_hex[2] . $supplied_hex[3] );
		$RGB_values['B'] = hexdec( $supplied_hex[4] . $supplied_hex[5] );

		foreach ( $RGB_values as $c => $v ) {
			switch ( $shift_method ) {
				case '-':
					$amount = round( ((255 - $v) / 100) * $percentage ) + $v;
					break;
				case '+':
					$amount = $v - round( ($v / 100) * $percentage );
					break;
				default:
					trigger_error( "Oops. Unexpected shift method", E_USER_ERROR );
			}

			$shifted_hex_value .= $current_value = (
				strlen( $decimal_to_hex = dechex( $amount ) ) < 2
				) ? '0' . $decimal_to_hex : $decimal_to_hex;
		}

		return '#' . $shifted_hex_value;
	}
endif;

/**
 * Display template for pagination.
 *
 */
if (!function_exists('villenoir_pagination')) :
function villenoir_pagination($query=null) { 
	
	$prev_arrow = is_rtl() ? '<i class="fa fa-chevron-right"></i>' : '<i class="fa fa-chevron-left"></i>';
	$next_arrow = is_rtl() ? '<i class="fa fa-chevron-left"></i>' : '<i class="fa fa-chevron-right"></i>';
	
	global $wp_query;
	$query = $query ? $query : $wp_query;
	$total = $query->max_num_pages;
	$big = 999999999; // need an unlikely integer

	if( !$current_page = get_query_var('paged') )
	  $current_page = 1;
	if( get_option('permalink_structure') ) {
	  $format = 'page/%#%/';
	} else {
	  $format = '&paged=%#%';
	}

	$paginate_links = paginate_links(array(
	  'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	  'format'    => $format,
	  'current'   => max( 1, get_query_var('paged') ),
	  'total'     => $total,
	  'mid_size'    => 3,
	  'type'      => 'list',
	  'prev_text'   => $prev_arrow,
	  'next_text'   => $next_arrow,
	 ) );

	$paginate_links = str_replace( "<ul class='page-numbers'>", "<ul class='pagination'>", $paginate_links );
	$paginate_links = str_replace( '<li><span class="page-numbers dots">', "<li><a href='#'>", $paginate_links );
	$paginate_links = str_replace( "<li><span class='page-numbers current'>", "<li class='current'><a href='#'>", $paginate_links );
	$paginate_links = str_replace( '</span>', '</a>', $paginate_links );
	$paginate_links = str_replace( "<li><a href='#'>&hellip;</a></li>", "<li><span class='dots'>&hellip;</span></li>", $paginate_links );
	$paginate_links = preg_replace( '/\s*page-numbers/', '', $paginate_links );
	// Display the pagination if more than one page is found
	if ( $paginate_links ) {
	  echo '<div class="pagination">';
	  echo wp_kses_post($paginate_links);
	  echo '</div><!--// end .pagination -->';
	}

}
endif;

/**
 * Display template for comments and pingbacks.
 *
 */
if (!function_exists('villenoir_comment')) :
	function villenoir_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type) :
			case 'pingback' :
			case 'trackback' : ?>

				<li <?php comment_class('media, comment'); ?> id="comment-<?php comment_ID(); ?>">
					<div class="media-body">
						<p>
							<?php esc_html_e('Pingback:', 'villenoir'); ?> <?php comment_author_link(); ?>
						</p>
					</div><!--/.media-body -->
				<?php
				break;
			default :
				// Proceed with normal comments.
				global $post; ?>

				<li <?php comment_class('media'); ?> id="li-comment-<?php comment_ID(); ?>">
						<a href="<?php echo esc_url($comment->comment_author_url); ?>" class="pull-left avatar-holder">
							<?php echo get_avatar($comment, 70); ?>
						</a>
						<div class="media-body">
							<h4 class="media-heading comment-author vcard">
								<?php
								printf('<cite class="fn">%1$s %2$s</cite>',
									get_comment_author_link(),
									// If current post author is also comment author, make it known visually.
									($comment->user_id === $post->post_author) ? '<span class="label"> ' . esc_html__(
										'Post author',
										'villenoir'
									) . '</span> ' : ''); ?>
							</h4>
							<p class="meta">
								<?php printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
										esc_url(get_comment_link($comment->comment_ID)),
										get_comment_time('c'),
										sprintf(
											esc_html__('%1$s at %2$s', 'villenoir'),
											get_comment_date(),
											get_comment_time()
										)
									); ?>
							</p>
							<p class="reply">
								<?php comment_reply_link( array_merge($args, array(
											'reply_text' => '<i class="arrow_back"></i> '. esc_html__('Reply', 'villenoir'),
											'depth'      => $depth,
											'max_depth'  => $args['max_depth']
										)
									)); ?>
							</p>

							<?php if ('0' == $comment->comment_approved) : ?>
								<p class="comment-awaiting-moderation"><?php esc_html_e(
									'Your comment is awaiting moderation.',
									'villenoir'
								); ?></p>
							<?php endif; ?>

							<?php comment_text(); ?>
													
						</div>
						<!--/.media-body -->
				<?php
				break;
		endswitch;
	}
endif;

/**
 * Function for aq_resize
 */
if (!function_exists('villenoir_aq_resize')) :
function villenoir_aq_resize( $attachment_id, $width = null, $height = null, $crop = true, $single = true ) {

	if ( is_null( $attachment_id ) ) 
		return null;

	$image = wp_get_attachment_image_src( $attachment_id, 'full' );

	$return = aq_resize( $image[0], $width, $height, $crop, $single );

	if ( $return ) {
		return $return;
	}
	else {
		return $image[0];
	}
}
endif;

/**
 * Check if element comes from VC
 */
if (!function_exists('villenoir_is_in_vc')) :
function villenoir_is_in_vc() {
	$classes = get_body_class();
	if (in_array('blog',$classes) || in_array('single',$classes) || in_array('search',$classes) || in_array('archive',$classes) || in_array('category',$classes)) {
		return false;
	} else {
		return true;
	}
}
endif;

/**
 * strpos with needles as an array function
 **/
if ( ! function_exists('villenoir_strpos_array') ) :
  function villenoir_strpos_array($haystack, $needles) {
	  if ( is_array($needles) ) {
		  foreach ($needles as $str) {
			  if ( is_array($str) ) {
				  $pos = villenoir_strpos_array($haystack, $str);
			  } else {
				  $pos = strpos($haystack, $str);
			  }
			  if ($pos !== FALSE) {
				  return $pos;
			  }
		  }
	  } else {
		  return strpos($haystack, $needles);
	  }
  }
endif;



/**
 * WPML Multi currency function
 **/
if ( ! function_exists('villenoir_currency_switcher') ) { 
	function villenoir_currency_switcher() {
	ob_start();
	if ( class_exists('woocommerce_wpml') && villenoir_is_wc_activated() ) {
	?>

	<a href="">
		<span><?php echo get_woocommerce_currency(); ?></span>
	</a>

	<?php
	echo villenoir_sub_menu_toggle(0);
	do_action('wcml_currency_switcher', array('format' => '%name% (%symbol%)', 'switcher_style' =>get_option('stylesheet').'-custom-currency-switcher')); ?>

	<?php
	}
	return ob_get_clean();
	} 
}

/**
 * WPML - Language dropdown with flags
 **/

if ( ! function_exists('villenoir_wpml_language_sel') ) {
	
	function villenoir_wpml_language_sel(){
		if (villenoir_is_wpml_activated()) {
			if (function_exists('icl_get_languages')) {
			  $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=custom&order=asc' );
			  if ($languages) {
				  $out = '';
				  $dropdown = '';
				  foreach ($languages as $lang) {
					  $lcode = explode('-',$lang['language_code']);
		   
					  if ($lang['active']) {
						  $button = '<a href="#"><span>'.$lang['language_code'].'</span></a>';
						  $button .= villenoir_sub_menu_toggle(0);
					  } else {
						  $dropdown .= '<li class="menu-item"><a href="'.esc_url($lang['url']).'" title="'.esc_html($lang['native_name']).'"><img src="'.esc_url($lang['country_flag_url']).'" alt="'.esc_html($lang['translated_name']).'" />'.$lang['language_code'].'</a></li>';
					  }
				  }
				  $out .= $button;
				  $out .= '<ul id="langswitch" class="sub-menu">'.$dropdown.'</ul>';
				  return $out;
			  }
			}
		}
	}
}

/**
 * Show Polylang Languages with Custom Markup
 * @param  string $class Add custom class to the languages container
 * @return string        
 */
function villenoir_polylang_languages() {

  if ( ! function_exists( 'pll_the_languages' ) ) return;

  // Gets the pll_the_languages() raw code
  $languages = pll_the_languages( array(
    'display_names_as'       => 'slug',
    'hide_if_no_translation' => 1,
    'raw'                    => true
  ) ); 

  $output = '';

  // Checks if the $languages is not empty
  if ( ! empty( $languages ) ) {

    // Creates the $output variable with languages container
    $output = '<li class="gg-language-switcher menu-item-has-children">';

    // Runs the loop through all languages
    foreach ( $languages as $language ) {

      // Variables containing language data
      $slug           = $language['slug'];
      $current        = $language['current_lang'] ? ' languages__item--current' : '';
      $no_translation = $language['no_translation'];

      // Checks if the page has translation in this language
      if ( ! $no_translation ) {
        // Check if it's current language
        if ( $current ) {
          // Output the language in a <span> tag so it's not clickable
          $output .= "<a href='#' class=\"languages__item$current\">$slug</a>";
          $output .= villenoir_sub_menu_toggle(0);
        } 

      }

    }

    $output .= "<ul class='sub-menu'>";
    // Runs the second loop through all languages
    foreach ( $languages as $lang ) {

      // Variables containing language data
      $slug_lang           = $lang['slug'];
      $url_lang            = $lang['url'];
      $current_lang        = $lang['current_lang'] ? ' languages__item--current' : '';
      $no_translation_lang = $lang['no_translation'];

      // Checks if the page has translation in this language
      if ( ! $no_translation_lang ) {
        // Check if it's current language
        if ( ! $current_lang ) {
          // Output the language in an anchor tag
          $output .= "<li class='menu-item'><a href=\"$url_lang\" class=\"languages__item$current\">$slug_lang</a></li>";
        }
      }
    }
    $output .= "</ul>";

    $output .= '</li>';

  }

  return $output;
}


function is_tribe_page($debug = false){
    $is_tribe_page = false;

    if( tribe_is_month() && !is_tax() ) { // Month View Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the month view page</p>'; }
    } elseif( tribe_is_month() && is_tax() ) { // Month View Category Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the month view category page</p>'; }
    } elseif( tribe_is_past() || tribe_is_upcoming() && !is_tax() ) { // List View Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the list view page</p>'; }
    } elseif( tribe_is_past() || tribe_is_upcoming() && is_tax() ) { // List View Category Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar an list view category page</p>'; }
    } elseif( tribe_is_week() && !is_tax() ) { // Week View Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the week view page</p>'; }
    } elseif( tribe_is_week() && is_tax() ) { // Week View Category Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the week view category page</p>'; }
    } elseif( tribe_is_day() && !is_tax() ) { // Day View Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the day view page</p>'; }
    } elseif( tribe_is_day() && is_tax() ) { // Day View Category Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar a day view category page</p>'; }
    } elseif( tribe_is_map() && !is_tax() ) { // Map View Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the map view page</p>'; }
    } elseif( tribe_is_map() && is_tax() ) { // Map View Category Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the map view category page</p>'; }
    } elseif( tribe_is_photo() && !is_tax() ) { // Photo View Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the photo view page</p>'; }
    } elseif( tribe_is_photo() && is_tax() ) { // Photo View Category Page
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar the photo view category page</p>'; }
    } elseif( tribe_is_event() && is_single() ) { // Single Events
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar a single event page</p>'; }
    } elseif( tribe_is_venue() ) { // Single Venues
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar a single venue page</p>'; }
    } elseif( get_post_type() == 'tribe_organizer' && is_single() ) { // Single Organizers
        $is_tribe_page = true;
        if($debug){ echo '<p class="tribe_page_debug">Tribe calendar a single organizer page</p>'; }
    } else {
        if($debug){ echo '<p class="tribe_page_debug">Not a tribe calendar page</p>'; }
    }

    return $is_tribe_page;
}