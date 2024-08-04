<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://lucavisciola.com
 * @since      1.0.0
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and settings
 * register and render plugin settings page
 *
 * @since      1.0.0
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/admin
 * @author     Luca Visciola <info@melasistema.com>
 */
class Melasistema_Amministrazione_Trasparente_Admin_Options  {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private string $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array   $plugin_settings    The plugin settings.
	 */
	protected array $plugin_settings;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
     * @since       1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// Call the Config Class
		$plugin_options = new Melasistema_Amministrazione_Trasparente_Plugin_Config();
		$this->plugin_settings = $plugin_options->load_plugin_settings();

	}

	/**
	 * @internal never define functions inside callbacks.
	 * these functions could be run multiple times; this would result in a fatal error.
	 */
	 
	/**
	 * Add Options fields
	 */
	public function melatransadmin_settings_init(): void {

	    add_settings_section(
	        'melatransadmin_section_plugin_settings',
	        __( 'Transparent Administration Settings', 'melasistema-amministrazione-trasparente' ),
	        array( $this,'melatransadmin_section_plugin_settings_callback' ),
	        'melatransadmin'
	    );


	    foreach($this->plugin_settings as $setting => $value) {

	    	$section = 'melatransadmin_section_' . $value['section'];

		    add_settings_field(
		    	
		    	$setting,
		    	__( $value['label'], 'melasistema-amministrazione-trasparente' ),
		    	array( $this, 'melatransadmin_render_fields' ),
		    	'melatransadmin',
		    	$section,
		    	array(
		    		'label_for'         => $setting,
		            'class'             => 'melatransadmin_row',
		            'melatransadmin_custom_data' => array(
		            	'field_type' => (!empty( $value['field_type'] )) ? $value['field_type'] : "",
		            	'class' => (!empty( $value['class'] )) ? $value['class'] : "",
		            	'label' => (!empty( $value['label'] )) ? $value['label'] : "",
		            	'description' => (!empty( $value['description'] )) ? $value['description'] : "",
		            	'select_placeholder' => (!empty( $value['select_placeholder'] )) ? $value['select_placeholder'] : "",
		            	'select_options' => (!empty( $value['select_options'] )) ? $value['select_options'] : "",
		            ),
		    	)
		    );
		};

		// Register a new setting for "melatransadmin" page.
	    register_setting( 'melatransadmin', 'melatransadmin_options' );

	}

	/**
     * Add submenu options page.
     *
	 * @return void
	 */
	public function melatransadmin_add_options_menu(): void {
		add_submenu_page(
			'edit.php?post_type=mela_trans_admin',
			__('Transparent Admin Options', 'melasistema-amministrazione-trasparente'),
			__('Options', 'melasistema-amministrazione-trasparente'),
			'administrator',
            'melatransadmin',
			array( $this, 'melatransadmin_options_page_html' ),
		);
	}
	 
	/**
	 * Render fields callback function. Used throught loop ... for now v.1
	 *
	 * WordPress has magic interaction with the following keys: label_for, class.
	 * - the "label_for" key value is used for the "for" attribute of the <label>.
	 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
	 * Note: you can add custom key value pairs to be used inside your callbacks.
	 *
	 * @param array $args
	 */
	public function melatransadmin_render_fields( $args ): void {

	    // Get the value of the setting we've registered with register_setting()
	    $options = get_option( 'melatransadmin_options' );
	    // Get options for populating selects
	    $select_options = $args['melatransadmin_custom_data']['select_options'];

		switch ($args['melatransadmin_custom_data']['field_type']) {

			case 'text': 
			case 'number':?>

				<input type="<?php echo esc_attr( $args['melatransadmin_custom_data']['field_type'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="<?php echo !empty( $args['melatransadmin_custom_data']['class'] ) ? $args['melatransadmin_custom_data']['class'] : "";   ?>" name="melatransadmin_options[<?php echo esc_attr( $args['label_for'] ); ?>]"  value="<?php echo empty( $options[$args['label_for']] ) ? "" : $options[$args['label_for']]; ?>">

				<?php break;

			case 'checkbox':?>

				<input type="<?php echo esc_attr( $args['melatransadmin_custom_data']['field_type'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="<?php echo !empty( $args['melatransadmin_custom_data']['class'] ) ? $args['melatransadmin_custom_data']['class'] : "";   ?>" name="melatransadmin_options[<?php echo esc_attr( $args['label_for'] ); ?>]" <?php echo ( !empty( $options[$args['label_for']] ) && $options[$args['label_for']] == true) ? "checked" : ""; ?>><?php //_e( $args['melatransadmin_custom_data']['description'], 'melasistema-amministrazione-trasparente' ); ?></input>

				<?php break;

			case 'multiple_select': ?>

				<select multiple type="<?php echo esc_attr( $args['melatransadmin_custom_data']['field_type'] ); ?>" title="<?php echo __($args['melatransadmin_custom_data']['select_placeholder'], 'melasistema-amministrazione-trasparente')  ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="<?php echo !empty( $args['melatransadmin_custom_data']['class'] ) ? $args['melatransadmin_custom_data']['class'] : "";   ?>" name="melatransadmin_options[<?php echo esc_attr( $args['label_for'] ); ?>][]">

					<?php if (!empty($select_options)) {

						foreach ($select_options as $key => $value) { ?>

							<option value="<?php echo $value; ?>" <?php echo ( isset( $options[$args['label_for']] ) && in_array( $value, $options[$args['label_for']] ) ) ? 'selected' : '' ?>><?php if ( $key ) { echo $key; } ?></option>

						<?php } 
						
					} ?>

				</select>
		
			<?php break;

			case 'select': ?>

				<select type="<?php echo esc_attr( $args['melatransadmin_custom_data']['field_type'] ); ?>" title="<?php echo __($args['melatransadmin_custom_data']['select_placeholder'], 'melasistema-amministrazione-trasparente')  ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="<?php echo !empty( $args['melatransadmin_custom_data']['class'] ) ? $args['melatransadmin_custom_data']['class'] : "";   ?>" name="melatransadmin_options[<?php echo esc_attr( $args['label_for'] ); ?>]">

					<?php if ( !empty( $this->languages ) ) {

						foreach ( $this->languages as $key => $value ) {

							// Remove some of the language codes not supported from simpleBooking
							if ( !strpos($value, "-" ) ) { ?>

								<option value="<?php echo $value; ?>" <?php echo ( isset($options[$args['label_for']]) && $value == $options[$args['label_for']] ) ? 'selected' : '' ?>><?php echo $key; ?></option>
							
							<?php }
						} 
						
					} ?>

				</select>
		
			<?php break;

			default:?>
				<input type="<?php echo esc_attr( $args['melatransadmin_custom_data']['field_type'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" class="<?php echo !empty( $args['melatransadmin_custom_data']['class'] ) ? $args['melatransadmin_custom_data']['class'] : "";   ?>" name="melatransadmin_options[<?php echo esc_attr( $args['label_for'] ); ?>]"  value="<?php echo empty( $options[$args['label_for']] ) ? "" : $options[$args['label_for']]; ?>">
			<?php break;
			
		}?>

	    <p class="description">
	        <?php esc_html_e( esc_attr( $args['melatransadmin_custom_data']['description'] ), 'melasistema-amministrazione-trasparente' ); ?>
	    </p>

	 <?php }

	/**
	 * Custom option and settings:
	 *  - callback functions
	 */
	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	function melatransadmin_section_plugin_settings_callback( $args ) { ?>

	    <div class="melatransadmin_settings_section_wrapper" style="background-image: url(<?php echo plugin_dir_url( __FILE__ ) . 'images/melatransadmin-plugin-settings-header.png'; ?>);">
			<div class="melatransadmin-spacer"></div>
		</div>

	<?php }
	  
	/**
	 * Top level menu callback function
	 */
	function melatransadmin_options_page_html() {
	    // check user capabilities
	    if ( ! current_user_can( 'manage_options' ) ) {
	        return;
	    }
	 
	    // add error/update messages
	    // check if the user have submitted the settings
	    // WordPress will add the "settings-updated" $_GET parameter to the url
	    if ( isset( $_GET['settings-updated'] ) ) {
	        // add settings saved message with the class of "updated"
	        add_settings_error( 'melatransadmin_messages', 'melatransadmin_message', __( 'Settings Saved', 'melasistema-amministrazione-trasparente' ), 'updated' );
	    }
	 
	    // show error/update messages
	    settings_errors( 'melatransadmin_messages' );
	    ?>
	    <div class="wrap">
	        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	        <form action="options.php" method="post">
	            <?php
	            // output security fields for the registered setting "melatransadmin"
	            settings_fields( 'melatransadmin' );
	            // output setting sections and their fields
	            // (sections are registered for "melatransadmin", each field is registered to a specific section)
	            do_settings_sections( 'melatransadmin' );
	            // output save settings button
	            submit_button( __('Save Settings', 'melasistema-amministrazione-trasparente') );
	            ?>
	        </form>
	    </div>
	    <?php
	}

}