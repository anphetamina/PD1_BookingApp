<?php
session_start();

if (!isset($_SESSION['cookie'])) {
    header("Location: index.php");
}

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

        global $seats_check;
        global $states_check;

        switch ($action) {
            case 'updateSeat':
                if (isset($_POST['id']) && isset($_POST['current_state'])) {
                    $id = $_POST['id'];
                    $current_state = $_POST['current_state'];

                    /*
                     * if has been injected any $id or $state not valid
                     * it will exit
                     * */
                    if (!in_array($id, $seats_check) || !in_array($current_state, $states_check)) {
                        // echo INVALID_INPUT;
                        exit();
                    }

                    echo selectSeat($id, $current_state, $user);
                }
                break;

            case 'buySeats':
                if (isset($_POST['selected_seats'])) {
                    $selected_seats = json_decode($_POST['selected_seats']);

                    /*
                     * check if has been injected any not valid seat $id
                     * exit if true
                     * */
                    foreach ($selected_seats as $index => $id) {
                        if (!in_array($id, $seats_check)){
                            // echo INVALID_INPUT;
                            exit();
                        }
                    }


                    $not_purchased_seats = array();
                    if (!empty($selected_seats)) {
                        $not_purchased_seats = buySeats($selected_seats, $user);
                        if ($not_purchased_seats === DB_ERROR) echo DB_ERROR;
                        else echo json_encode($not_purchased_seats);
                    } else echo NOT_LOGGED_IN; // double check


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
    if ($connection === DB_ERROR) {
        return DB_ERROR;
    } else {
        $seat = db_get_seat($id, $connection);

        if ($seat === DB_ERROR) {
            return DB_ERROR;
        } else {
            /*
         * the seat is free so it can be booked by the user
         * */
            if (empty($seat)) {
                if (db_book_seat($id, $user, $connection)) $state = 'selected';
                else return DB_ERROR;
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
                            else return DB_ERROR;
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
                            else return DB_ERROR;
                        }


                    }
                }
            }

            $connection->commit();
            $connection->close();
            return $state;
        }

    }



}

function buySeats($selected_seats, $user) {

    $connection = db_get_connection();

    if ($connection === DB_ERROR) {
        return DB_ERROR;
    } else {
        $seats = array();
        /*
         * check if any of the selected seat has been booked or bought by other users
         * */
        foreach ($selected_seats as $seat => $id) {
            $count = db_get_count_booked_or_bought_seat_by_other_users($id, $user, $connection);
            if ($count === DB_ERROR) return DB_ERROR;
            elseif($count>0) $seats[] = $id;
        }

        /*
         * all selected seats has not been booked or bought by other users
         * update them as bought
         * */
        if (empty($seats)) {
            foreach ($selected_seats as $seat => $id) {
                /*
                 * check the seat state
                 * */
                $state = db_check_seat_state_by_user($id, $user, $connection);
                if ($state === DB_ERROR) return DB_ERROR;
                /*
                 * insert the bought seat
                 * */
                elseif ($state === null) {
                    $result = db_insert_bought_seat($id, $user, $connection);
                    if ($result === DB_ERROR) return DB_ERROR;
                }
                /*
                 * update the booked seat
                 * */
                elseif ($state === 'booked') {
                    $result = db_update_booked_seat($id, $user, $connection);
                    if ($result === DB_ERROR) return DB_ERROR;
                }
                /*
                 * the seat has been already been bought by the user
                 * */
                else null;
            }

        }
        /*
         * one or more displayed selected seat has been booked or bought by other users
         * delete all the selected seats from the user bookings
         * */
        else {
            foreach ($selected_seats as $seat => $id) {
                $result = db_delete_booking_by_user($id, $user, $connection);
                if ($result === DB_ERROR) return DB_ERROR;
            }
        }

        $connection->commit();
        $connection->close();
        return $seats;
    }


}