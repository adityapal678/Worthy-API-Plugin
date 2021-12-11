<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Worthy_Api
 * @subpackage Worthy_Api/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Worthy_Api
 * @subpackage Worthy_Api/includes
 * @author     Your Name <email@example.com>
 */
class Worthy_Api_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$worthy_api_db_version = '1.1';
		$worthy_prefix = "worthy_";

		$goals_table_name = $wpdb->prefix . $worthy_prefix . "goals";
		$notes_table_name = $wpdb->prefix . $worthy_prefix . "lesson_notes";
		$trackers_table_name = $wpdb->prefix . $worthy_prefix . "daily_tracker";
		$wishlists_table_name = $wpdb->prefix . $worthy_prefix . "workshop_wishlist";
		$downloads_table_name = $wpdb->prefix . $worthy_prefix . "user_downloads";
		$oauth_table_name =  $wpdb->prefix . $worthy_prefix . "oauth_client_credentials";
		$mobile_tokens_table_name = $wpdb->prefix . $worthy_prefix . "mobile_tokens";

		$users_table = $wpdb->prefix . "users";
		$posts_table = $wpdb->prefix . "posts";

		$charset_collate = $wpdb->get_charset_collate();

		$goals_table = "CREATE TABLE $goals_table_name (
											id mediumint(9) NOT NULL AUTO_INCREMENT,
											user_id bigint(20) unsigned NOT NULL,
											created timestamp NOT NULL default CURRENT_TIMESTAMP,
											updated timestamp NOT NULL default CURRENT_TIMESTAMP,
											goal_name varchar(255),
											goal_type varchar(255),
											goal_feel varchar(255),
											goal_motivations varchar(255),
											FOREIGN KEY (user_id) REFERENCES $users_table (ID),
											PRIMARY KEY  id (id),
											KEY t_IX (created)
										) $charset_collate;";

		$notes_table = "CREATE TABLE $notes_table_name (
											id mediumint(9) NOT NULL AUTO_INCREMENT,
											post_id bigint(20) unsigned NOT NULL,
											user_id bigint(20) unsigned NOT NULL,
											created timestamp NOT NULL default CURRENT_TIMESTAMP,
											updated timestamp NOT NULL default CURRENT_TIMESTAMP,
											note text,
											FOREIGN KEY (user_id) REFERENCES $users_table (ID),
											PRIMARY KEY  id (id)
										) $charset_collate;";

		$trackers_table = "CREATE TABLE $trackers_table_name (
											id mediumint(9) NOT NULL AUTO_INCREMENT,
											user_id bigint(20) unsigned NOT NULL,
											created timestamp NOT NULL default CURRENT_TIMESTAMP,
											updated timestamp NOT NULL default CURRENT_TIMESTAMP,
											hydrate BOOLEAN default 0 NOT NULL,
											mindfulness BOOLEAN default 0 NOT NULL,
											movement BOOLEAN default 0 NOT NULL,
											self_care BOOLEAN default 0 NOT NULL,
											gratitude BOOLEAN default 0 NOT NULL,
											journal BOOLEAN default 0 NOT NULL,
											FOREIGN KEY (user_id) REFERENCES $users_table (ID),
											PRIMARY KEY  id (id),
											KEY t_IX (created)
										) $charset_collate;";

		$wishlist_table = "CREATE TABLE $wishlists_table_name (
											id mediumint(9) NOT NULL AUTO_INCREMENT,
											post_id bigint(20) unsigned NOT NULL,
											user_id bigint(20) unsigned NOT NULL,
											created timestamp NOT NULL default CURRENT_TIMESTAMP,
											updated timestamp NOT NULL default CURRENT_TIMESTAMP,
											FOREIGN KEY (user_id) REFERENCES $users_table (ID),
											PRIMARY KEY  id (id)
										) $charset_collate;";

		$oauth_table = "CREATE TABLE $oauth_table_name (
											id mediumint(9) NOT NULL AUTO_INCREMENT,
											created timestamp NOT NULL default CURRENT_TIMESTAMP,
											updated timestamp NOT NULL default CURRENT_TIMESTAMP,
											access_token varchar(255),
											refresh_token varchar(255),
											client_name varchar(255),
											PRIMARY KEY  id (id)
										) $charset_collate";

		require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $goals_table );
		dbDelta( $notes_table );
		dbDelta( $trackers_table );
		dbDelta( $wishlist_table );
		dbDelta( $downloads_table );
		dbDelta( $oauth_table );

		add_option( 'worthy_api_db_version', '$worthy_api_db_version' );
	}

}
