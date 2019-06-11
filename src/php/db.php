<?php

include "common.php";

define('HOST', '127.0.0.1');
define('USER', 'root');
define('PASS', '');
define('DB', 'booking_app');
define('DB_OK', 10);


function db_get_connection() {
    $connection = mysqli_connect(HOST, USER, PASS, DB);
    if (!$connection) {
        die('Connection error '.mysqli_errno().' '.mysqli_connect_error());
    }
    $connection->autocommit(false);
    return $connection;
}

function db_get_vars() {

    $connection = db_get_connection();
    $query = "select rows, columns from variable for update";
    $dims = array();
    if($stmt = $connection->prepare($query)) {
        try {
            if(!$stmt->execute())
                throw new Exception('db_get_vars failed');
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc())
                $dims = $row;
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return null;
        }
    }

    $connection->commit();
    $connection->close();
    return $dims;
}

function db_get_seats() {

    $connection = db_get_connection();
    $query = "select seat_id, state, user from seat for update";
    $seats = array();
    if($stmt = $connection->prepare($query)) {
        try {
            if(!$stmt->execute())
                throw new Exception('db_get_seats failed');
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc())
                $seats[] = $row;
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return null;
        }
    }

    $connection->commit();
    $connection->close();
    return $seats;
}

function db_get_seat_state($id) {

    $connection = db_get_connection();
    $query = "select state from seat where seat_id = ? for update";
    $state = null;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("s", $id);
        try {
            if(!$stmt->execute())
                throw new Exception('db_get_seat_state failed');
            $stmt->bind_result($state);
            $stmt->fetch();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return $state;
        }
    }

    $connection->commit();
    $connection->close();
    return $state;
}

function db_count($table) {

    $connection = db_get_connection();
    $query = 'select count(*) from ' . $table . ' for update';
    $count = 0;
    if($stmt = $connection->prepare($query)) {
        try {
            if(!$stmt->execute())
                throw new Exception('db_count failed');
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return $count;
        }
    }

    $connection->commit();
    $connection->close();
    return $count;
}

function db_delete($table) {

    $connection = db_get_connection();
    $query = "delete from " . $table;
    $result = false;
    if($stmt = $connection->prepare($query)) {
        try {
            if(!$result = $stmt->execute())
                throw new Exception('db_delete failed');
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            print 'Rollback ' . $exception->getMessage();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return $result;
        }
    }

    $connection->commit();
    $connection->close();
    return $result;
}

function db_seat_init() {
    global $rows;
    global $columns;

    $connection = db_get_connection();
    $result = false;
    $stmt = $connection->init();
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $columns; $j++) {
            $query = "insert into seat (seat_id, state, user) values (?, 'free', 'null')";
            if($stmt = $connection->prepare($query)) {
                $stmt->bind_param("s",strval(($i+1).chr(65+$j)));
                try {
                    if(!$result = $stmt->execute())
                        throw new Exception('db_seat_init failed');
                } catch (Exception $exception) {
                    $connection->rollback();
                    print 'Rollback ' . $exception->getMessage();
                    $connection->autocommit(true);
                    if($stmt!=null) $stmt->close();
                    $connection->close();
                    return $result;
                }
            }
        }
    }


    if($stmt!=null) $stmt->close();
    $connection->commit();
    $connection->close();
    return $result;
}

function db_vars_init() {
    global $rows;
    global $columns;

    $connection = db_get_connection();
    $query = "insert into variable (rows, columns) value (?, ?)";
    $result = false;
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ii", $rows, $columns);
        try {
            if(!$result = $stmt->execute())
                throw new Exception('db_vars_init failed');
            $stmt->close();
        } catch (Exception $exception) {
            $connection->rollback();
            print 'Rollback ' . $exception->getMessage();
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return $result;
        }
    }

    $connection->commit();
    $connection->close();
    return $result;
}

function db_init() {
    global $rows;
    global $columns;

    $dims = db_get_vars();

    if ($dims['rows'] != $rows || $dims['columns'] != $columns) {

        db_delete('seat');
        db_delete('variable');
        db_seat_init();
        db_vars_init();

    }

    return DB_OK;
}
