<?php

$rows = 10;
$columns = 6;

$seats_check = array();
$states_check = ['free', 'selected', 'booked', 'bought'];
for ($i = 0; $i < $rows; $i++) {
    for ($j = 0; $j < $columns; $j++) {
        $seats_check[] = chr(65+$j).strval(($i+1));
    }
}

include_once "session.php";
