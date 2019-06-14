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
                if (isset($_POST['id']) && $_POST['current_state']) {
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
                if (db_update_booking($id, $user, $connection)) $state = 'selected';
                else $state = DB_ERROR;
            }
            /*
             * the seat has been booked by the user
             * remove the booking
             * */
            else {
                if (db_delete_booking($id, $connection)) $state = 'free';
                else $state = DB_ERROR;
            }
        }
    }

    $connection->commit();
    $connection->close();
    return $state;


    /*$connection = db_get_connection();

    $seat = db_get_seat($id);


    if ($seat['state'] === 'bought') $state = 'bought';
    elseif ($current_state === 'selected' && $seat['user'] !== $user && $seat['state'] === 'booked') $state = 'booked';
    elseif ($seat['state'] === 'booked' && $seat['user'] !== $user) {

        $query = "update seat set user = ? where seat_id = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("ss", $user, $id);
            try {
                if(!$stmt->execute())
                    throw new Exception('selectSeat failed');
                $stmt->close();
            } catch (Exception $exception) {
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
                $connection->close();
                echo 'Error';
            }
        }

        $state = 'selected';
    }
    elseif ($seat['state'] === 'booked' && $seat['user'] === $user) {
        $state = 'free';
        $query = "update seat set user = 'null', state = ? where seat_id = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("ss", $state, $id);
            try {
                if(!$stmt->execute())
                    throw new Exception('selectSeat failed');
                $stmt->close();
            } catch (Exception $exception) {
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
                $connection->close();
                echo 'Error';
            }
        }
    }
    else {

        $query = "update seat set user = ?, state = 'booked' where seat_id = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("ss", $user, $id);
            try {
                if(!$stmt->execute())
                    throw new Exception('selectSeat failed');
                $stmt->close();
            } catch (Exception $exception) {
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
                $connection->close();
                echo 'Error';
            }
        }

        $state = 'selected';
    }


    $connection->commit();
    $connection->close();
    return $state;*/
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
    if (empty($count)) {
        foreach ($selected_seats as $seat => $id) {
            db_buy_seat($id, $user, $connection);
        }

    }
    /*
     * one or more displayed selected seat has not been booked
     * free them
     * */
    else {
        foreach ($selected_seats as $seat => $id) {
            db_delete_booking($id, $connection);
        }
    }

    $connection->commit();
    $connection->close();
    return $seats;

    /*$seats = array();
    foreach ($selected_seats as $seat => $id) {
        $count = db_get_count_booked_seat_by_user($id, $user);
        if($count===0) $seats[] = $id;
    }

    $connection = db_get_connection();

    if (count($seats)===0) {

        $query = "update seat set state = 'bought' where user = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("s", $user);
            try {
                if(!$stmt->execute())
                    throw new Exception('bookSeats failed');
                $stmt->close();
            } catch (Exception $exception) {
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
//                $connection->close();
                echo 'Error';
            }
        }

    } else {


        $query = "update seat set state = 'free', user = 'null' where user = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("s", $user);
            try {
                if(!$stmt->execute())
                    throw new Exception('bookSeats failed');
                $stmt->close();
            } catch (Exception $exception) {
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
//                $connection->close();
                echo 'Error';
            }
        }


    }

    $connection->commit();
    $connection->close();

    return $seats;*/
}