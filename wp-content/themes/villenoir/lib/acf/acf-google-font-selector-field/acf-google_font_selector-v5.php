<?php
/**
 * ACF 5 Field Class
 *
 * This file holds the class required for our field to work with ACF 5
 *
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */

/**
 * ACF 5 Role Selector Class
 *
* The Google Font selector class enables users to select webfonts from
 * The Google Fonts service. This is the class that is used for ACF 5.
 *
 * @author Daniel Pataki
 * @since 3.0.0
 *
 */
class villenoir_acf_field_google_font_selector extends acf_field {

	/**
	 * Field Constructor
	 *
	 * Sets basic properties and runs the parent constructor
	 *
	 * @author Daniel Pataki
	 * @since 3.0.0
	 *
	 */
	function __construct() {
		$this->name     = 'google_font_selector';
		$this->label    = esc_html__( 'Google Font Selector', 'villenoir');
		$this->category = esc_html__( 'Choice' , 'villenoir' );

		$this->defaults = array(
			'include_web_safe_fonts' => true,
			'enqueue_font'           => true,
			'default_font'           => 'Open Sans',
		);

    	parent::__construct();

		add_action( 'wp_ajax_villenoir_get_font_details', 'villenoir_action_get_font_details' );

		add_action( 'wp_enqueue_scripts', 'villenoir_google_font_enqueue' );

	}


	/**
	 * Field Options
	 *
	 * Creates the options for the field, they are shown when the user
	 * creates a field in the back-end. Currently there are three fields.
	 *
	 * The Web Safe Fonts setting allows you to add regular fonts available
	 * in any browser to the list
	 *
	 * The Enqueue Font setting will load the fonts on the appropriate page
	 * when checked.
	 *
	 * The default font settings allows you to specify the font set as the
	 * default for the field.
	 *
	 * @param array $field The details of this field
	 * @author Daniel Pataki
	 * @since 3.0.0
	 *
	 */
	function render_field_settings( $field ) {


		acf_render_field_setting( $field, array(
			'label'			=> esc_html__('Web Safe Fonts?', 'villenoir'),
			'message'    	=> esc_html__('Include web safe fonts?', 'villenoir'),
			'type'			=> 'true_false',
			'name'			=> 'include_web_safe_fonts',
			'layout'		=> 'horizontal',
		));

		acf_render_field_setting( $field, array(
			'label'			=> esc_html__('Enqueue Font?', 'villenoir'),
			'message'    	=> esc_html__('Automatically load font?', 'villenoir'),
			'type'			=> 'true_false',
			'name'			=> 'enqueue_font',
			'layout'		=> 'horizontal',
		));

		acf_render_field_setting( $field, array(
			'label'			=> esc_html__('Default Font', 'villenoir'),
			'type'			=> 'select',
			'name'			=> 'default_font',
			'choices'       => villenoir_get_font_dropdown_array()
		));


	}


	/**
	 * Field Display
	 *
	 * This function takes care of displaying our field to the users, taking
	 * the field options into account.
	 *
	 * @param array $field The details of this field
	 * @author Daniel Pataki
	 * @since 3.0.0
	 *
	 */
	function render_field( $field ) {

		$current_font_family = ( empty( $field['value'] ) ) ? $field['default_font'] : $field['value']['font'];
		?>
		<div class="acfgfs-font-selector">
			<div class="acfgfs-loader"></div>
			<div class="acfgfs-form-control acfgfs-font-family">
				<div class="acfgfs-form-control-title"><?php esc_html_e('Font Family', 'villenoir') ?></div>

				<select name="<?php echo esc_attr($field['name']) ?>">
					<?php
					$options = villenoir_get_font_dropdown_array( $field );
					foreach( $options as $option ) {
						echo '<option ' . selected( $option, $current_font_family ) . ' value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
					}
					?>
				</select>

				<?php $font = str_replace( ' ', '+', $current_font_family ); ?>
				<div class='acfgfs-preview'>
					
					<?php if ( ! villenoir_check_web_safe_fonts($current_font_family) ) : ?>
						<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo esc_attr($font); ?>">
					<?php endif; ?>

					<div style='font-family:<?php echo esc_attr( $current_font_family ); ?>'>
						<?php esc_html_e( 'This is a preview of the selected font', 'villenoir' ) ?>
					</div>
				</div>

			</div>

			<div class="acfgfs-form-control acfgfs-font-variants">
				<div class="acfgfs-form-control-title"><?php esc_html_e('Variants', 'villenoir') ?></div>
				<div class="acfgfs-list">
					<?php villenoir_display_variant_list( $field ) ?>
				</div>

			</div>

			<div class="acfgfs-form-control acfgfs-font-subsets">
				<div class="acfgfs-form-control-title"><?php esc_html_e('Subsets', 'villenoir') ?></div>
				<div class="acfgfs-list">
					<?php villenoir_display_subset_list( $field ) ?>
				</div>

			</div>

			<textarea name="acfgfs-font-data" class="acfgfs-font-data"><?php echo json_encode( $field ) ?></textarea>

		</div>

		<?php
	}

	/**
	 * Enqueue Assets
	 *
	 * This function enqueues the scripts and styles needed to display the
	 * field
	 *
	 * @author Daniel Pataki
	 * @since 3.0.0
	 *
	 */
	function input_admin_enqueue_scripts() {

		$dir = get_template_directory_uri() . '/lib/acf/acf-google-font-selector-field/';

		wp_enqueue_script( 'acf-input-google_font_selector', "{$dir}js/input.js" );
		wp_enqueue_style( 'acf-input-google_font_selector', "{$dir}css/input.css" );

	}

	/**
	 * Pre-Save Value Modification
	 *
	 * This filter is applied to the $value before it is updated in the db
	 *
	 * @param mixed $value The value which will be saved in the database
	 * @param int $post_id The $post_id of which the value will be saved
	 * @param array $field The field array holding all the field options
	 * @return mixed The new value
	 * @author Daniel Pataki
	 * @since 3.0.0
	 *
	 */
	function update_value( $value, $post_id, $field ) {
		$new_value = array();
		$new_value['font'] = $value;
		if( empty( $_POST[$field['key'] . '_variants'] ) ) {
			$_POST[$field['key'] . '_variants'] = villenoir_get_font_variant_array( $new_value['font'] );
		}

		if( empty( $_POST[$field['key'] . '_subsets'] ) ) {
			$_POST[$field['key'] . '_subsets'] = villenoir_get_font_subset_array( $new_value['font'] );
		}

		$new_value['variants'] = $_POST[$field['key'] . '_variants'];
		$new_value['subsets'] = $_POST[$field['key'] . '_subsets'];
		return $new_value;
	}

}


// create field
new villenoir_acf_field_google_font_selector();

?>
