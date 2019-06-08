<?php
include 'db.php';

session_start();

global $rows;
global $columns;


if(!empty($_POST)) {
    if (isset($_POST['action'])) {

        $action = $_POST['action'];

        switch ($action) {
            case 'initDB':
                echo db_init();
                break;

            case 'getDims':
                $dim = array($rows, $columns);
                echo json_encode($dim);
                break;

            case 'getSeats':
                echo json_encode(db_get_seats());
                break;

            case 'getSeatState':
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    echo db_get_seat_state($id);
                };
                break;

            default:
                break;
        }

    }
}
