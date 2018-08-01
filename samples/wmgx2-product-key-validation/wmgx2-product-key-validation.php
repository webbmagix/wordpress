<?php
/**
 * @package wmgx2_product_key_validation
 * @version 2.0
 */
 /*
Plugin Name: WebbMagix Product Key Validation v2
Plugin URI: https://WebbMagix.com
Description: Custom plugin to validate the key pair of the product.
Author: Sathish Kumar
Version: 2.0
Author URI: https://WebbMagix.com
Text Domain: wmgx2-product-key-validation
*/

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts' );
function ajax_test_enqueue_scripts() {
  // love -> wmgx_key_validation; todo: plugins_url or use
  // alternate: plugin_dir_url( __FILE__ ) . 'wmgx2-product-key-validation.js')
	wp_enqueue_script( 'wmgx_key_validation', plugins_url( '/wmgx2-product-key-validation.js', __FILE__ ), array(
    'jquery'), '1.0', true );

  //postlove -> wmgx_key_validation_vars
	wp_localize_script( 'wmgx_key_validation', 'wmgx_key_validation_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));

}

add_action( 'wp_ajax_nopriv_wmgx_key_validation', 'wmgx_key_validation_form' );
add_action( 'wp_ajax_wmgx_key_validation', 'wmgx_key_validation_form' );

function wmgx_key_validation_form() {
    error_log("Running wmgx_key_validation_form()");
    $result = "<h2>Your bottle is kind of genuine.</h2>";
    echo $result;
}

add_shortcode('wmgx_key_validator', 'wmgx_key_validation');
function wmgx_key_validation(){
  error_log("Running wmgx_key_validation shortcode");
  ob_start();

  echo wmgx_html_display_form();
  wmgx_form_message();

  return ob_get_clean();
}

function wmgx_html_display_form()
{
  error_log("Running wmgx_html_display_form()");
  ob_start();

  //action="<?php echo esc_url( admin_url( 'admin-post.php' ) );
  //id=wmgx_key_validation_btn is referred from Ajax script

  ?>
  <div id="form-area">
  <form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wmgx_key_validation_form">
    <p>Enter the number from the bottle<br />
      <input type="text" id="key1" name="key1" pattern="[0-9]+" value="<?php isset($_POST['key1']) ? esc_attr($_POST['key1']) : '';
      ?>" size="10" />
    </p>
		<br /><label class="error" for="key1" id="key1_error">This field is required.</label>
    <p>Enter the number from the cap<br />
      <input type="text" name="key2" pattern="[0-9]+" value="<?php isset($_POST['key2']) ? esc_attr($_POST['key2']) : '';
      ?>" size="10" />
    </p>
		<br /><label class="error" for="key2" id="key2_error">This field is required.</label>
    <p><input type="submit" name="wmgx_key_validation" value="Validate Keys" id="wmgx_key_validation_btn"></p>
    <input type="hidden" name="wmgx_key_submitted" id="wmgx_key_submitted" value="true" />
    <input type="hidden" name="action" value="wmgx_key_validation" >
    <span id="message-area"></span>
  </form>
  </div>
  <?php

  //echo ob_get_clean();
  return ob_get_clean();
}

// Form succsess/error message
function wmgx_form_message()
{
		error_log("Running wmgx_form_message");
    global $errors;
    $msg = '';
    if (is_wp_error($errors) && empty($errors->get_error_messages())) {
        $msg  = '<div class="wmgx_success">';
        $msg .= '<p>Your bottle is genuine.</p>';
        $msg .= '</div>';

      //Empty $_POST because we already sent email
      //$_POST = '';
    } else {
        if (is_wp_error($errors) && !empty($errors->get_error_messages())) {
            $error_messages = $errors->get_error_messages();
            foreach ($error_messages as $k => $message) {
                $msg  .= '<div class="wmgx-error '.$k.'">';
                $msg  .= '<p>'.$message.'</p>';
                $msg  .= '</div>';
            }
        }
    }

    error_log("wmgx_form_message() returning: " . $msg);
    echo $msg;
    // return $msg;
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
