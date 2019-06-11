<?php
include "common.php";
include "db.php";

define('LOGIN_SUCCESS', 1111);
define('LOGIN_FAILED', 121234);
define('LOGIN_ERROR', 231);
define('LOGOUT_SUCCESS', 78);
define('LOGOUT_FAILED', 97);

// todo sanitize

function login($user, $psw) {
    $connection = db_get_connection();
    $result = false;
    $query = "select password from user where username=? for update";
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param('s', $user);
        try {
            if(!$stmt->execute())
                throw new Exception('login failed');
            $stmt->bind_result($hash);
            $stmt->fetch();
            $result = password_verify($psw, $hash);
            $stmt->close();
        } catch (Exception $exception) {
            /*$connection->rollback();
            print 'Rollback ' . $exception->getMessage();*/
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return LOGIN_ERROR;
        }
    }

    $connection->commit();
    $connection->close();

    return ($result)? LOGIN_SUCCESS : LOGIN_FAILED;
}

function logout() {
    if(destroySession()) return LOGOUT_SUCCESS;

    return LOGOUT_FAILED;
}