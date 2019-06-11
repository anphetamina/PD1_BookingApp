<?php

$rows = 10;
$columns = 6;

include "session.php";


function checkEmail($username) {
    return filter_var($username, FILTER_VALIDATE_EMAIL) && htmlentities($username)==$username;
}

function checkPassword($psw) {
    $pattern = '/^[^\`\¬\`\¦\!\"\£\$\%\^\&\*\(\)\_\-\+\=\[\]\{\}\:\;\@\\\'\#\~\?\/\.\>\<\,\\\|\€\n\r\t]{1,100}$/';
    if (preg_match($pattern, $psw)) {
        return strlen($psw)>=2 && strlen($psw)<=100;
    }

    return false;
}

