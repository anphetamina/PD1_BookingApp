<?php
include "db.php";

$rows = 10;
$columns = 6;

if (isset($_POST['action'])) {

    $action = $_POST['action'];

    if($action == 'initDB') {
        echo db_init();
    }

    if($action == 'getDims') {
        $dim = array($rows, $columns);
        echo json_encode($dim);
    }

    if ($action == 'getSeats') {
        echo json_encode(db_get_seats());
    }

    if ($action == 'getSeatState') {
        echo json_encode(db_get_seat_state($_POST['id']));
    }

}