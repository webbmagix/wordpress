<?php
/**
 * @package wmgx_product_key_validation
 * @version 1.0
 */
 /*
Plugin Name: WebbMagix Product Key Validation v1
Plugin URI: https://WebbMagix.com
Description: Custom plugin to validate the key pair of the product.
Author: Sathish Kumar
Version: 1.0
Author URI: https://WebbMagix.com
Text Domain: wmgx-product-key-validation
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//require_once(plugins_url( '/wmgx-product-key-validation.php', __FILE__ ));
require_once(plugin_dir_path(__FILE__) .  'wmgx-product-key-validation.php');

///////////////////
// Load ajax scripts
///////////////////

// For adding script file to admin page use admin_enqueue_scripts hook (not wp_enqueue_scripts)
// Verify that scrpt is added to the page by looking at html pagesouce.
add_action( 'admin_enqueue_scripts', 'ajax_enqueue_scripts' );
function ajax_enqueue_scripts() {
	// alternate: plugin_dir_url( __FILE__ ) . 'wmgx-product-key-validation.js')
	wp_enqueue_script( 'wmgx_key_management', plugins_url( '/wmgx-product-key-management.js', __FILE__ ), array(
    'jquery'));

	wp_localize_script( 'wmgx_key_management', 'wmgx_key_script_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}


/////////////////////////////////////
/// Admin Page
// Status - worked 7/28/18
/////////////////////////////////////

add_action( 'admin_menu', 'wmgx_validation_plugin_admin' );
function wmgx_validation_plugin_admin() {
	add_menu_page( 'WebbMagix Product Key Validation', 'Wmgx Product Key Management', 'manage_options',
			'wmgx-product-key-admin-page', 'wmgx_key_plugin_admin_page', 'dashicons-tickets', 6  );
}

function wmgx_key_plugin_admin_page(){
	?>
	<div class="wrap">
		<h2>WebbMagix Product Key Management</h2>

		<h2>Generate keys</h2>

    <?php // action not being used as it uses ajax. action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); \?\>" ?>

		<form id="generate-keys-form"  method="post">
			<div>
					For Date: <input type="date" name="key-date" id="key-date" placeholder="Key Date" />
					<input type="button" name="generate-keys-btn" id="generate-keys-btn" value="Generate Keys" />
					<input type="hidden" name="action" value="wmgx_generate_keys" />
			</div>
			<span id="generate-keys-msg-area"></span>
		</form>

		<h2>Display keys</h2>

		<form id="display-keys-form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post">
			<div>
				From Date: <input type="date" name="key-start-date" id="key-start-date" placeholder="Key Start Date" />
        To Date: <input type="date" name="key-end-date" id="key-end-date" placeholder="Key End Date" />
				<input type="button" name="display-keys-btn" id="display-keys-btn" value="Display Keys" />
				<input type="hidden" name="action" id="display-keys-btn" value="wmgx_display_keys" />
			</div>
			<span id="display-keys-msg-area"></span>
		</form>
	</div>
	<?php
}


// Process Generate Keys button - wmgx_generate_keys is expected as the action attribute in the form POST
add_action( 'wp_ajax_nopriv_wmgx_generate_keys', 'wmgx_generate_keys_submit' );
add_action( 'wp_ajax_wmgx_generate_keys', 'wmgx_generate_keys_submit' );

// funtion name used as action
function wmgx_generate_keys_submit() {
  error_log("Running wmgx_generate_keys_submit");

  // Variable key_date is defined in jQuery Ajax script
	if($_POST["key_date"]) {
		$key_date_value = $_POST["key_date"];
	}
	error_log("Generating keys for date: $key_date_value" );

  $count = generateKeys($key_date_value);

  ?>
    <h4><?php echo $count ?>  Key pairs generated for date: <?php echo $key_date_value ?></h4>
  <?php

  wp_die();
  error_log("Running wmgx_generate_keys_submit.. exit");
}


// Process Display Keys button - wmgx_display_keys is expected as the action attribute in the form POST
add_action( 'wp_ajax_nopriv_wmgx_display_keys', 'wmgx_display_keys_submit' );
add_action( 'wp_ajax_wmgx_display_keys', 'wmgx_display_keys_submit' );

// funtion name used as action
function wmgx_display_keys_submit() {
  error_log("Running wmgx_display_keys_submit");

  // Variable key_date is defined in jQuery Ajax script
	if($_POST["key_date"]) {
		$key_date_value = $_POST["key_date"];
	}

  if($_POST["key_end_date"]) {
    $key_end_date_value = $_POST["key_end_date"];
  } else {
    $key_end_date_value = $key_date_value;
  }

	error_log("Retrieving keys for date from: $key_date_value to $key_end_date_value" );

  $keystable = getKeysTable($key_date_value, $key_end_date_value);
  echo $keystable;

  wp_die();
  error_log("Running wmgx_display_keys_submit.. exit");
}


////////////////////////////////
// Activation / deactivation
// Status - worked 7/28/18
////////////////////////////////

function wmgx_data_validation_activate () {
  createTable();
}
register_activation_hook( __FILE__, 'wmgx_data_validation_activate' );

function wmgx_data_validation_deactivate() {
  removeTable();
}
register_deactivation_hook( __FILE__, 'wmgx_data_validation_deactivate' );

//////////////////////////////////
//// Data Management ////////////
//////////////////////////////////

//// Table Management

function createTable() {
  error_log("createTable..");
  global $wpdb;

  $table_name = $wpdb->prefix."data_validation_keys";
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    keydate DATE NOT NULL,
    key1 mediumint(9) NOT NULL,
    key2 mediumint(9) NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

  $wpdb->query($sql);

  // Populate table - just some sample data
  $date1 = date("Y-m-d");
  $wpdb->query("INSERT INTO $table_name(keydate, key1, key2)
      VALUES($date1, 101, 201)");

  error_log("createTable.. exit");
}

function removeTable() {
  error_log("removeTable...");
  //TODO: show Errors

  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys";
  $sql = "drop table $table_name";
  $wpdb->query($sql);
  error_log("removeTable.. exit");
}

//// Keys management

function generateKeys($keydate) {
  error_log("generateKeys($keydate)..");

  $existingkeysfordate = checkKeys($keydate);
  if ($existingkeysfordate > 0) {
    error_log("Not generating. Keys already existing for $keydate");
    // TODO: return error message
    return 0;
  }

  $key_max_size = 999;
  $key_count_value = 900;
  error_log("Generating " . $key_count_value . " keys for date: " . $keydate );

  $arr_key1 = UniqueRandomNumbersWithinRange(0,$key_max_size,$key_count_value);
  $arr_key2 = UniqueRandomNumbersWithinRange(0,$key_max_size,$key_count_value);

  $count = 0;
  for ($i=0; $i < $key_count_value; $i++) {
    $count += addData($keydate, $arr_key1[$i], $arr_key2[$i]);
  }

  error_log("generateKeys.. exit. Created keys: $count");
  return $count;
}


function getKeysTable($keydate, $keyenddate) {
  error_log("getKeysTable... $keydate, $keyenddate");
  $results = getKeysBetween($keydate, $keyenddate);
  $keyscount = count($results);

  ob_start();
  ?>
  <div style="height:300px;overflow:auto;">
  	<h2><?php echo $keyscount; ?> Keys between: <?php echo $keydate; ?> and <?php echo $keyenddate; ?> </h2>
  	<table border = "1">
  		 <tr>
          <th>Number</th>
  				<th>Date</th>
  				<th>Bottle Key</th>
  				<th>Cap Key</th>
  		 </tr>
  			<?php
        $counter = 0;
  			foreach ( $results as $result ) {
            $counter = $counter + 1;
    		?>
    				 <tr>
                <td><?php echo $counter; ?></td>
    						<td><?php echo $result->keydate; ?></td>
    						<td><?php echo $result->key1; ?></td>
    						<td><?php echo $result->key2; ?></td>
    				 </tr>
    		<?php
  			 }
  			 ?>
  	 </table>
  </div>
  <?php
  error_log("getKeysTable...exit. Key pairs returning: $keyscount");
  return ob_get_clean();
}


function getKeysBetween($keydate, $keyenddate) {
  error_log("getKeysBetween... $keydate - $keyenddate");

  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys";
  $query = "SELECT keydate, key1, key2 FROM " .$table_name. " WHERE keydate >= '" .$keydate. "' AND keydate <= '" .$keyenddate. "'";
  error_log($query);
  $results = $wpdb->get_results($query);
  error_log("Found keys:" . sizeof($results));
  return $results;
}

function checkKeys($keydate) {
  error_log("checkKeys... $keydate");
  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys";
  $query = "SELECT count(*) FROM " .$table_name. " WHERE keydate = '" .$keydate. "'";
  error_log($query);
  $count = $wpdb->get_var($query);
  error_log("checkKeys found $count keys for $keydate");
  return $count;
}

function addData($keydate, $key1, $key2) {
  error_log("addData..$keydate, $key1, $key2");
  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys";
  // keydate needs qoutes around; key1 does not as it is number;
  $query = "INSERT INTO $table_name(keydate, key1, key2) VALUES('$keydate', $key1, $key2)";
  $count = $wpdb->query($query);
  error_log("Added $count rows: ");
  error_log("addData.. exit.");
  return $count;
}

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}
