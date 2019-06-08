<?php

include 'db.php';

session_start();

if (isset($_COOKIE)) {
    if (isset($_COOKIE['user'])) {
        $user = $_COOKIE['user'];
    }
}

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