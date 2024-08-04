<?php

/**
 * Define the Plugin Config
 *
 * Define the array of all admin settings fields
 * and they're infos
 *
 * @link       https://lucavisciola.com
 * @since      1.0.0
 *
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/includes
 */

/**
 * Define the settings fields.
 *
 * Define the array of all admin settings fields
 * and they're infos
 *
 * @since      1.0.0
 * @package    Melasistema_Amministrazione_Trasparente
 * @subpackage Melasistema_Amministrazione_Trasparente/includes
 * @author     Luca Visciola <info@melasistema.com>
 */
class Melasistema_Amministrazione_Trasparente_Plugin_Config {

    /**
     * The Plugin Settings.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $plugin_settings    All plugin settings.
     */
    protected array $plugin_settings;

    /**
     * Config the plugin settings.
     *
     * Configure all settings fields for the plugin
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_settings = [

            // Plugin Settings
            'melatransadmin_breadcrumbs' => array(
                'field_type' => 'checkbox',
                'class' => '',
                'section' => 'plugin_settings',
                'label' => __( 'Show Breadcrumbs', 'melasistema-amministrazione-trasparente' ),
                'description' => __( 'Check this to show breadcrumbs', 'melasistema-amministrazione-trasparente' ),
            ),

            'melatransadmin_delete_settings' => array(
                'field_type' => 'checkbox',
                'class' => '',
                'section' => 'plugin_settings',
                'label' => __( 'Delete Settings', 'melasistema-amministrazione-trasparente' ),
                'description' => __( 'Check this to delete all settings on plugin uninstall', 'melasistema-amministrazione-trasparente' ),
            ),

        ];

    }

	/**
	 * @return array|array[]
	 */
    public function load_plugin_settings(): array {
	    return $this->plugin_settings;
    }

}