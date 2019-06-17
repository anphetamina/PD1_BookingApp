<?php

$rows = 10;
$columns = 6;

$seats_check = array();
$states_check = ['free', 'selected', 'booked', 'bought'];
for ($i = 0; $i < $rows; $i++) {
    for ($j = 0; $j < $columns; $j++) {
        $seats_check[] = strval(($i+1).chr(65+$j));
    }
}

define('INVALID_INPUT', 'invalidInput');

include_once "session.php";
