<?php
// start gg_breadcrumb
function villenoir_breadcrumbs(array $options = array() ) {
	
	// default values assigned to options
	$options = array_merge(array(
		'crumbId'       => 'nav_crumb', // id for the breadcrumb Div
		'crumbClass'    => 'nav_crumb', // class for the breadcrumb Div
		'beginningText' => '', // text showing before breadcrumb starts
		'showOnHome'    => 1,// 1 - show breadcrumbs on the homepage, 0 - don't show
		'delimiter'     => ' <span class="delimiter">&frasl;</span> ', // delimiter between crumbs
		'homePageText'  => esc_html__('Home', 'villenoir'), // text for the 'Home' link
		'showCurrent'   => 1, // 1 - show current post/page title in breadcrumbs, 0 - don't show
		'beforeTag'     => '<span class="current">', // tag before the current breadcrumb
		'afterTag'      => '</span>', // tag after the current crumb
		'showTitle'     => 1, // showing post/page title or slug if title to show then 1
		'position'		=> 'left'
    ), $options);
   
	$crumbId       = $options['crumbId'];
	$crumbClass    = $options['crumbClass'];
	$beginningText = $options['beginningText'] ;
	$showOnHome    = $options['showOnHome'];
	$delimiter     = $options['delimiter']; 
	$homePageText  = $options['homePageText']; 
	$showCurrent   = $options['showCurrent']; 
	$beforeTag     = $options['beforeTag']; 
	$afterTag      = $options['afterTag']; 
	$showTitle     = $options['showTitle']; 
	$crumbPosition = $options['position']; 
	
	global $post;

	$wp_query = $GLOBALS['wp_query'];

	$homeLink = esc_url( home_url( '/' ) );
	
	echo '<div id="'.esc_attr($crumbId).'" class="'.esc_attr($crumbClass).' '.esc_attr($crumbPosition).'" >'.esc_html($beginningText);
	
	if ( is_home() || is_front_page() ) {
	
		if ($showOnHome == 1)
			echo wp_kses_post($beforeTag) . esc_html($homePageText) . wp_kses_post($afterTag);

	} else { 
		
	  	if ( villenoir_is_wc_activated() && !is_woocommerce() ) { 		
			echo '<a href="' . esc_url($homeLink) . '">' . esc_html($homePageText) . '</a> ' . wp_kses_post($delimiter) . ' ';
		}
	  
		if (villenoir_is_wc_activated() && is_woocommerce()) {
        	
        	echo woocommerce_breadcrumb();

    	} elseif ( is_category() ) {
			
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0)
				echo get_category_parents($thisCat->parent, TRUE, ' ' . wp_kses_post($delimiter) . ' ');
			echo wp_kses_post($beforeTag) . 'Archive by category "' . single_cat_title('', false) . '"' . wp_kses_post($afterTag);
		
		} elseif ( is_tax() ) {
			
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

			$parents = array();
			$parent = $term->parent;
			while ( $parent ) {
				$parents[] = $parent;
				$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
				$parent = $new_parent->parent;
		  	}		  
		  	if ( ! empty( $parents ) ) {
				$parents = array_villenoir( $parents );
				foreach ( $parents as $parent ) {
					$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
					echo '<a href="' . get_term_link( $item->slug, get_query_var( 'taxonomy' ) ) . '">' . $item->name . '</a>'  . wp_kses_post($delimiter);
			  	}
		  	}

		  	$queried_object = $wp_query->get_queried_object();
			echo wp_kses_post($beforeTag) . $queried_object->name . wp_kses_post($afterTag);	 

		} elseif ( is_search() ) {
		
			echo wp_kses_post($beforeTag) . 'Search results for "' . get_search_query() . '"' . wp_kses_post($afterTag);
	
		} elseif ( is_day() ) {
			
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . wp_kses_post($delimiter) . ' ';
			echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . wp_kses_post($delimiter) . ' ';
			echo wp_kses_post($beforeTag) . get_the_time('d') . wp_kses_post($afterTag);
	
		} elseif ( is_month() ) {
			
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . wp_kses_post($delimiter) . ' ';
			echo wp_kses_post($beforeTag) . get_the_time('F') . wp_kses_post($afterTag);
	
		} elseif ( is_year() ) {
			
			echo wp_kses_post($beforeTag) . get_the_time('Y') . wp_kses_post($afterTag);
	
		} elseif ( is_single() && !is_attachment() ) {
		  
		    if($showTitle)
				$title = get_the_title();
			else
				$title =  $post->post_name;
		  
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					echo '<a href="' . esc_url($homeLink) . '/' . $slug['slug'] . '/">' . $post_type->labels->name . '</a>';
					if ($showCurrent == 1) 
						echo ' ' . wp_kses_post($delimiter) . ' ' . wp_kses_post($beforeTag) . $title . wp_kses_post($afterTag);
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, ' ' . wp_kses_post($delimiter) . ' ');
					if ($showCurrent == 0) 
						$cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
					echo wp_kses_post($cats);
					if ($showCurrent == 1) 
						echo wp_kses_post($beforeTag) . $title . wp_kses_post($afterTag);
				}

		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
		  	
		  	$post_type = get_query_var( 'post_type' );
		  	$post_type_object = get_post_type_object( $post_type );
			echo wp_kses_post($beforeTag) . $post_type_object->labels->name . wp_kses_post($afterTag);
			 
		} elseif ( is_attachment() ) {
			 
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);

			if ($cat) {
				$cat = $cat[0];
				echo get_category_parents($cat, TRUE, ' ' . wp_kses_post($delimiter) . ' ');
			}
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
			if ($showCurrent == 1)
				echo ' ' . wp_kses_post($delimiter) . ' ' . wp_kses_post($beforeTag) . get_the_title() . wp_kses_post($afterTag);	
			  
		} elseif ( is_page() && !$post->post_parent ) {
				
			$title = ($showTitle) ? get_the_title() : $post->post_name;
			if ($showCurrent == 1) 
				echo wp_kses_post($beforeTag) .  $title . wp_kses_post($afterTag);

	 	} elseif ( is_page() && $post->post_parent ) {
			
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo wp_kses_post($breadcrumbs[$i]);
				if ($i != count($breadcrumbs)-1)
					echo ' ' . wp_kses_post($delimiter) . ' ';
			}
			$title = ($showTitle) ? get_the_title() : $post->post_name;
			if ($showCurrent == 1)
				echo ' ' . wp_kses_post($delimiter) . ' ' . wp_kses_post($beforeTag) . $title . wp_kses_post($afterTag);

		} elseif ( is_tag() ) {

			echo wp_kses_post($beforeTag) . esc_html__('Posts tagged', 'villenoir') . ' " ' . single_tag_title('', false) . ' "' . wp_kses_post($afterTag);

		} elseif ( is_author() ) {
			
			global $author;
			$userdata = get_userdata($author);
			echo wp_kses_post($beforeTag) . esc_html__('Articles posted by', 'villenoir') . $userdata->display_name . wp_kses_post($afterTag);

		} elseif ( is_404() ) {
			
			echo wp_kses_post($beforeTag) . esc_html__('Error 404', 'villenoir') . wp_kses_post($afterTag);

		}

		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_tax() ) 
				echo ' (';
			echo wp_kses_post($delimiter) . esc_html__('Page', 'villenoir') . ' ' . get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_tax() ) 
				echo ')';
		}
	}
	echo '</div>';
}
// end gg_breadcrumb