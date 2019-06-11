<?php
include "src/php/common.php";

global $authenticated;
global $user;
global $timeout;

define('TIMEOUT', -1);
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
            if (isset($_GET['msg']) && isset($timeout)) {
                if ($_GET['msg'] == -1 && $timeout)
                    echo 'Sessione scaduta';
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
    if ($authenticated) {
        echo "<form id='book-form' action='#'>";
        echo "<button id='book-button' type='submit'>Prenota posti</button>";
        echo "</form>";

        echo "<form id='logout-form' action='src/php/auth.php'>";
        echo "<button id='logout-button' type='submit'>Logout</button>";
        echo "</form>";

        echo "<p id='welcome-msg'>Bentornato ".$user."</p>";
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