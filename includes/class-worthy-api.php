<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Worthy_Api
 * @subpackage Worthy_Api/includes
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
 * @package    Worthy_Api
 * @subpackage Worthy_Api/includes
 * @author     Your Name <email@example.com>
 */
class Worthy_Api {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Worthy_Api_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $worthy_api    The string used to uniquely identify this plugin.
	 */
	protected $worthy_api;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

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
		if ( defined( 'WORTHY_API_VERSION' ) ) {
			$this->version = WORTHY_API_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->worthy_api = 'worthy-api';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_rest_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Worthy_Api_Loader. Orchestrates the hooks of the plugin.
	 * - Worthy_Api_i18n. Defines internationalization functionality.
	 * - Worthy_Api_Admin. Defines all hooks for the admin area.
	 * - Worthy_Api_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-worthy-api-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-worthy-api-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-worthy-api-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-worthy-api-public.php';

		/**
		 * The classes responsible for defining the custom api endpoints
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'rest-api/class-worthy-api-keap-oauth.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'rest-api/class-worthy-api-notes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'rest-api/class-worthy-api-profile.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'rest-api/class-worthy-api-trackers.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'rest-api/class-worthy-api-users.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'rest-api/class-worthy-api-workshops.php';

		$this->loader = new Worthy_Api_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Worthy_Api_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Worthy_Api_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Worthy_Api_Admin( $this->get_worthy_api(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_post_worthy_keap_authorize', $plugin_admin, 'keap_authorize_response' );
		$this->loader->add_filter( 'jwt_auth_token_before_dispatch', $plugin_admin, 'modify_jwt_auth_data', 10, 2 );
		$this->loader->add_filter( 'jwt_auth_expire', $plugin_admin, 'modify_jwt_auth_expiry', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Worthy_Api_Public( $this->get_worthy_api(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register custom api endpoints
	 * 
	 * @since 1.0.0
	 * @access private
	 */
	private function define_rest_api_hooks() {

		$plugin_keap_oauth_endpoints = new Worthy_Keap_Oauth_Route();
		$plugin_notes_endpoints = new Worthy_Notes_Route();
		$plugin_profile_endpoints = new Worthy_Profile_Route();
		$plugin_trackers_endpoints = new Worthy_Trackers_Route();
		$plugin_users_endpoints = new Worthy_User_Route();
		$plugin_workshops_endpoints = new Worthy_Workshops_Route();

		$this->loader->add_action( 'rest_api_init', $plugin_keap_oauth_endpoints, 'register_routes' );
		$this->loader->add_action( 'rest_api_init', $plugin_notes_endpoints, 'register_routes' );
		$this->loader->add_action( 'rest_api_init', $plugin_profile_endpoints, 'register_routes' );
		$this->loader->add_action( 'rest_api_init', $plugin_trackers_endpoints, 'register_routes' );
		$this->loader->add_action( 'rest_api_init', $plugin_users_endpoints, 'register_routes' );
		$this->loader->add_action( 'rest_api_init', $plugin_workshops_endpoints, 'register_routes' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_worthy_api() {
		return $this->worthy_api;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Worthy_Api_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
