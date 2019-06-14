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

            case 'getDims':
                $dim = array($rows, $columns);
                echo json_encode($dim);
                break;

            case 'getSeats':
                $seats = db_get_seats();
                echo json_encode($seats);
                break;

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
