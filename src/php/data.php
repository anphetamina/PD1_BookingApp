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

            case 'getUser':
                if (isset($_SESSION['authenticated']) && isset($_SESSION['user']) && $_SESSION['authenticated']) {
                    $user = array($_SESSION['authenticated'], $_SESSION['user']);
                    echo json_encode($user); // logged in
                } else {
                    $user = array(null, false);
                    echo json_encode($user);
                }
                break;

            default:
                break;
        }

    }
}
