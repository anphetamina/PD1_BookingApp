<?php
session_start();

if (!isset($_SESSION['cookie'])) {
    header("Location: index.php");
}


include "src/php/auth.php";
include "src/php/common.php";

if (isset($_SESSION['user'])) {
    redirect('home.php');
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
    <script src="lib/jquery-3.4.1.min.js"></script>

    <link rel="stylesheet" href="lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="lib/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/9e9e597745.js"></script>

    <link rel="stylesheet" href="src/css/style.css"/>
    <script src="src/app/app.js"></script>
    <script type="text/javascript">
        $(function () {
            loadLoginForm();

        });
    </script>

    <link rel="stylesheet" href="src/css/style.css"/>

</head>
<body>


<div id="header-div" class="container-fluid bg-dark">
    <span class="span-brand">Santoro Airlines</span>
</div>
<div id="content-div" class="container-fluid">
    <div class="row">
        <div class="col">
            <h1>Login<i class="fas fa-sign-in-alt right"></i></h1>
        </div>
    </div>
    <div class="row">
        <nav id="navigation-nav" class="nav flex-column col-md-2">

            <li class="nav-item">
                <a class="nav-link" href="home.php"><i class="fa fa-plane"></i>Mappa</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="registration.php"><i class="fas fa-user-plus"></i>Registrazione</a>
            </li>

            <p id="response-msg">
                <!-- msg to be injected -->

                <?php

                if (isset($response) && !isset($_SESSION['user'])) {
                    switch ($response) {
                        case LOGIN_FAILED:
                            echo 'Login fallito';
                            break;

                        case LOGIN_ERROR:
                            echo 'Errore login';
                            break;

                        case DB_ERROR:
                            echo 'Errore database';
                            break;

                        default:
                            break;
                    }
                }

                ?>

            </p>
        </nav>

        <div id="main-div" class="col-md-10 p-2 table-responsive-sm">
            <noscript>
                <p>Per il corretto funzionamento del sito è necessario abilitare javascript</p>
            </noscript>
            <!-- login form to be injected -->

        </div>
    </div>
</div>

</body>
</html>
