<?php
include 'common.php';

global $rows;
global $columns;


if(!empty($_GET)) {
    if (isset($_GET['action'])) {

        $action = $_GET['action'];

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
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    echo db_get_seat_state($id);
                };
                break;

            default:
                break;
        }

    }
}
