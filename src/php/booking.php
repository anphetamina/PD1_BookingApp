<?php

include "common.php";
include "db.php";

if (!empty($_POST)) {

    if (isset($_POST['action']) && isset($_SESSION['user'])) {
        if (checkTime()) {
            echo TIMEOUT;
        }

        $action = $_POST['action'];
        $user = $_SESSION['user'];

        switch ($action) {
            case 'updateSeat':
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    echo selectSeat($id, $user);
                }
                break;

            case 'book':

                break;

            default:
                break;
        }
    } else {
        echo NOT_LOGGED_IN;
    }
}

function selectSeat($id, $user) {
    $connection = db_get_connection();

    $seat = db_get_seat($id);

    if ($seat['state'] === 'bought') $state = 'bought';
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
        $query = "update seat set user = ?, state = ? where seat_id = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("sss", $user, $state, $id);
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
        $state = 'booked';
        $query = "update seat set user = ?, state = ? where seat_id = ?";

        if($stmt = $connection->prepare($query)) {
            $stmt->bind_param("sss", $user, $state, $id);
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



    $connection->commit();
    $connection->close();
    return $state;
}