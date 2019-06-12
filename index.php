<?php
session_start();

include "src/php/common.php";
include "src/php/auth.php";

checkTime();

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'logout' && isset($_SESSION['user']) && isset($_SESSION['timeout'])) {
            $response = logout();

            switch ($response) {
                case LOGOUT_SUCCESS:
                    $_SESSION['response'] = $response;
                    redirect('index.php?msg=' . LOGOUT_SUCCESS); // session already destroyed
                    break;

                case LOGOUT_FAILED:
                    $_SESSION['response'] = $response;
                    redirect('index.php?msg=' . LOGOUT_FAILED);
                    break;

                default:
                    break;
            }
        }


    }
}

define('TIMEOUT', 'timeOut');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>s264014_BookingApp</title>
    <link rel="stylesheet" href="src/css/style.css"/>
    <script src="lib/jquery-3.4.1.min.js"></script>
    <script src="src/app/model/map.js"></script>
    <script src="src/app/controller/map.js"></script>
    <script src="src/app/view/map.js"></script>
    <script src="src/app/app.js"></script>
    <script type="text/javascript">
        $(function () {
            loadMap();

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
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];

                switch ($msg){
                    case TIMEOUT:
                        if (isset($_SESSION['timeout'])) {
                            if ($_SESSION['timeout']){
                                echo 'Sessione scaduta';
                                $_SESSION = array();
                            }
                        };
                        break;

                    case LOGOUT_SUCCESS:
                        if (isset($_SESSION['response'])) {
                            if($_SESSION['response'] === LOGOUT_SUCCESS) echo 'Logout riuscito';

                        }
                        break;

                    case LOGOUT_FAILED:
                        if (isset($_SESSION['response'])) {
                            if($_SESSION['response'] === LOGOUT_FAILED) echo 'Logout non riuscito';

                        }
                        break;

                    default:
                        break;
                }

            }
        }


        ?>
    </p>
</div>

<div id="navigation-div">
    <form action="index.php">
        <button id="map-button">Aggiorna mappa</button>
    </form>

    <?php

    if (isset($_SESSION['user']) && isset($_SESSION['timeout'])) {

        if (!$_SESSION['timeout']) {
            echo "<form id='book-form' action='#'>";
            echo "<button id='book-button' type='submit'>Prenota posti</button>";
            echo "</form>";

            echo "<form id='logout-form' action='index.php' method='POST'>";
            echo "<button id='logout-button' type='submit' name='action' value='logout'>Logout</button>";
            echo "</form>";

            echo "<p id='welcome-msg'>Bentornato ".$_SESSION['user']."</p>";
        }


    } else {
        echo "<form action='login.php'>";
        echo "<button id='login-button'>Login</button>";
        echo "</form>";

        echo "<form action='register.php'>";
        echo "<button id='register-button'>Registrati</button>";
        echo "</form>";
    }


    ?>
</div>


<footer>
    footer
</footer>
</body>
</html>