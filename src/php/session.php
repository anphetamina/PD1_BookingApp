<?php

function startSession() {
    session_start();
    $_SESSION['authenticated'] = true;
    $_SESSION['time'] = time();
}

function destroySession() {
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        session_destroy();
        $_SESSION['authenticated'] = false;
        return true;
    }

    return false;
}

function checkSession() {
    if (isset($_SESSION['authenticated'])) {
        checkTime();
    } else 'Not allowed';
}

function checkTime() {
    $diff = time() - $_SESSION['time'];

    if($diff > 2*60) { // minutes
        logout();
    }

    $_SESSION['time'] = time();
}

function redirect($page) {
    header('Location: '.$page);
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