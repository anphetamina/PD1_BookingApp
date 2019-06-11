<?php

include "src/php/signup.php";


if ($authenticated) {
    redirect('index.php');
}

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'register' && !$authenticated) {
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
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                switch ($msg) {
                    case REGISTRATION_SUCCESS:
                        $msg = 'Registrazione avvenuta con successo';
                        break;

                    case REGISTRATION_FAILED:
                        $msg = 'Registrazione fallita';
                        break;

                    case USERNAME_ALREADY_EXISTS:
                        $msg = 'Username già esistente';
                        break;

                    case USERNAME_NOT_VALID:
                        $msg = 'Username non valido';
                        break;

                    case PASSWORD_NOT_VALID:
                        $msg = 'Password non valida';
                        break;

                    case PASSWORD_NOT_EQUAL:
                        $msg = 'Le password non coincidono';
                        break;

                    case PASSWORD_NULL:
                        $msg = 'Password sbagliata';
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