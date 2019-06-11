<?php

$rows = 10;
$columns = 6;

if (isset($_SESSION['authenticated'])) {
    $authenticated = $_SESSION['authenticated'];
} else $authenticated = false;

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else $user = 'visitor';

if (isset($_SESSION['timeout'])) {
    $timeout = $_SESSION['timeout'];
} else $timeout = false;


