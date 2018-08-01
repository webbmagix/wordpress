<?php
  // This file can be used for testing php prototypes
  define( 'SHORTINIT', true );
  require( dirname(dirname(__FILE__)) . "\wp-load.php" );
?>
  <h3>Testing PHP code</h3>
<?php

main();

function main() {
  error_log("test.php ..");
  error_log(dirname(dirname(__FILE__)) . "\wp-load.php");

  $date4 = "2018-07-14";
  // worked; created 900 keys.
  //generateKeys($date4);
  
  error_log("main.. $date4");

}

function misc() {
  $date1 = date("Y-m-d");   // this works
  $date2 = date('Y-m-d', strtotime('2012-08-07'));   // this works
  $date3 = strtotime('2012-08-07'); // does not match
  $date4 = "2018-07-13";  // this works

  // finding date 2 days earler
  echo "Date: " . "2012-08-07" . '<br/>';
  $date5 = date('Y-m-d', strtotime('2012-08-07') - (60*60*24*2));
  echo "Other Date: " . $date5 . '<br/>';

  //addData($date2,"115","215");

  echo "Seraching for " . $date4 . '<br/>';
  $count = getKeysByDate($date4);
  if ($count > 0) {
    echo "Rows found: " . $count;
  } else {
    echo "No rows found.";
  }
}

function getKeysByDate($keydate) {
  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys2";
  $query = "SELECT keydate, key1, key2 FROM " .$table_name. " WHERE keydate >= '" .$keydate. "'";
  $result = $wpdb->get_results($query);
  error_log("DbResult rows: " . sizeof($result));
  return sizeof($result);
}

function addData($keydate, $key1, $key2) {
  error_log("addData..");
  global $wpdb;
  $table_name = $wpdb->prefix."data_validation_keys2";
  $date1 = date("Y-m-d");
  // keydate needs qoutes around; key1 does not as it is number;
  $query = "INSERT INTO $table_name(keydate, key1, key2) VALUES('$keydate', $key1, $key2)";
  $count = $wpdb->query($query);
  error_log("Added rows: " .$count);
  error_log("addData.. exit");
  return $count;
}


function getKeys() {
  global $wpdb;
  $key1 = "101";
  $key2 = "201";
  $table_name = $wpdb->prefix."data_validation_keys";
  error_log("$wpdb->prefix");
  $query = "SELECT key1 FROM " .$table_name. " WHERE key1=" . $key1 . " AND key2=" . $key2;
  error_log("Query: " .$query);
  //$dbresult = $wpdb->query($query);
  $result = $wpdb->get_results($query);
  error_log("DbResult: " .$result[0]->key1);
  return "getKeys Result: " . $result[0]->key1;
}


function createTable() {
  error_log("createTable..");
  global $wpdb;

  $table_name = $wpdb->prefix."data_validation_keys2";
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

function generateKeys($keydate) {
  $key_max_size = 999;
  $key_count_value = 900;
  error_log("Generating " . $key_count_value . " keys for date: " . $keydate );

  $arr_key1 = UniqueRandomNumbersWithinRange(0,$key_max_size,$key_count_value);
  $arr_key2 = UniqueRandomNumbersWithinRange(0,$key_max_size,$key_count_value);

  $count = 0;
  for ($i=0; $i < $key_count_value; $i++) {
    $count += addData($keydate, $arr_key1[$i], $arr_key2[$i]);
  }

  error_log("generateKeys.. created " . $count);
}

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}
