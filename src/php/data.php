<?php
session_start();

if (!isset($_SESSION['cookie'])) {
    header("Location: index.php");
}

include "common.php";
include "db.php";

global $rows;
global $columns;

define('VAR_NOT_VALID', 'varNotValid');


if(!empty($_GET)) {
    if (isset($_GET['action'])) {

        $action = $_GET['action'];

        switch ($action) {

            case 'getDims':
                if ($rows !== 0 && $columns !== 0) {
                    $dim = array($rows, $columns);
                    echo json_encode($dim);
                } else
                    echo VAR_NOT_VALID;

                break;

            case 'getSeats':
                $seats = db_get_seats();
                if ($seats === DB_ERROR) {
                    echo DB_ERROR;
                } else
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
