<?php
session_start();

if (!isset($_GET['cookie'])) {
    setcookie('test', 'check', time() + 3600);
    header("Location: " . $_SERVER['PHP_SELF'] . "?cookie=check");
} else {
    if (count($_COOKIE) > 0 && $_COOKIE['test'] === 'check') {
        $_SESSION['cookie'] = 'enabled';
        setcookie('test', 'check', time() - 3600*24);
        header("Location: home.php");
    } else {
        header("Location: block.php");
    }
}