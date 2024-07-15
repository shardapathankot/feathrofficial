<?php
/**
 * Adds villenoir_Social_Icons_Widget widget.
 */
class villenoir_Social_Icons_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'villenoir_Social_Icons_Widget', // Base ID
			esc_html__('Social Icons Widget', 'villenoir'), // Name
			array( 'description' => esc_html__( 'Display a list of social icons', 'villenoir' ), 'classname' => 'social-icons', ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo wp_kses_post($args['before_widget']);

		if ( ! empty( $title ) )
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		?>
		
		<?php if (_get_field('gg_social_icons','option')) : ?>
		<div class="social-icons-widget">
			<ul>
				<?php
		            while (has_sub_field('gg_social_icons','option')) { //Loop through sidebar fields to generate custom sidebars

		                $s_name = get_sub_field('gg_social_icon_name','option');
		                $s_icon = get_sub_field('gg_select_social_icon','option');
		            	$s_link = get_sub_field('gg_social_icon_link','option');

		            	if (is_rtl()) {
                            echo '<li><a href="'.esc_url($s_link).'" target="_blank">'.$s_name.'<i class="'.esc_attr($s_icon).'"></i></a></li>';
                        } else {
                            echo '<li><a href="'.esc_url($s_link).'" target="_blank"><i class="'.esc_attr($s_icon).'"></i>'.$s_name.'</a></li>';
                        }
		            	
		            }
				?>
			</ul>
		</div> <!-- .social-icons-widget -->
		<?php endif; ?>

		<?php
		echo wp_kses_post($args['after_widget']);
	}
	

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];

		?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'villenoir'); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" type="text" class="widefat" />
		</p>
		
		<?php 
	}


	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		return $instance;
	}


} // class villenoir_Social_Icons_Widget

// register villenoir_Social_Icons_Widget 
function register_villenoir_Social_Icons_Widget() {
    register_widget( 'villenoir_Social_Icons_Widget' );
}
add_action( 'widgets_init', 'register_villenoir_Social_Icons_Widget' );