<?php
include 'common.php';

checkSession();

if (!isset($_SESSION['seats'])) {
    $_SESSION['seats'] = 0;
} else {
    if (!empty($_POST)) {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            switch ($action) {
                case 'bookSeats':
                    ;
                    break;
                case 'addSeat':
                    $_SESSION['seats']++;
                    break;

                default:
                    break;
            }
        }
    }
}