<?php
include "db.php";

session_start();

$user = null;

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
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            echo db_get_seat_state($id);
        }
    }

}