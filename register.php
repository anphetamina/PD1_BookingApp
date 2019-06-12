<?php
session_start();

include "src/php/signup.php";

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'register' && !isset($_SESSION['user'])) {
            if (isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])) {
                $username = $_POST['username'];
                $password1 = $_POST['password1'];
                $password2 = $_POST['password2'];

                $response = register($username, $password1, $password2);

                redirect('register.php?msg=' . $response);
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
            loadRegisterForm();

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


    <!-- Register form to be injected -->



</div>

<div id="div-msg">
    <p id="p-msg">
        <?php

        if (!empty($_GET)) {
            if (isset($_GET['msg']) && !isset($_SESSION['user']) && isset($response)) {
                $msg = $_GET['msg'];
                switch ($msg) {
                    case REGISTRATION_SUCCESS:
                        if($response === REGISTRATION_SUCCESS) echo 'Registrazione avvenuta con successo';
                        break;

                    case REGISTRATION_FAILED:
                        if($response === REGISTRATION_FAILED) echo 'Registrazione fallita';
                        break;

                    case USERNAME_ALREADY_EXISTS:
                        if($response === USERNAME_ALREADY_EXISTS) echo 'Username già esistente';
                        break;

                    case USERNAME_NOT_VALID:
                        if($response === USERNAME_NOT_VALID) echo 'Username non valido';
                        break;

                    case PASSWORD_NOT_VALID:
                        if($response === PASSWORD_NOT_VALID) echo 'Password non valida';
                        break;

                    case PASSWORD_NOT_EQUAL:
                        if($response === PASSWORD_NOT_EQUAL) echo 'Le password non coincidono';
                        break;

                    case PASSWORD_NULL:
                        if($response === PASSWORD_NULL) echo 'Password sbagliata';
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

    <form action='login.php'>
        <button id='login-button'>Login</button>
    </form>


</div>


<footer>
footer
</footer>
</body>
</html>