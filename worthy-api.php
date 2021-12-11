<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             0.0.1
 * @package           Worthy_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Worthy Api
 * Plugin URI:        // bitbucket link?
 * Description:       WORTHY custom API endpoints
 * Version:           0.0.1
 * Author:            Reggie Bigornia / WORTHY
 * Author URI:        https://iamworthy.co
 * License:           // only WORTHY
 * License URI:       // none?
 * Text Domain:       worthy-api
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
define( 'WORTHY_API_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-worthy-api-activator.php
 */
function activate_worthy_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-worthy-api-activator.php';
	Worthy_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-worthy-api-deactivator.php
 */
function deactivate_worthy_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-worthy-api-deactivator.php';
	Worthy_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_worthy_api' );
register_deactivation_hook( __FILE__, 'deactivate_worthy_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-worthy-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_worthy_api() {

	$plugin = new Worthy_Api();
	$plugin->run();

}
run_worthy_api();
