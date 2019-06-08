<?php
include 'db.php';

if (!isset($_SESSION['seats'])) {
    $_SESSION['seats'] = 0;
} else {
    if (!empty($_POST)) {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            switch ($action) {
                case 'bookSeats':
                    echo db_book_seats($user);
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