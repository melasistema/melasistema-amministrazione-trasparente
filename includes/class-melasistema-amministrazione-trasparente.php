<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://lucavisciola.com
 * @since      1.0.0
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/includes
 * @author     Luca Visciola <info@melasistema.com>
 */
class Melasistema_Amministrazione_Trasparente {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Melasistema_Amministrazione_Trasparente_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected Melasistema_Amministrazione_Trasparente_Loader $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected string $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected string $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MELASISTEMA_AMMINISTRAZIONE_TRASPARENTE_VERSION' ) ) {
			$this->version = MELASISTEMA_AMMINISTRAZIONE_TRASPARENTE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'melasistema-amministrazione-trasparente';

		$this->load_dependencies();
		$this->set_locale();
        $this->define_system_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Melasistema_Amministrazione_Trasparente_Loader. Orchestrates the hooks of the plugin.
	 * - Melasistema_Amministrazione_Trasparente_i18n. Defines internationalization functionality.
	 * - Melasistema_Amministrazione_Trasparente_System. Defines all hooks for the system.
	 * - Melasistema_Amministrazione_Trasparente_Admin. Defines all hooks for the admin area.
	 * - Melasistema_Amministrazione_Trasparente_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies(): void {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-melasistema-amministrazione-trasparente-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-melasistema-amministrazione-trasparente-i18n.php';

		/**
		 * The class responsible for holding the Plugin Config (settings fields).
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-melasistema-amministrazione-trasparente-plugin-config.php';

        /**
         * The class responsible for defining all actions that occur in the WP system
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'system/class-melasistema-amministrazione-trasparente-system.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-melasistema-amministrazione-trasparente-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-melasistema-amministrazione-trasparente-admin-options.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-melasistema-amministrazione-trasparente-public.php';

		$this->loader = new Melasistema_Amministrazione_Trasparente_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Melasistema_Amministrazione_Trasparente_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale(): void {

		$plugin_i18n = new Melasistema_Amministrazione_Trasparente_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

    /**
     * Register all hooks related to the WP system
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_system_hooks(): void {

        $plugin_system = new Melasistema_Amministrazione_Trasparente_System( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'init', $plugin_system, 'register_custom_post_type' );
        $this->loader->add_action( 'init', $plugin_system, 'register_custom_taxonomy' );
        // Remove it for now we leave empty voices for Transparent Administration
        // $this->loader->add_action( 'init', $plugin_system, 'add_default_taxonomy_data' );
    }

	/**
	 * Register all hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks(): void {

		$plugin_admin = new Melasistema_Amministrazione_Trasparente_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_options = new Melasistema_Amministrazione_Trasparente_Admin_Options( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_init', $plugin_options, 'melatransadmin_settings_init' );
		$this->loader->add_action( 'admin_menu', $plugin_options, 'melatransadmin_add_options_menu' );

		$this->loader->add_filter( 'upload_dir', $plugin_admin, 'mela_trans_upload_dir' );
		$this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_pdf_meta_box');
		$this->loader->add_action('post_edit_form_tag', $plugin_admin, 'mela_trans_backend_form_enctype');
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_pdf_meta_box' );

	}

	/**
	 * Register all hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks(): void {

		$plugin_public = new Melasistema_Amministrazione_Trasparente_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_filter( 'template_include', $plugin_public, 'transparent_administration_template_include' );
		$this->loader->add_action('template_redirect', $plugin_public, 'mela_trans_with_attachment_redirect');
		$this->loader->add_action( 'melatransadmin_breadcrumbs', $plugin_public, 'transparent_administration_breadcrumbs' );

	}

	/**
	 * Run the loader to execute all hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run(): void {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Melasistema_Amministrazione_Trasparente_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(): Melasistema_Amministrazione_Trasparente_Loader {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version(): string {
		return $this->version;
	}

}
