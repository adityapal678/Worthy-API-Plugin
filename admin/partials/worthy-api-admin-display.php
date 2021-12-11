<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Worthy_Api
 * @subpackage Worthy_Api/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
function worthy_api_options_page_html() {
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
      <?php
      settings_fields( 'worthy_api_options' );
      do_settings_sections( 'wporg' );
      submit_button( __( 'Save settings', 'textdomain' ) );
      ?>
    </form>
  </div>
  <?php
}
?>