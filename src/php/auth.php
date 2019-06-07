<?php
include 'db.php';

session_start();

if (isset($_COOKIE)) {
    foreach ($_COOKIE as $key => $value) {
        echo $key . ' ' . $value;
    }
}


function login($user, $psw) {
    $connection = db_get_connection();
    $result = false;
    $query = 'select password from user where username=?';
    if($stmt = $connection->prepare($query)) {
        $stmt->bind_param('s', $user);
        try {
            if(!$stmt->execute())
                throw new Exception('login failed');
            $stmt->bind_result($result);
            $stmt->fetch();
            $result = password_verify($psw, $result);
            $stmt->close();
        } catch (Exception $exception) {
            /*$connection->rollback();
            print 'Rollback ' . $exception->getMessage();*/
            $connection->autocommit(true);
            if($stmt!=null) $stmt->close();
            $connection->close();
            return DB_ERROR;
        }
    }

    $connection->commit();
    $connection->close();

    return $result;
}

function logout() {
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        session_destroy();
    }
}

function checkSession() {

}

function checkTime() {
    $diff = time() - $_SESSION['time'];

    if($diff > 2*60) { // minutes
        logout();
    }

    $_SESSION['time'] = time();
}

function redirect($page) {
    header('Location>: '.$page);
    exit();
}

function httpsRedirect() {
    if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        print 'Https request already made';
    } else {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirect);
        exit();
    }
}

// todo test cookie