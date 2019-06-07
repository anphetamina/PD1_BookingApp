<?php

define('DB_OK', 0);
define('DB_INIT', 1);
define('DB_ERROR', -1);
define('DB_ROLLBACK', -2);

function db_get_vars() {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if (mysqli_connect_error($connection))
            throw new Exception('Connection failed');

        $query = "select rows, columns from variable for update";

        $dims = array();

        if (!$stmt = $connection->prepare($query))
            throw new Exception('db_get_vars failed');

        $connection->autocommit(false);

        if (!$stmt->execute()) {
            throw new Exception('db_get_vars failed');
        }
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $dims = $row;
        }

        $connection->commit();
        $stmt->close();
        $connection->close();

        return $dims;

    } catch (Exception $e) {
        $connection->rollback();
        print 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
        return DB_ROLLBACK;
    }
}

function db_get_seats() {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if (mysqli_connect_error($connection))
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
            return $seats;
        }

    } catch (Exception $e) {
        $connection->rollback();
        print 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
        return DB_ROLLBACK;
    }
}

function db_get_seat_state($id) {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
        if (mysqli_connect_error($connection))
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
        print 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
        return DB_ROLLBACK;
    }
}

function db_count($table) {

    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');

    try {
        if (mysqli_connect_error($connection))
            throw new Exception('Connection failed');

        $query = 'select * from ' . $table;

        $connection->autocommit(false);

        $result = $connection->query($query);

        $connection->commit();
        $connection->close();

        $count = $result->num_rows;
        $result->close();

        return $count;

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        $connection->close();
    }
}

function db_delete($table) {
    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');

    try {
        if (mysqli_connect_error($connection))
            throw new Exception('Connection failed');

        $query = "delete from " . $table;

        $connection->autocommit(false);

        if (!$connection->query($query)) {
            throw new Exception;
        }

        $connection->commit();
        $connection->close();


        return true;

    } catch (Exception $e) {
        $connection->rollback();
        print 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        $connection->close();
    }

    return false;
}

function db_init() {
    global $rows;
    global $columns;

    $dims = db_get_vars();

    if ($dims['rows'] != $rows || $dims['columns'] != $columns) {

        db_delete('seat');

        $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');

        try {
            if (mysqli_connect_error($connection))
                throw new Exception('Connection failed');
            $connection->autocommit(false);

            $query = "update variable set rows=?, columns=?";

            if(!$stmt = $connection->prepare($query))
                throw new Exception('Insert failed');
            $stmt->bind_param("ii", $rows, $columns);
            $stmt->execute();

            $stmt = $connection->init();

            $index = 0;
            for ($i = 0; $i < $rows; $i++) {
                for ($j = 0; $j < $columns; $j++) {
                    $query = "insert into seat (row_id, seat_id, state, user) values (?, ?, 'free', 'null')";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("is", $index,strval(($i+1).chr(65+$j)));
                    $stmt->execute();
                    $index++;
                }
            }

            $stmt->close();
            $connection->commit();
            $connection->close();

        } catch (Exception $e) {
            $connection->rollback();
            echo 'Rollback ' . $e->getMessage();
            $connection->autocommit(true);
            $connection->close();
            return DB_ERROR;
        }

    }

    return DB_OK;
}
