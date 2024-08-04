<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lucavisciola.com
 * @since             1.0.0
 * @package           Melasistema_Amministrazione_Trasparente
 *
 * @wordpress-plugin
 * Plugin Name:       Amministrazione Trasparente
 * Plugin URI:        https://melasistema.com
 * Description:       Semplifica la gestione della tua sezione Amministrazione Trasparente. Crea e aggiorna facilmente i contenuti obbligatori per legge, assicurando la conformità al D.Lgs. 14 marzo 2013, n. 33.
 * Version:           1.0.0
 * Author:            Luca Visciola
 * Author URI:        https://lucavisciola.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       melasistema-amministrazione-trasparente
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MELASISTEMA_AMMINISTRAZIONE_TRASPARENTE_VERSION', '1.0.0' );

/**
 * Add write_log debug
 * Use it in all plugin files to write in WP default log es. write_log($var)
 * arrays or objects
 */
if ( !function_exists( 'write_log' ) ) {
    function write_log( $log ): void {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-melasistema-amministrazione-trasparente-activator.php
 */
function activate_melasistema_amministrazione_trasparente(): void {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-melasistema-amministrazione-trasparente-activator.php';
	Melasistema_Amministrazione_Trasparente_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-melasistema-amministrazione-trasparente-deactivator.php
 */
function deactivate_melasistema_amministrazione_trasparente(): void {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-melasistema-amministrazione-trasparente-deactivator.php';
	Melasistema_Amministrazione_Trasparente_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_melasistema_amministrazione_trasparente' );
register_deactivation_hook( __FILE__, 'deactivate_melasistema_amministrazione_trasparente' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-melasistema-amministrazione-trasparente.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_melasistema_amministrazione_trasparente(): void {

	$plugin = new Melasistema_Amministrazione_Trasparente();
	$plugin->run();

}
run_melasistema_amministrazione_trasparente();
