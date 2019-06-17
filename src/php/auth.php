<?php
include "common.php";
include "db.php";

define('LOGIN_SUCCESS', 'loginSuccess');
define('LOGIN_FAILED', 'loginFailed');
define('LOGIN_ERROR', 'loginError');
define('LOGOUT_SUCCESS', 'logoutSuccess');
define('LOGOUT_ERROR', 'logoutError');
define('LOGOUT_FAILED', 'logoutFailed');
define('PASSWORD_NOT_VALID', 'passwordNotValid');
define('USERNAME_NOT_VALID', 'usernameNotValid');

function login($user, $psw) {

    if (!checkEmail($user)) return USERNAME_NOT_VALID;
    if (!checkPassword($psw)) return PASSWORD_NOT_VALID;

    $connection = db_get_connection();
    if ($connection === DB_ERROR) {
        return LOGIN_ERROR;
    } else {
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

}

function logout() {
    if(destroySession()){
        return LOGOUT_SUCCESS;
    } else return LOGOUT_FAILED;
}

function checkEmail($username) {
    return filter_var($username, FILTER_VALIDATE_EMAIL) && htmlentities($username)==$username && strip_tags($username)==$username;
}

function checkPassword($psw) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z0-9]).{2,}$/';
    if (preg_match($pattern, $psw)) {
        return strlen($psw)>=2 && strlen($psw)<=100;
    }

    return false;
}