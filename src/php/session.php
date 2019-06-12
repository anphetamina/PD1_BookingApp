<?php

if (isset($_SESSION['count'])) {
    $count = $_SESSION['count']++;
} else $count = $_SESSION['count'] = 0;

define('TIMEOUT', 'timeOut');
define('TIMEOUT_ERROR', 'timeOutError');
define('NOT_LOGGED_IN', 'notAuthenticated');

function testCookie() {

}

function destroySession() {
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        $_SESSION = array();
        session_destroy();
        return true;
    }

    return false;
}

function checkTime() {
    if (isset($_SESSION['time']) && isset($_SESSION['user'])) {
        $diff = time() - $_SESSION['time'];

        if($diff > 2*60) { // minutes

            if(destroySession()) {
                return true;
            }
            else return false;
        }

        $_SESSION['time'] = time();
    }

    return false;
}

function redirect($page) {
    header('Location: '.$page);
    exit();
}

function httpsRedirect(){
    if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']==='off'){
        $page = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: ". $page);
        exit();
    }
}