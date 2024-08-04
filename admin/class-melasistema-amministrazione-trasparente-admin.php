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
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/admin
 * @author     Luca Visciola <info@melasistema.com>
 */
class Melasistema_Amministrazione_Trasparente_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Melasistema_Amministrazione_Trasparente_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Melasistema_Amministrazione_Trasparente_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/melasistema-amministrazione-trasparente-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Melasistema_Amministrazione_Trasparente_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Melasistema_Amministrazione_Trasparente_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/melasistema-amministrazione-trasparente-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * @param $post
	 *
	 * @return void
	 */
	public function mela_trans_backend_form_enctype($post): void {
		if ($post->post_type === 'mela_trans_admin') {
			echo ' enctype="multipart/form-data"';
		}
	}

	/**
	 * @param $uploads
	 *
	 * @return mixed
	 */
	function mela_trans_upload_dir( $uploads ): mixed {
		$uploads['path'] = WP_CONTENT_DIR . '/uploads/mela-trans-admin';
		$uploads['url'] = WP_CONTENT_URL . '/uploads/mela-trans-admin';
		$uploads['subdir'] = ''; // This disables the default subdirectory structure

		return $uploads;
	}

	/**
	 * @param $post
	 *
	 * @return void
	 */
	public function add_pdf_meta_box($post): void {
		$screen = get_current_screen();
		if ($screen->post_type == 'mela_trans_admin') {
			add_meta_box(
				'mela_trans_pdf_upload',
				__('Use PDF for this Voice', 'melasistema-amministrazione-trasparente'),
				[$this, 'pdf_meta_box_callback'],
				'mela_trans_admin',
				'side'
			);
		}
	}

	/**
	 * @param $post
	 *
	 * @return void
	 */
	public function pdf_meta_box_callback( $post ): void {
		if ( ! is_a( $post, 'WP_Post' ) ) {
			return;
		}

		wp_nonce_field( basename( __FILE__ ), 'mela_trans_pdf_meta_box_nonce' );
		$pdf_file = get_post_meta( $post->ID, 'mela_trans_pdf_file', true );
		?>
        <input type="file" name="mela_trans_pdf_file" id="mela_trans_pdf_file">
		<?php if ( $pdf_file ): ?>
            <p>Current PDF: <a href="<?php echo esc_url( $pdf_file ); ?>" target="_blank"><?php echo esc_html( basename( $pdf_file ) ); ?></a></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * @param $post_id
	 *
	 * @return bool|WP_Error
	 */
	public function save_pdf_meta_box( $post_id ): WP_Error|bool {
		// Verify nonce and permissions
		$nonce_field = 'mela_trans_pdf_meta_box_nonce';

		if (!$_POST[$nonce_field] || !wp_verify_nonce($_POST[$nonce_field], basename(__FILE__))) {
			return new WP_Error('nonce_verification_failed', __('Nonce verification failed.', 'melasistema-amministrazione-trasparente'));
		}

		if (!current_user_can('edit_post', $post_id)) {
			return new WP_Error('insufficient_permissions', __('Insufficient permissions.', 'melasistema-amministrazione-trasparente'));
		}

		$old_pdf_file = get_post_meta( $post_id, 'mela_trans_pdf_file', true );

		// Handle file upload
		if (isset($_FILES['mela_trans_pdf_file'])) {
			$file = $_FILES['mela_trans_pdf_file'];

			// Check file type (optional)
			if ($file['type'] !== 'application/pdf') {
				return new WP_Error('invalid_file_type', __('Invalid file type.', 'melasistema-amministrazione-trasparente'));
			}

			$upload_dir = wp_upload_dir();

			$file_path = $upload_dir['path'] . '/' . basename($file['name']);

			if (move_uploaded_file($file['tmp_name'], $file_path)) {
				$file_url = $upload_dir['url'] . '/' . basename($file['name']);
				update_post_meta($post_id, 'mela_trans_pdf_file', $file_url);
			} else {
				return new WP_Error('upload_failed', __('File upload failed.', 'melasistema-amministrazione-trasparente'));
			}
		}

		// Delete old file if it exists
		if ( $old_pdf_file ) {
			$upload_dir = wp_upload_dir();
			$old_file_path = str_replace( $upload_dir['url'], $upload_dir['path'], $old_pdf_file );
			if ( file_exists( $old_file_path ) ) {
				unlink( $old_file_path );
			}
		}

		return true;
	}

}
