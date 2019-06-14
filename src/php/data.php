<?php
session_start();

include "common.php";
include "db.php";

global $rows;
global $columns;


if(!empty($_GET)) {
    if (isset($_GET['action'])) {

        $action = $_GET['action'];

        switch ($action) {
            case 'initDB':
//                echo db_init(); // automatic reset
                echo DB_OK;
                break;

            case 'getDims':
                $dim = array($rows, $columns);
                echo json_encode($dim);
                break;

            case 'getSeats':
                $seats = db_get_seats();
                echo json_encode($seats);
                break;

            /*case 'getSeatState':
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $state = db_get_seat_state($id);
                    if ($state !== null && $state !== DB_ERROR) echo $state;
                    else echo DB_ERROR;
                };
                break;*/

            case 'getUser':
                if (isset($_SESSION['user'])) {
                    echo $_SESSION['user'];
                } else echo 'null';
                break;


            default:
                break;
        }

    }
}
