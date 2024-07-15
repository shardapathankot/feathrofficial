<?php
/**
 * Adds villenoir_Contact_Widget widget.
 */
class villenoir_Working_Hours_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'villenoir_Working_Hours_Widget', // Base ID
			esc_html__('Working Hours Widget', 'villenoir'), // Name
			array( 'description' => esc_html__( 'Display your business working hours', 'villenoir' ), 'classname' => 'working-hours', ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		$title         = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$monday_friday = ! empty( $instance['monday_friday'] ) ? $instance['monday_friday'] : '';
		$saturday      = ! empty( $instance['saturday'] ) ? $instance['saturday'] : '';
		$sunday        = ! empty( $instance['sunday'] ) ? $instance['sunday'] : '';
		$other_details = ! empty( $instance['other_details'] ) ? $instance['other_details'] : '';

		echo wp_kses_post($args['before_widget']);

		if ( ! empty( $title ) )
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );

		echo '<ul>';

		if ( $monday_friday )
			echo '<li>' . esc_html__('Monday - Friday', 'villenoir') . '<span>'.esc_html($monday_friday).'</span></li>';

		if ( $saturday )
			echo '<li>' . esc_html__('Saturday', 'villenoir') . '<span>'.esc_html($saturday).'</span></li>';

		if ( $sunday )
			echo '<li>' . esc_html__('Sunday', 'villenoir') . '<span>'.esc_html($sunday).'</span></li>';

		if ( $other_details )
			echo '<li class="other-details">'.esc_html($other_details).'</li>';

		echo '</ul>';

		echo wp_kses_post($args['after_widget']);
	}
	

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'monday_friday' => '', 'saturday' => '', 'sunday' => '', 'other_details' => '') );
		
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Working hours', 'villenoir' );

		$monday_friday = isset( $instance['monday_friday'] ) ? $instance['monday_friday'] : '';
		$saturday = isset( $instance['saturday'] ) ? $instance['saturday'] : '';
		$sunday = isset( $instance['sunday'] ) ? $instance['sunday'] : '';
		$other_details = esc_textarea($instance['other_details']);

		?>
		
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'villenoir'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_html($instance['title']); ?>" class="widefat" />
		</p>
		<!-- Monday - Friday: Text Input -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'monday_friday' )); ?>"><?php esc_html_e('Monday - Friday:', 'villenoir'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'monday_friday' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'monday_friday' )); ?>" value="<?php echo esc_html($instance['monday_friday']); ?>" class="widefat" />
		</p>
		<!-- Saturday: Text Input -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'saturday' )); ?>"><?php esc_html_e('Saturday:', 'villenoir'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'saturday' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'saturday' )); ?>" value="<?php echo esc_html($instance['saturday']); ?>" class="widefat" />
		</p>
		<!-- Sunday: Text Input -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'sunday' )); ?>"><?php esc_html_e('Sunday:', 'villenoir'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'sunday' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'sunday' )); ?>" value="<?php echo esc_html($instance['sunday']); ?>" class="widefat" />
		</p>
		<!-- Other details: Text Input -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'other_details' )); ?>"><?php esc_html_e('Other details:', 'villenoir'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('other_details')); ?>" name="<?php echo esc_attr($this->get_field_name('other_details')); ?>"><?php echo esc_textarea($other_details); ?></textarea>
		</p>
		<?php 
	}


	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['monday_friday'] = ( ! empty( $new_instance['monday_friday'] ) ) ? strip_tags( $new_instance['monday_friday'] ) : '';
		$instance['other_details'] =  $new_instance['other_details'];
		$instance['saturday'] = ( ! empty( $new_instance['saturday'] ) ) ? strip_tags( $new_instance['saturday'] ) : '';
		$instance['sunday'] = ( ! empty( $new_instance['sunday'] ) ) ? strip_tags( $new_instance['sunday'] ) : '';
		
		return $instance;
	}


} // class villenoir_Working_Hours_Widget

// register villenoir_Working_Hours_Widget 
function register_villenoir_Working_Hours_Widget() {
    register_widget( 'villenoir_Working_Hours_Widget' );
}
add_action( 'widgets_init', 'register_villenoir_Working_Hours_Widget' );