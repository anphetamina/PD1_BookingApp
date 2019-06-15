<?php

include "db.php";

define('REGISTRATION_SUCCESS', 0);
define('REGISTRATION_FAILED', -1);
define('USERNAME_ALREADY_EXISTS', -2);
define('USERNAME_NOT_VALID', -3);
define('PASSWORD_NOT_EQUAL', -4);
define('PASSWORD_NOT_VALID', -5);
define('PASSWORD_NULL', -6);
define('INPUT_NOT_VALID', -7);

function checkUser($username, $connection) {

    $result = false;
    $count = 0;
    $query = "select count(*) from user where username=? for update";
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param('s', $username);
        try {
            if(!$stmt->execute())
                throw new Exception('checkUser failed');
            $stmt->bind_result($count);
            $stmt->fetch();
            if($count===0) $result = true; // user does not exists
            $stmt->close();
        } catch (Exception $exception) {
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    return $result;
}

function register($user, $psw1, $psw2) {

    $connection = db_get_connection();
    if ($connection === DB_ERROR) {
        return DB_ERROR;
    } else {
        if ($psw1!=$psw2) return PASSWORD_NOT_EQUAL;
        if (!checkPassword($psw1)) return PASSWORD_NOT_VALID;

        $result = checkUser($user, $connection);
        if ($result === DB_ERROR) {
            return DB_ERROR;
        } elseif (!$result) return USERNAME_ALREADY_EXISTS;

        if (!checkEmail($user)) return USERNAME_NOT_VALID;

        $query = "insert into user (username, password) values (?, ?)";
        if($stmt = $connection->prepare($query)) {

            if (!$hash = password_hash($psw1, PASSWORD_DEFAULT)) {
                $connection->close();
                return PASSWORD_NULL;
            }

            $stmt->bind_param('ss', $user, $hash);
            try {
                if(!$result = $stmt->execute())
                    throw new Exception('register failed');
                $stmt->close();
            } catch (Exception $exception) {
                $connection->rollback();
                print 'Rollback ' . $exception->getMessage();
                $connection->autocommit(true);
                if($stmt!=null) $stmt->close();
                $connection->close();
                return REGISTRATION_FAILED;
            }
        }

        $connection->commit();
        $connection->close();

        return REGISTRATION_SUCCESS;
    }


}

function checkEmail($username) {
    return filter_var($username, FILTER_VALIDATE_EMAIL) && htmlentities($username)==$username;
}

function checkPassword($psw) {
    $pattern = '/^[a-z]+[A-Z0-9]+[a-zA-Z0-9]*|[A-Z0-9]+[a-z]+[a-zA-Z0-9]*$/';
    if (preg_match($pattern, $psw)) {
        return strlen($psw)>=2 && strlen($psw)<=100;
    }

    return false;
}