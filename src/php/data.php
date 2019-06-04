<?php
include "db.php";

$rows = 6;
$columns = 10;

if (isset($_POST['action'])) {

    $action = $_POST['action'];

    if($action == 'getDims') {
        $dimensions = array($rows, $columns);
        echo json_encode($dimensions);
    }

    if ($action == 'getSeats') {
        echo db_get_seats();
    }

}