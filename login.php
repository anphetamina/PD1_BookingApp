<?php
session_start();


include "src/php/auth.php";
include "src/php/common.php";

if (isset($_SESSION['user'])) {
    redirect('index.php');
}

httpsRedirect();


if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'login' && !isset($_SESSION['user'])) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $response = login($username, $password);

                if ($response === LOGIN_SUCCESS) {
                    $_SESSION['user'] = $username;
                    $_SESSION['time'] = time();
                    redirect('index.php');
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

        if (isset($response) && !isset($_SESSION['user'])) {
            switch ($response) {
                case LOGIN_FAILED:
                    echo 'Login fallito';
                    break;

                case LOGIN_ERROR:
                    echo 'Errore login';
                    break;

                default:

                    break;
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
