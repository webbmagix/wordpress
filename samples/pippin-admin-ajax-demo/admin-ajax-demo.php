<?php
/*
Plugin Name: Pippin Admin Ajax Demo
Plugin URI: https://WebbMagix.com
Description: Custom plugin to use Ajax in Admin page.
Author: Sathish Kumar
Version: 2.0
Author URI: https://www.youtube.com/watch?v=7pO-FYVZv94&t=555s
Text Domain: admin-ajax-demo
*/

add_action('admin_menu', 'aad_admin_page');
function aad_admin_page() {
  $aad_settings = add_options_page('Admin Ajax Demo', 'Admin Ajax', 'manage_options',
    'admin-ajax-demo', 'aad_render_admin');

}

function aad_render_admin() {
  ?>
  <div class="wrap">
    <h2>Admin Ajax Demo</h2>
    <form id="add-form" action="" method="post">
      <div>
        <input type="submit" name="add-summit" value="Get Results">
      </div>
    </form>
  </div>
  <?php
}

add_action('admin_init', 'plugin_admin_init2');
function plugin_admin_init2(){
  register_setting( 'plugin_options_group', 'plugin_options_group', 'plugin_options_validate' );
  add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'plugin');
  add_settings_field('plugin_text_string', 'Plugin Text Input', 'plugin_setting_string', 'plugin', 'plugin_main');
}
