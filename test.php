<?php
    $connection = new mysqli('127.0.0.1', 'root', '', 'booking_app');
    $stmt = $connection->init();

    try {
    if ($connection->connect_errno)
    throw new Exception('Connection failed');

    $query = "select state from seat where seat_id=? for update";


    if (!$stmt = $connection->prepare($query))
    throw new Exception('db_get_seat_state failed');
    $id = '1A';
    $stmt->bind_param('s', $id);
    $connection->autocommit(false);


    $stmt->execute();
    $stmt->bind_result($state);

    $stmt->fetch();

    echo $state;

    $connection->commit();
    $stmt->close();
    $connection->close();

    } catch (Exception $e) {
        $connection->rollback();
        echo 'Rollback ' . $e->getMessage();
        $connection->autocommit(true);
        if($stmt!=null) $stmt->close();
        $connection->close();
    }