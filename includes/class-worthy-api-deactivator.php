<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Worthy_Api
 * @subpackage Worthy_Api/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Worthy_Api
 * @subpackage Worthy_Api/includes
 * @author     Your Name <email@example.com>
 */
class Worthy_Api_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		$worthy_api_db_version = '1.0';
		$worthy_prefix = "worthy_";

		$goals_table_name = $wpdb->prefix . $worthy_prefix . "goals";
		$notes_table_name = $wpdb->prefix . $worthy_prefix . "lesson_notes";
		$trackers_table_name = $wpdb->prefix . $worthy_prefix . "daily_tracker";
		$wishlists_table_name = $wpdb->prefix . $worthy_prefix . "workshop_wishlist";
		$oauth_table_name =  $wpdb->prefix . $worthy_prefix . "oauth_client_credentials";

		$drop_goals = "DROP TABLE IF EXISTS $goals_table_name";
		$drop_notes = "DROP TABLE IF EXISTS $notes_table_name";
		$drop_trackers = "DROP TABLE IF EXISTS $trackers_table_name";
		$drop_wishlists = "DROP TABLE IF EXISTS $wishlists_table_name";
		$drop_oauth = "DROP TABLE IF EXISTS $oauth_table_name";

		require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$wpdb->query( $drop_goals );
		$wpdb->query( $drop_notes );
		$wpdb->query( $drop_trackers );
		$wpdb->query( $drop_wishlists );
		$wpdb->query( $drop_oauth );

		add_option( 'worthy_api_db_version', '$worthy_api_db_version' );
	}

}
