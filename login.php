<?php
session_start();

include "src/php/auth.php";


if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'login' && !isset($_SESSION['user'])) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $response = login($username, $password);

                switch ($response) {
                    case LOGIN_SUCCESS:
                        $_SESSION['user'] = $username;
                        $_SESSION['time'] = time();
                        $_SESSION['timeout'] = false;
                        redirect('index.php');
                        break;

                    case LOGIN_FAILED:
                        redirect('login.php?msg=' . LOGIN_FAILED);
                        break;

                    case LOGIN_ERROR:
                        redirect('login.php?msg=' . LOGIN_ERROR);
                        break;

                    default:
                        // $msg = 'Messaggio non riconosciuto';
                        break;
                }


            }
        }


    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>s264014_BookingApp</title>
    <link rel="stylesheet" href="src/css/style.css"/>
    <script src="lib/jquery-3.4.1.min.js"></script>
    <script src="src/app/app.js"></script>
    <script type="text/javascript">
        $(function () {
            loadLoginForm();

        });
    </script>

</head>
<body>
<header>
    header
</header>


<div id="main-div">

    <noscript>
        <p>Per il corretto funzionamento del sito è necessario abilitare javascript</p>
    </noscript>

</div>

<div id="div-msg">
    <p id="p-msg">
        <?php

        if (!empty($_GET)) {
            if (isset($_GET['msg']) && isset($response)) {
                $msg = $_GET['msg'];

                switch ($msg) {
                    case LOGIN_SUCCESS:
                        if($response === LOGOUT_SUCCESS) echo 'Login effettuato';
                        break;

                    case LOGIN_FAILED:
                        if($response === LOGIN_FAILED) echo 'Password errata';
                        break;

                    case LOGIN_ERROR:
                        if($response === LOGIN_ERROR) echo 'Login non riuscito';
                        break;

                    default:
                        // $msg = 'Messaggio non riconosciuto';
                        break;
                }
            }
        }
        ?>
    </p>
</div>

<div id="navigation-div">
    <form action="index.php">
    <button id="map-button">Mappa</button>
    </form>

    <form action="register.php">
        <button id="register-button">Registrati</button>
    </form>
</div>


<footer>
    footer
</footer>
</body>
</html>
