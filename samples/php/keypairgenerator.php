<?php
// Creating valid key pairs between a range of values.

//Option 1

$arr_key1 = array();
for ($i=1;$i<=10;$i++) {
    $arr_key1[] = $i;
}
shuffle($arr_key1);

$arr_key2 = array();
for ($i=11;$i<=20;$i++) {
    $arr_key2[] = $i;
}
shuffle($arr_key2);

print_r($arr_key1);
print_r($arr_key2);

for ($i=0;$i<10;$i++) {
    echo "Keys:" . $arr_key1[$i] . "-" . $arr_key2[$i] . "\n";
}

// Option 2

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

$arr_key1 = UniqueRandomNumbersWithinRange(0,25,5);
$arr_key2 = UniqueRandomNumbersWithinRange(25,50,5);

for ($i=0;$i<5;$i++) {
    echo "Keys:" . $arr_key1[$i] . "-" . $arr_key2[$i] . "\n";
}

