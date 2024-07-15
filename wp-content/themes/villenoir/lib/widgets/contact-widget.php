<?php
/**
 * Adds villenoir_Contact_Widget widget.
 */
class villenoir_Contact_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'villenoir_Contact_Widget', // Base ID
			esc_html__('Contact Widget', 'villenoir'), // Name
			array( 'description' => esc_html__( 'Contact us Widget', 'villenoir' ), 'classname' => 'contact', ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';
		$address_directions = ! empty( $instance['address_directions'] ) ? $instance['address_directions'] : '';
		
		$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
		$fax = ! empty( $instance['fax'] ) ? $instance['fax'] : '';
		$email = ! empty( $instance['email'] ) ? $instance['email'] : '';

		$extra_details = ! empty( $instance['extra_details'] ) ? $instance['extra_details'] : '';

		echo wp_kses_post($args['before_widget']);

		if ( ! empty( $title ) )
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );

		if ( $address )
			echo '<address>'.wp_kses_post($address).' </address>';
		if ( $address_directions )
			echo '<p><a href="//www.google.com/maps/dir/Current+Location/'.esc_html($address_directions).'">' . esc_html__('Get directions', 'villenoir') . '</a></p>';

		echo '<div class="clearfix contact-separator"></div>';

		if ( $phone )
			echo '<p>'.esc_html($phone).' </p>';
		if ( $fax )
			echo '<p>'.esc_html($fax).' </p>';
		if ( $email )
			echo '<p><a href="mailto:'.antispambot($email,1).'">'.antispambot($email).'</a></p>';

		if ( $extra_details )
			echo '<div class="clearfix"></div><div class="extra_details">'.esc_html($extra_details).' </div>';	

		echo wp_kses_post($args['after_widget']);
	}
	

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'address' => '', 'address_directions' => '','phone' => '','fax' => '','email' => '','extra_details' => '') );
		
		$title     			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$address     		= isset( $instance['address'] ) ? esc_textarea( $instance['address'] ) : '';
		$address_directions	= isset( $instance['address_directions'] ) ? esc_attr( $instance['address_directions'] ) : '';

		$phone				= isset( $instance['phone'] ) ? esc_attr( $instance['phone'] ) : '';
		$fax				= isset( $instance['fax'] ) ? esc_attr( $instance['fax'] ) : '';
		$email				= isset( $instance['email'] ) ? esc_attr( $instance['email'] ) : '';

		$extra_details		= isset( $instance['extra_details'] ) ? esc_attr( $instance['extra_details'] ) : '';
		?>
		
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'villenoir'); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>
		
		<!-- Your Phone: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e('Your Phone:', 'villenoir'); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" value="<?php echo esc_attr( $phone ); ?>" class="widefat" />
		</p>
		<!-- Your Fax: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"><?php esc_html_e('Your Fax:', 'villenoir'); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fax' ) ); ?>" value="<?php echo esc_attr( $fax ); ?>" class="widefat" />
		</p>

		<!-- Your E-mail: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e('Your Email:', 'villenoir'); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" value="<?php echo esc_attr( $email ); ?>" class="widefat" />
		</p>

		<!-- Your Address: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e('Your Address:', 'villenoir'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name('address') ); ?>"><?php echo esc_attr($address); ?></textarea>
		</p>
		<!-- Address: Directions -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address_directions' ) ); ?>"><?php esc_html_e('Directions:', 'villenoir'); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'address_directions' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address_directions' ) ); ?>" value="<?php echo esc_attr( $address_directions ); ?>" class="widefat" />
			<span><?php esc_html_e('Insert the longitude and latitude coordinates separated by comma. E.g.: 43.2238916,-76.2575936', 'villenoir'); ?></span>
		</p>

		<!-- Extra details: Textarea -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'extra_details' ) ); ?>"><?php esc_html_e('Extra details:', 'villenoir'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'extra_details' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name('extra_details') ); ?>"><?php echo esc_attr( $extra_details ); ?></textarea>
		</p>

		<?php 
	}


	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['address_directions'] =  $new_instance['address_directions'];
		$instance['address'] =  $new_instance['address'];

		$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? $new_instance['phone'] : '';
		$instance['fax'] = ( ! empty( $new_instance['fax'] ) ) ? $new_instance['fax'] : '';
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? $new_instance['email'] : '';

		$instance['extra_details'] = ( ! empty( $new_instance['extra_details'] ) ) ? $new_instance['extra_details'] : '';
		
		return $instance;
	}


} // class villenoir_Contact_Widget

// register villenoir_Contact_Widget 
function register_villenoir_Contact_Widget() {
    register_widget( 'villenoir_Contact_Widget' );
}
add_action( 'widgets_init', 'register_villenoir_Contact_Widget' );