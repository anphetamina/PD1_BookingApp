<?php

include "common.php";

define('HOST', '127.0.0.1');
define('USER', 'root');
define('PASS', '');
define('DB', 'booking_app');
define('DB_OK', 'dbOk');
define('DB_ERROR', 'dbError');
define('NULL', 'null');

function db_get_connection() {

    $connection = mysqli_connect(HOST, USER, PASS, DB);
    if (!$connection) {
        return DB_ERROR;
    }
    $connection->autocommit(false);
    return $connection;
}

function db_book_seat($id, $user, $connection) {
    $query = "insert into seat (seat_id, state, user) values (?, 'booked', ?)";
    $result = false;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $id, $user);
        try {
            if(!$result = $stmt->execute())
                throw new Exception();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $result;
}

function db_check_seat_state_by_user($id, $user, $connection) {
    $query = "select state from seat where seat_id = ? and user = ?";
    $state = null;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $id, $user);
        try {
            if(!$result = $stmt->execute())
                throw new Exception();
            $stmt->bind_result($state);
            $stmt->fetch();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $state;
}

function db_update_booked_seat($id, $user, $connection) {

    $query = "update seat set state = 'bought' where seat_id = ? and user = ?";
    $count = 0;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $id, $user);
        try {
            if(!$result = $stmt->execute())
                throw new Exception();
            $count = $stmt->affected_rows;
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $count;
}

function db_insert_bought_seat($id, $user, $connection) {

    $query = "insert into seat (seat_id, state, user) values (?, 'bought', ?)";
    $result = false;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $id, $user);
        try {
            if(!$result = $stmt->execute())
                throw new Exception();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $result;
}

function db_update_booking($id, $user, $connection) {

    $query = "update seat set user = ? where seat_id = ?";
    $result = false;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $user, $id);
        try {
            if(!$result = $stmt->execute())
                throw new Exception();
            if ($stmt->affected_rows === 1) $result = true; // double check
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $result;
}

function db_delete_booking($id, $connection) {
    $query = "delete from seat where seat_id = ?";
    $result = false;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("s", $id);
        try {
            if(!$result = $stmt->execute())
                throw new Exception('db_update_booking failed');
            if ($stmt->affected_rows === 1) $result = true; // double check
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $result;
}

function db_delete_booking_by_user($id, $user, $connection) {
    $query = "delete from seat where seat_id = ? and user = ? and state = 'booked'";
    $result = false;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $id, $user);
        try {
            if(!$result = $stmt->execute())
                throw new Exception('db_update_booking failed');
            if ($stmt->affected_rows === 0) $result = false; // double check
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $result;
}

function db_get_count_booked_or_bought_seat_by_other_users($id, $user, $connection) {

    $count = 0;
    $query = "select count(*) from seat where seat_id = ? and user <> ? and (state = 'bought' || state = 'booked') for update";
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ss", $id, $user);
        try {
            if(!$stmt->execute())
                throw new Exception('db_get_count_booked_or_bought_seat_by_other_users failed');
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $count;
}

function db_get_seat($id, $connection) {

    $query = "select state, user from seat where seat_id = ? for update";
    $seat = array();
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("s", $id);
        try {
            if(!$stmt->execute())
                throw new Exception('db_get_seat failed');
            $result = $stmt->get_result();
            $seat = $result->fetch_assoc();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $seat;
}

/*
 * called once so the connection has been open and closed inside the function
 * */
function db_get_seats() {

    $connection = db_get_connection();
    if (!$connection) {
        return false;
    } else {
        $query = "select seat_id, state, user from seat for update";
        $seats = array();
        if($stmt = $connection->prepare($query)) {
            try {
                if(!$stmt->execute())
                    throw new Exception('db_get_seats failed');
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc())
                    $seats[$row['seat_id']] = $row;
                $stmt->close();
            } catch (Exception $exception) {
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
                $connection->close();
                return DB_ERROR;
            }
        }

        $connection->commit();
        $connection->close();
        return $seats;
    }

}
