<?php
/**
 * @package wmgx3_product_key_validation
 * @version 3.0
 */
 /*
Plugin Name: WebbMagix Product Key Validation v3
Plugin URI: https://WebbMagix.com
Description: Custom plugin to validate the key pair of the product.
Author: Sathish Kumar
Version: 3.0
Author URI: https://WebbMagix.com
Text Domain: wmgx3-product-key-validation
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/////////////////
// Displaying form for short code wmgx_key_validator
/////////////////

add_action( 'wp_enqueue_scripts', 'ajax_enqueue_scripts' );
function ajax_enqueue_scripts() {
  // alternate: plugin_dir_url( __FILE__ ) . 'wmgx2-product-key-validation.js')
	wp_enqueue_script( 'wmgx_key_validation', plugins_url( '/wmgx3-product-key-validation.js', __FILE__ ), array(
    'jquery'), '1.0', true );

	wp_localize_script( 'wmgx_key_validation', 'wmgx_key_validation_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));

}

add_action( 'wp_ajax_nopriv_wmgx_key_validation', 'wmgx_key_validation_form' );
add_action( 'wp_ajax_wmgx_key_validation', 'wmgx_key_validation_form' );

function wmgx_key_validation_form() {
    error_log("Running wmgx_key_validation_form()");
    $result = "<h2>Your bottle is kind of genuine..</h2>";
		if($_POST["key1"]) {
			$key1 = $_POST["key1"];
		}

		if($_POST["key2"]) {
			$key2 = $_POST["key2"];
		}

		if ($key1 == '' || $key2 == '') {
			error_log(" wmgx_key_validation_form: Either key1 or key2 is empty!");
			$result = 'Either key1 or key2 is empty!';
		} else {
			error_log("Got Keys: ". $key1 . ", " . $key2) ;
			$existkeys = wmgx_check_keys($key1, $key2);
			if ($existkeys) {
				$result = "<h4>Your bottle is genuine</h4>";
			} else {
				$result = "<h4>Your bottle is not genuine</h4>";
			}
		}
		error_log("Returing result: " . $result);
    echo $result;

		wp_die();
}

function wmgx_check_keys($key1, $key2) {
		error_log("Running wmgx_check_keys(). Keys:" .  $key1 . ", " . $key2) ;

		global $wpdb;
		$table_name = $wpdb->prefix."data_validation_keys";
		$charset_collate = $wpdb->get_charset_collate();
		$query = "SELECT key1 FROM " .$table_name. " WHERE key1=" . $key1 . " AND key2=" . $key2;
		error_log("Query: " .$query);
		$dbresult = $wpdb->query($query);
		error_log("DbResult: " .$dbresult);
		if ($dbresult) {
			return true;
		} else {
			return false;
		}
}


/////////////////////////
/// Process short code
/////////////////////////

add_shortcode('wmgx3_key_validator', 'wmgx_key_validation');
function wmgx_key_validation(){
  error_log("Running wmgx_key_validation shortcode");

  ob_start();
  echo wmgx_html_display_form();
  return ob_get_clean();
}

function wmgx_html_display_form()
{
  error_log("Running wmgx_html_display_form()");
  ob_start();

  ?>
  <div id="form-area">
  <form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wmgx_key_validation_form">
      <input type="text" id="key1" name="key1" pattern="[0-9]+"
					value="<?php isset($_POST['key1']) ? esc_attr($_POST['key1	']) : '';?>" size="10"
					placeholder="Enter number on cap"/>
			<label class="error" for="key1" id="key1_error">This field is required.</label>
      </p>

      <input type="text" id="key2" name="key2" pattern="[0-9]+"
					value="<?php isset($_POST['key2']) ? esc_attr($_POST['key2']) : '';?>" size="10"
						placeholder="Enter number on bottle"/>
			<label class="error" for="key2" id="key2_error">This field is required.</label>
    	</p>

	    <input type="button" name="wmgx_key_validation" value="Check Bottle" id="wmgx_key_validation_btn">
	    <input type="hidden" name="wmgx_key_submitted" id="wmgx_key_submitted" value="true" />
	    <input type="hidden" name="action" value="wmgx_key_validation" >
	    <span id="message-area"></span>
  </form>
  </div>
  <?php

  return ob_get_clean();
}


////////////////////////////////
// Activation / deactivation
////////////////////////////////

function wmgx_data_validation_activate () {
  error_log("Activating plugin wmgx_product_key_validation...");
  global $wpdb;

  $table_name = $wpdb->prefix."data_validation_keys";
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    key1 mediumint(9) NOT NULL,
    key2 mediumint(9) NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

  $wpdb->query($sql);

  // Populate table - just some sample data
  $date1 = date("Y-m-d H:i:s");
  $wpdb->query("INSERT INTO $table_name(time, key1, key2)
      VALUES(now(), 101, 201)");
  $wpdb->query("INSERT INTO $table_name(time, key1, key2)
      VALUES(now(), 102, 202)");
  $wpdb->query("INSERT INTO $table_name(time, key1, key2)
      VALUES(now(), 103, 203)");
  $wpdb->query("INSERT INTO $table_name(time, key1, key2)
      VALUES(now(), 104, 204)");

  error_log("Activated plugin wmgx_product_key_validation.");
}
register_activation_hook( __FILE__, 'wmgx_data_validation_activate' );


function wmgx_data_validation_deactivate() {
  error_log("Deactivating plugin wmgx_product_key_validation...");
  //TODO: show Errors

  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys";
  $sql = "drop table $table_name";
  $wpdb->query($sql);
  error_log("Dectivated plugin wmgx_product_key_validation");
}
register_deactivation_hook( __FILE__, 'wmgx_data_validation_deactivate' );


/////////////////////////////////////
/// Admin Page
/////////////////////////////////////

add_action( 'admin_enqueue_scripts', 'ajax_enqueue_scripts' );

add_action( 'admin_menu', 'wmgx_validation_plugin_admin' );
function wmgx_validation_plugin_admin() {
	add_menu_page( 'WebbMagix Product Key Validation', 'Wmgx Product Key Management', 'manage_options',
			'wmgx3-product-key-admin-page', 'wmgx_key_plugin_admin_page', 'dashicons-tickets', 6  );
}

function wmgx_key_plugin_admin_page(){

	?>
	<div class="wrap">
		<h2>WebbMagix Product Key Management</h2>
		<form id="add-form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post">
			<div>
				<input type="date" name="key-date" id="key-date">
				<input type="button" name="generate-keys-btn" id="generate-keys-btn" value="Generate Keys">
				<input type="hidden" name="action" value="wmgx_generate_keys" >
			</div>
			<span id="keys-display-area"></span>
		</form>
	</div>
	<?php
}

add_action( 'wp_ajax_nopriv_wmgx_generate_keys', 'wmgx_generate_keys_submit' );
add_action( 'wp_ajax_wmgx_generate_keys', 'wmgx_generate_keys_submit' );

function wmgx_generate_keys_submit() {
  error_log("Running wmgx_generate_keys_submit");

	if($_POST["keydate"]) {
		$key_date_value = $_POST["keydate"];
	}
	error_log("Generating for date: " . $key_date_value);

	$arr_key1 = UniqueRandomNumbersWithinRange(0,100,100);
	$arr_key2 = UniqueRandomNumbersWithinRange(100,200,100);

	?>
	<h2>Keys generated for date: xxx </h2>
	<?php

	// ?>
	// <div style="height:200px;overflow:auto;">
	// 	<h2>Keys generated for date: <?php echo $key_date_value; ?> </h2>
	// 	<table border = "1">
	// 		 <tr>
	// 				<th>Date</th>
	// 				<th>Bottle Key</th>
	// 				<th>Cap Key</th>
	// 		 </tr>
	// 			<?php
	// 			for ($i=0;$i<20;$i++) {
	// 			?>
	// 				 <tr>
	// 						<td>date</td>
	// 						<td><?php echo $arr_key1[$i]; ?></td>
	// 						<td><?php echo $arr_key2[$i]; ?></td>
	// 				 </tr>
	// 			<?php
	// 			 }
	// 			 ?>
	// 	 </table>
	// </div>
	// <?php

	wp_die();
	error_log("Method wmgx_generate_keys_submit returning generated keys");
}

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}
