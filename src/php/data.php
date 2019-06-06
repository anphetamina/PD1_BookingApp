<?php
include "db.php";

$rows = 10;
$columns = 6;

if (isset($_POST['action'])) {

    $action = $_POST['action'];

    if($action == 'getDims') {
        $dim = array($rows, $columns);
        echo json_encode($dim);
    }

    if ($action == 'getSeats') {
        echo db_get_seats();
    }

}