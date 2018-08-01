<?php
  // This file can be used for testing php prototypes
  define( 'SHORTINIT', true );
  require( dirname(dirname(__FILE__)) . "\wp-load.php" );
?>
  <h3>Wmgx Key Managagment Service</h3>
<?php

main();

function main() {
  error_log("Running Wmgx Key Managagment Service");

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
    keydate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    key1 mediumint(9) NOT NULL,
    key2 mediumint(9) NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

  $wpdb->query($sql);

  // Populate table - just some sample data
  $date1 = date("Y-m-d");
  $wpdb->query("INSERT INTO $table_name(keydate, key1, key2)
      VALUES(now(), 101, 201)");
  $wpdb->query("INSERT INTO $table_name(keydate, key1, key2)
      VALUES(now(), 102, 202)");
  $wpdb->query("INSERT INTO $table_name(keydate, key1, key2)
      VALUES(now(), 103, 203)");
  $wpdb->query("INSERT INTO $table_name(keydate, key1, key2)
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
