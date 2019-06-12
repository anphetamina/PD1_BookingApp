<?php
session_start();

// todo check cookie

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

        if ($timeout) {
            echo 'Sessione scaduta';
        } else if (!empty($_POST)) {
            if (isset($_POST['action'])) {
                if (isset($_SESSION['user'])) {
                    $msg = logout();

                    switch ($msg) {
                        case LOGOUT_SUCCESS:
                            echo 'Logout riuscito';
                            break;

                        case LOGOUT_FAILED:
                            echo 'Logout fallito';
                            break;

                        case LOGOUT_ERROR:
                            echo 'Errore';
                            break;

                        default:
                            break;

                    }
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

    if (isset($_SESSION['user']) && !$timeout) {

        echo "<form id='book-form' action='index.php' method='POST'>";
        echo "<button id='book-button' type='submit' name='action' value='book'>Prenota posti</button>";
        echo "</form>";

        echo "<form id='logout-form' action='index.php' method='POST'>";
        echo "<button id='logout-button' type='submit' name='action' value='logout'>Logout</button>";
        echo "</form>";

        echo "<p id='welcome-msg'>Bentornato ".$_SESSION['user']."</p>";


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