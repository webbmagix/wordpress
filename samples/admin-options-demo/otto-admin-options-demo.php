<?php
/*
Plugin Name: Otto Admin Options Demo
Plugin URI: https://WebbMagix.com
Description: Using Admin Settings Menu Demo.
Author: Sathish Kumar
Version: 2.0
Author URI: https://www.youtube.com/watch?v=7pO-FYVZv94&t=555s
Text Domain: wmgx2-product-key-validation
*/

add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
  add_options_page('Custom Plugin Page', 'Otto Custom Plugin Menu', 'manage_options', 'otto-plugin', 'plugin_options_page');
}


function plugin_options_page() {
?>
  <div>
  <h2>My custom plugin</h2>
  Options relating to the Custom Plugin.
  <form action="options.php" method="post">
  <?php settings_fields('plugin_options_group'); ?>
  <?php do_settings_sections('plugin'); ?>
  <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
  </form>
  </div>
<?php
}

add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init(){
  register_setting( 'plugin_options_group', 'plugin_options_group', 'plugin_options_validate' );
  add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'plugin');
  add_settings_field('plugin_text_string', 'Plugin Text Input', 'plugin_setting_string', 'plugin', 'plugin_main');
}

function plugin_section_text() {
  echo '<p>Main description of this section here.</p>';
}

function plugin_setting_string() {
  error_log("Running plugin_setting_string()");
  $options = get_option('plugin_options_group');
  echo "<input id='plugin_text_string' name='plugin_options_group[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}

function plugin_options_validate($input) {
  error_log("Running plugin_options_validate()");
  $options = get_option('plugin_options_group');
  $options['text_string'] = trim($input['text_string']);
  if(!preg_match('/^[a-z0-9]{32}$/i', $options['text_string'])) {
    $options['text_string'] = '';
  }
  return $options;
}
