<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://lucavisciola.com
 * @since      1.0.0
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/public
 * @author     Luca Visciola <info@melasistema.com>
 */
class Melasistema_Amministrazione_Trasparente_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/melasistema-amministrazione-trasparente-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/melasistema-amministrazione-trasparente-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * @return void
	 */
	public function mela_trans_with_attachment_redirect(): void {
		global $post;

		if (is_singular('mela_trans_admin')) {
			$pdf_file = get_post_meta($post->ID, 'mela_trans_pdf_file', true);
			if (!empty($pdf_file)) {
				wp_redirect(get_post_type_archive_link('mela_trans_admin'));
				exit;
			}
		}
	}

    /**
     * @param $template
     * @return mixed
     */
    function transparent_administration_template_include( $template ): mixed {
        $templates = array(
            'archive-mela_trans_admin' => dirname(__FILE__) . '/partials/archive-mela_trans_admin.php',
            'single-mela_trans_admin' => dirname(__FILE__) . '/partials/single-mela_trans_admin.php'
        );

        foreach ( $templates as $template_name => $template_path ) {
            if ( is_file( $template_path ) && (
                    is_archive() && $template_name === 'archive-mela_trans_admin' ||
                    is_single() && $template_name === 'single-mela_trans_admin'
                )
            ) {
                return $template_path;
            }
        }

        return $template;
    }

	/**
	 * @return void
	 */
	function transparent_administration_breadcrumbs(): void {
		global $post;

		$options = get_option('melatransadmin_options');
		$melatransadmin_breadcrumbs = isset($options['melatransadmin_breadcrumbs']) ? $options['melatransadmin_breadcrumbs'] : '';

		if (!$melatransadmin_breadcrumbs) {
			return;
		}

		echo '<div class="breadcrumb-column mela-breadcrumbs">';

		echo '<a href="' . esc_url(home_url()) . '">Home</a> / ';

		$baseArchive = '<a href="' . get_post_type_archive_link('mela_trans_admin') . '">'.__('Transparent Administration', 'melasistema-amministrazione-trasparente').'</a>';

		if (is_archive() && is_post_type_archive('mela_trans_admin')) {
			echo $baseArchive;
		} elseif (is_single() && get_post_type() === 'mela_trans_admin') {
			echo $baseArchive . ' / ';

			$terms = get_the_terms($post->ID, 'mela_trans_section');
			if ($terms && !is_wp_error($terms)) {
				foreach ($terms as $term) {
					/*echo '<span class="mela-breadcrumb-section">' . $term->name . '</span> / ';*/
					$term_slug = $term->slug;
					$anchor_id = "category-" . $term_slug;
					echo '<a href="'. get_post_type_archive_link('mela_trans_admin') .'#'.$anchor_id.'">' . $term->name . '</a> / ';

				}
			}

			$title = get_the_title();
			echo '<span>'.$title.'</span>';
		}

		echo '</div>';
	}

}
