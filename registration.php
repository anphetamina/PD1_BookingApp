<?php
session_start();

if (!isset($_SESSION['cookie'])) {
    header("Location: index.php");
}

include "src/php/signup.php";
include "src/php/common.php";

if (isset($_SESSION['user'])) {
    redirect('home.php');
}

httpsRedirect();

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'registration' && !isset($_SESSION['user'])) {
            if (isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])) {
                $username = $_POST['username'];
                $password1 = $_POST['password1'];
                $password2 = $_POST['password2'];

                $response = registration($username, $password1, $password2);
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


    <script src="src/app/app.js"></script>
    <script type="text/javascript">
        $(function () {
            loadRegistrationForm();

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
            <h1>Registrazione<i class="fas fa-user-plus right"></i></h1>
        </div>
    </div>
    <div class="row">
        <nav id="navigation-nav" class="nav flex-column col-md-2">

            <li class="nav-item">
                <a class="nav-link" href="home.php"><i class="fa fa-plane"></i>Mappa</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i>Login</a>
            </li>

            <p id="response-msg">
                <!-- msg to be injected -->

                <?php

                if (isset($response) && !isset($_SESSION['user'])) {
                    switch ($response) {
                        case REGISTRATION_SUCCESS:
                            echo 'Registrazione avvenuta con successo';
                            break;

                        case REGISTRATION_FAILED:
                            echo 'Registrazione fallita';
                            break;

                        case USERNAME_ALREADY_EXISTS:
                            echo 'Username già esistente';
                            break;

                        case USERNAME_NOT_VALID:
                            echo 'Inserire un\'email valida';
                            break;

                        case PASSWORD_NOT_VALID:
                            echo 'La password deve contenere almeno un carattere minuscolo e un carattere maiuscolo o numero';
                            break;

                        case PASSWORD_NOT_EQUAL:
                            echo 'Le password non coincidono';
                            break;

                        case PASSWORD_NULL:
                            echo 'Password sbagliata';
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
            <!-- registration form to be injected -->

        </div>
    </div>
</div>

</body>
</html>