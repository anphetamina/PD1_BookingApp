<?php
session_start();

if (!isset($_SESSION['cookie'])) {
    header("Location: index.php");
}

include "src/php/common.php";
include "src/php/auth.php";

if (isset($_SESSION['user'])) {
    httpsRedirect();
}

$timeout = checkTime();

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

    <script src="src/app/model/map.js"></script>
    <script src="src/app/controller/map.js"></script>
    <script src="src/app/view/map.js"></script>
    <script src="src/app/app.js"></script>
    <script type="text/javascript">
        $(function () {
            loadMap();

        });
    </script>

    <link rel="stylesheet" href="src/css/style.css"/>

</head>
<body>

<div id="header-div" class="container-fluid bg-dark w-100">
    <span class="span-brand">Santoro Airlines</span>
</div>
<div id="content-div" class="container-fluid w-100">
    <div class="row">
        <div class="col">
            <h1>Mappa<i class="fas fa-plane-departure right"></i></h1>
        </div>
    </div>
    <div class="row">
        <nav id="navigation-nav" class="nav flex-column col-2 col-md-2 col-sm-2">

            <li class="nav-item">
                <a class="nav-link" href="home.php"><i class="fa fa-plane"></i>Aggiorna mappa</a>
            </li>


            <?php

            if (isset($_SESSION['user']) && !$timeout) {


                echo "<li class=\"nav-item\">";
                echo "<p id='welcome-msg'>Bentornato ".$_SESSION['user']."</p>";
                echo "<form id='logout-form' action='home.php' method='POST'>";
                echo "<button id='logout-button' type='submit' name='action' value='logout' class=\"btn btn-danger\"><i class='fas fa-sign-out-alt'></i>Logout</button>";
                echo "</form>";
                echo "</li>";




            } else {

                echo "<li class=\"nav-item\">";
                echo "<a class=\"nav-link\" href=\"login.php\"><i class=\"fas fa-sign-in-alt\"></i>Login</a>";
                echo "</li>";
                echo "<li class=\"nav-item\">";
                echo "<a class=\"nav-link\" href=\"registration.php\"><i class=\"fas fa-user-plus\"></i>Registrazione</a>";
                echo "</li>";
            }


            ?>




            <ul id="seat-counter-ul" class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Posti totali
                    <span id="total-seats-label" class="badge badge-primary badge-pill">0</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Posti liberi
                    <span id="free-seats-label" class="badge badge-primary badge-pill">0</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Posti prenotati
                    <span id="booked-seats-label" class="badge badge-primary badge-pill">0</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Posti acquistati
                    <span id="bought-seats-label" class="badge badge-primary badge-pill">0</span>
                </li>
            </ul>

            <p id="response-msg">
                <!-- msg to be injected -->

                <?php

                if ($timeout) {
                    echo 'Sessione scaduta';
                } else if (!empty($_POST)) {
                    if (isset($_POST['action'])) {
                        $action = $_POST['action'];

                        if (isset($_SESSION['user']) && $action === 'logout') {
                            $msg = logout();

                            switch ($msg) {
                                case LOGOUT_SUCCESS:
                                    echo 'Logout riuscito';
                                    break;

                                case LOGOUT_FAILED:
                                    echo 'Logout fallito';
                                    break;

                                case LOGOUT_ERROR:
                                    echo 'Errore logout';
                                    break;

                                default:
                                    break;

                            }
                        }
                    }
                }

                ?>

            </p>
        </nav>

        <div id="main-div" class="col-10 col-md-10 col-sm-10 p-3">
            <noscript>
                <p>Per il corretto funzionamento del sito è necessario abilitare javascript</p>
            </noscript>
            <!-- map to be injected -->
        </div>
    </div>
</div>


<!--<div id="main-div">

    <noscript>
        <p>Per il corretto funzionamento del sito è necessario abilitare javascript</p>
    </noscript>

</div>

<div id="div-msg">
    <p id="p-msg">
        <?php
/*
        if ($timeout) {
            echo 'Sessione scaduta';
        } else if (!empty($_POST)) {
            if (isset($_POST['action'])) {
                $action = $_POST['action'];

                if (isset($_SESSION['user']) && $action === 'logout') {
                    $msg = logout();

                    switch ($msg) {
                        case LOGOUT_SUCCESS:
                            echo 'Logout riuscito';
                            break;

                        case LOGOUT_FAILED:
                            echo 'Logout fallito';
                            break;

                        case LOGOUT_ERROR:
                            echo 'Errore logout';
                            break;

                        default:
                            break;

                    }
                }
            }
        }

        */?>
    </p>
</div>

<div id="navigation-div">
    <form id="refresh-form" action="home.php">
        <button id="map-button">Aggiorna mappa</button>
    </form>

    <?php
/*
    if (isset($_SESSION['user']) && !$timeout) {

        echo "<form id='logout-form' action='home.php' method='POST'>";
        echo "<button id='logout-button' type='submit' name='action' value='logout'>Logout</button>";
        echo "</form>";

        echo "<p id='welcome-msg'>Bentornato ".$_SESSION['user']."</p>";


    } else {
        echo "<form action='login.php'>";
        echo "<button id='login-button'>Login</button>";
        echo "</form>";

        echo "<form action='registration.php'>";
        echo "<button id='register-button'>Registrati</button>";
        echo "</form>";
    }


    */?>
</div>-->
</body>
</html>