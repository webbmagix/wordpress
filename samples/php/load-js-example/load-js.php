<?php
/*
Plugin Name: 00 Load Javascript Example
Plugin URI: http://planetozh.com/blog/2008/04/how-to-load-javascript-with-your-wordpress-plugin/
Description: Example on how to use various ways to load javascript once and only where you need it
Version: 0.1
Author: Ozh
Author URI: http://planetozh.com/
*/


add_action('admin_menu', 'ozh_loadjs_add_page');

function ozh_loadjs_add_page() {
	$mypage = add_options_page('Load JS Example', 'Load JS Example', 8, 'loadjsexample', 'ozh_loadjs_options_page');
	add_action( "admin_print_scripts-$mypage", 'ozh_loadjs_admin_head' );
}

function ozh_loadjs_options_page() {
	echo "<div class='wrap'>
	<h2>Load JS Example Page</h2>
	Only on this page you'll see ugly CSS and annoying JS
	</div>
	";
}

function ozh_loadjs_admin_head() {
	$plugindir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
	wp_enqueue_script('loadjs', $plugindir . '/example.js');
	echo "<link rel='stylesheet' href='$plugindir/example.css' type='text/css' />\n";
}














?>