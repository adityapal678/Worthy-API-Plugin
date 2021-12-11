<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Worthy_Api
 * @subpackage Worthy_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Worthy_Api
 * @subpackage Worthy_Api/admin
 * @author     Your Name <email@example.com>
 */
class Worthy_Api_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $worthy_api    The ID of this plugin.
	 */
	private $worthy_api;

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
	 * @param      string    $worthy_api       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $worthy_api, $version ) {

		$this->worthy_api = $worthy_api;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Worthy_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Worthy_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->worthy_api, plugin_dir_url( __FILE__ ) . 'css/worthy-api-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Worthy_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Worthy_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script( 'my_script', plugin_dir_url( __FILE__ ) . 'js/worthy-api-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'my_script', 'params', $params );
	}

	public function register_settings() {
		$register_args = array(
			'type' => 'string',
			'show_in_rest' => false
		);

		register_setting(
			'worthy_api_options',
			'worthy_api_options',
		);

		if ( false === get_option( 'worthy_api_options' ) ) {
			$options = array(
				'worthy_keap_client_id' => '',
				'worthy_keap_client_secret' => '',
				'worthy_keap_authorize_url' => '',
				'worthy_keap_token_url' => ''
			);
			$added = add_option( 'worthy_api_options', $options );
		}

		add_settings_section(
			'api_settings',
			'KEAP API Settings',
			function() {
				echo '<p>Set the Keap integration by saving the API id and secret from Keap here.';
			},
			'worthy_api'
		);

		add_settings_field(
			'worthy_keap_client_id',
			'Client ID',
			function() {
				$options = get_option( 'worthy_api_options' );
				echo "<input id='worthy_keap_client_id' name='worthy_api_options[worthy_keap_client_id]' type='text' value='" . esc_attr( $options['worthy_keap_client_id'] ) . "' />";
			},
			'worthy_api',
			'api_settings',
		);

		add_settings_field(
			'worthy_keap_client_secret',
			'Client Secret',
			function() {
				$options = get_option( 'worthy_api_options' );
				echo "<input id='worthy_keap_client_secret' type='password' name='worthy_api_options[worthy_keap_client_secret]' type='text' value='" . esc_attr( $options['worthy_keap_client_secret'] ) . "' />";
			},
			'worthy_api',
			'api_settings',
		);

		add_settings_field(
			'worthy_keap_authorize_url',
			'Keap Authorize URL',
			function() {
				$options = get_option( 'worthy_api_options' );
				echo "<input id='worthy_keap_authorize_url' name='worthy_api_options[worthy_keap_authorize_url]' type='text' value='" . esc_attr( $options['worthy_keap_authorize_url'] ) . "' />";
			},
			'worthy_api',
			'api_settings',
		);

		add_settings_field(
			'worthy_keap_token_url',
			'Keap Token URL',
			function() {
				$options = get_option( 'worthy_api_options' );
				echo "<input id='worthy_keap_token_url' name='worthy_api_options[worthy_keap_token_url]' type='text' value='" . esc_attr( $options['worthy_keap_token_url'] ) . "' />";
			},
			'worthy_api',
			'api_settings',
		);
	}

	public function admin_menu() {

		add_menu_page(
			'Worthy API',
			'Worthy API',
			'manage_options',
			'worthy_api',
			function () {
				$worthy_keap_authorize_nonce = wp_create_nonce( 'worthy_keap_authorize_form_nonce' );
				$options = get_option( 'worthy_api_options' );
				$client_id = $options['worthy_keap_client_id'];
				$authorize_args = array(
					'client_id' => $client_id,
					'redirect_uri' => get_site_url() . '/wp-json/worthy/v1/keap/request-access',
					'response_type' => 'code',
					'scope' => 'full'
				);
				$authorize_url = add_query_arg(
					$authorize_args,
					$options['worthy_keap_authorize_url']
				);

				?>
				<div class="wrap">
					<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
					<form action="options.php" method="post">
						<?php
						settings_fields( 'worthy_api_options' );
						do_settings_sections( 'worthy_api' );
						submit_button( 'Save Settings' );
						?>
					</form>
					<h3>Authorize App</h3>
					<a href="<?php echo $authorize_url ?>" target="_blank" class="button button-primary">Authorize</a>
					<!-- <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="worthy_keap_authorize_form">
						<input type="hidden" name="action" value="worthy_keap_authorize" />
						<input type="hidden" name="worthy_keap_authorize_nonce" value="<?php echo $worthy_keap_authorize_nonce ?>" />
						<button class="button button-primary">Authorize</button>
					</form>
					<div id="worthy_keap_form_feedback"></div> -->
				</div>
				<?php
			}
		);
	}

	public function keap_authorize_response() {
		if ( isset( $_POST['worthy_keap_authorize_nonce'] ) && wp_verify_nonce( $_POST['worthy_keap_authorize_nonce'], 'worthy_keap_authorize_form_nonce' ) ) {
			if ( isset( $_POST['ajaxrequest'] ) && $_POST['ajaxrequest'] === 'true' ) {
				echo '<pre>';
					print_r( $_POST );
				echo '</pre>';
				wp_die();
			}

			$admin_notice = 'success';
			
			wp_die( __( 'ok in thing', $this->worthy_api ), __( 'Error', $this->worthy_api ), array(
				'response' => 403,
				'back_link' => 'admin.php?page=' . $this->worthy_api
			) );

			$this->custom_redirect( $admin_notice, $_POST );
			$exit;
		} else {
			wp_die( __( 'Invalid nonce specified', $this->worthy_api ), __( 'Error', $this->worthy_api ), array(
				'response' => 403,
				'back_link' => 'admin.php?page=' . $this->worthy_api
			) );
		}
	}

	/** filters */
	public function modify_jwt_auth_data($data, $user) {
		$data['user_id'] = $user->ID;
		return $data;
	}

	public function modify_jwt_auth_expiry($expire, $issued_at) {
		return $issued_at + ( DAY_IN_SECONDS * 60 );
	}
}
