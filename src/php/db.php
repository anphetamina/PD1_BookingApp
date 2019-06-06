<?php

function db_get_vars() {
    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if ($connection->connect_errno)
            throw new Exception('Connection failed');

        $query = "select rows, columns from variable for update";

        $dims = array();

        if (!$stmt = $connection->prepare($query))
            throw new Exception('db_get_vars failed');

        $connection->autocommit(false);

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $dims[] = $row;
        }

        $connection->commit();
        $stmt->close();
        $connection->close();

        return json_encode($dims);

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
    }

    /*$connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if ($connection->connect_errno)
            throw new Exception('Connection failed');

        $query = "select rows, columns from variable for update";

        $dims = array();

        if ($stmt = $connection->prepare($query)) {
            $connection->autocommit(false);
            if (!$stmt->execute()) {
                throw new Exception('select rows, columns from variable failed');
            }
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $dims[] = $row;
            }

            $connection->commit();
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
    }*/
}

function db_get_seats() {
    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if ($connection->connect_errno)
            throw new Exception('Connection failed');

        $query = "select seat_id, state, user from seat for update";

        $seats = array();

        if ($stmt = $connection->prepare($query)) {
            $connection->autocommit(false);
            if (!$stmt->execute()) {
                throw new Exception('db_get_seats failed');
            }
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $seats[] = $row;
            }

            $connection->commit();
            $stmt->close();
            $connection->close();
            return json_encode($seats);
        }

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
    }


    /*$connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if ($connection->connect_errno)
            throw new Exception('Connection failed');

        $query = "select seat_id, state, user from seat for update";

        $seats = array();

        if (!$stmt = $connection->prepare($query))
            throw new Exception('db_get_seats failed');

        $connection->autocommit(false);

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $seats[] = $row;
        }

        $connection->commit();
        $stmt->close();
        $connection->close();

        return json_encode($seats);

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
    }*/
}

function db_get_seat_state($id) {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if ($connection->connect_errno)
            throw new Exception('Connection failed');

        $query = "select state from seat where seat_id = ? for update";

        if (!$stmt = $connection->prepare($query))
            throw new Exception('db_get_seat_state failed');

        $stmt->bind_param('s', $id);
        $connection->autocommit(false);

        $stmt->execute();
        $stmt->bind_result($state);

        $stmt->fetch();

        $connection->commit();
        $stmt->close();
        $connection->close();

        return $state;

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
    }

    /*$connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');

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

    $connection->close();*/
}
