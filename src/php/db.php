<?php

function db_get_vars() {
    $connection = new mysqli('127.0.0.1', 'root', '', 'variables');
    $stmt = null;

    try {
        if ($connection->connect_errno)
        throw new Exception('Connection failed');

        $query = "select rows, columns from variables";

        $dims = array();

        if ($stmt = $connection->prepare($query)) {
            $connection->autocommit(false);
            if (!$stmt->execute()) {
                throw new Exception('query execution failed');
            }
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $dims[] = $row;
            }
            $stmt->close();
            $connection->close();
            return json_encode($dims);
        }

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
    }
}

function db_get_seats() {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');

    if ($connection->connect_errno) {
        printf('Connection error');
    }

    $query = "select seat_id, state, user from seat";

    $seats = array();

    if ($stmt = $connection->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $seats[] = $row;
        }
        return json_encode($seats);
        $stmt->close();
    }

    $connection->close();
}

function db_get_seat_state($id) {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');

    if ($connection->connect_errno) {
        printf('[db_get_seat_state] connection error');
    }

    $query = "select state from seat where seat_id=?";

    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param(i, $id);
        $stmt->execute();
        $stmt->bind_result($state);
        while ($stmt->fetch()) {
            printf("[db_get_seat_state] %s", $state);
        }
        return json_encode($state);
        $stmt->close();
    }

    $connection->close();
}
