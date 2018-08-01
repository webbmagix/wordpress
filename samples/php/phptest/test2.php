<?php
  // This file can be used for testing php prototypes
  define( 'SHORTINIT', true );
  require( dirname(dirname(__FILE__)) . "\wp-load.php" );
?>
  <h3>Testing PHP code</h3>
<?php

main();

function main() {
  error_log("test2.php ..");
  createTable();

  $date4 = "2018-07-20";
  // worked; created 900 keys.
  generateKeys($date4);
  
  error_log("main.. $date4, $date4");

  ?>Generated keys<?php
}

////////////////////////////////
// Activation / deactivation
////////////////////////////////

function wmgx_data_validation_activate () {
  createTable();
}
//register_activation_hook( __FILE__, 'wmgx_data_validation_activate' );

function wmgx_data_validation_deactivate() {
  removeTable();
}
//register_deactivation_hook( __FILE__, 'wmgx_data_validation_deactivate' );

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
