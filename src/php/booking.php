<?php
session_start();

include "common.php";
include "db.php";

if (!empty($_POST)) {

    if (isset($_POST['action']) && isset($_SESSION['user'])) {
        if (checkTime()) {
            echo TIMEOUT;
            exit();
        }

        $action = $_POST['action'];
        $user = $_SESSION['user'];

        switch ($action) {
            case 'updateSeat':
                if (isset($_POST['id']) && isset($_POST['current_state'])) {
                    $id = $_POST['id'];
                    $current_state = $_POST['current_state'];
                    echo selectSeat($id, $current_state, $user);
                }
                break;

            case 'buySeats':
                if (isset($_POST['selected_seats'])) {
                    $selected_seats = json_decode($_POST['selected_seats']);
                    $not_purchasable_seats = array();
                    if (!empty($selected_seats)) {
                        $not_purchasable_seats = buySeats($selected_seats, $user);
                        echo json_encode($not_purchasable_seats);
                    } else echo DB_ERROR;


                }
                break;

            default:
                break;
        }
    } else {
        echo NOT_LOGGED_IN;
    }
}

function selectSeat($id, $current_state, $user) {


    $connection = db_get_connection();


    $seat = db_get_seat($id, $connection);

    /*
     * the seat is free so it can be booked by the user
     * */
    if (empty($seat)) {
        if (db_book_seat($id, $user, $connection)) $state = 'selected';
        else $state = DB_ERROR;
    } else {
        /*
         * the seat has already been booked or bought
         * */
        if ($seat['state'] === 'bought') $state = 'bought';
        else {
            /*
             * the seat has been booked by another user
             * update the current booking
             * */
            if ($seat['user'] !== $user) {

                /*
                 * the selected seat is displayed as selected (yellow)
                 * cancel your booking
                 * */
                if ($current_state === 'selected') $state = 'booked';
                /*
                 * overwrite the booking
                 * */
                else {
                    if (db_update_booking($id, $user, $connection)) $state = 'selected';
                    else $state = DB_ERROR;
                }
            }
            /*
             * the seat has been booked by the user
             * remove the booking
             * */
            else {
                /*
                 * the selected seat is displayed as free (green)
                 * reconfirm the booking
                 * */
                if ($current_state === 'free') $state = 'selected';
                /*
                 * delete the booking
                 * */
                else {
                    if (db_delete_booking($id, $connection)) $state = 'free';
                    else $state = DB_ERROR;
                }


            }
        }
    }

    $connection->commit();
    $connection->close();
    return $state;
}

function buySeats($selected_seats, $user) {

    $connection = db_get_connection();

    $seats = array();
    foreach ($selected_seats as $seat => $id) {
        $count = db_get_count_booked_seat_by_user($id, $user, $connection);
        if($count===0) $seats[] = $id;
    }

    /*
     * all selected seats has been booked by the user
     * update them as bought
     * */
    if (empty($seats)) {
        foreach ($selected_seats as $seat => $id) {
            db_buy_seat($id, $user, $connection);
        }

    }
    /*
     * one or more displayed selected seat has not been booked
     * free them
     * */
    else {
        foreach ($seats as $seat => $id) {
            db_delete_booking_by_user($id, $user, $connection);
        }
    }

    $connection->commit();
    $connection->close();
    return $seats;
}