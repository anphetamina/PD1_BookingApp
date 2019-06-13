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

            case 'bookSeats':

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
    return $state;
}

function bookSeats() {

}