<?php

include "src/php/auth.php";


if ($authenticated) {
    redirect('index.php');
}

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'login' && !$authenticated) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $response = login($username, $password);

                switch ($response) {
                    case LOGIN_SUCCESS:
                        $_SESSION['authenticated'] = true;
                        $_SESSION['user'] = $username;
                        $_SESSION['time'] = time();
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

        if ($action == 'logout' && $authenticated) {
            $response = logout();

            switch ($response) {
                case LOGOUT_SUCCESS:
                    $timeout = false;
                    redirect('login.php?msg=' . LOGOUT_SUCCESS);
                    break;

                case LOGOUT_FAILED:
                    $timeout = true;
                    redirect('login.php?msg=' . LOGOUT_FAILED);
                    break;

                default:
                    break;
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
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];

                switch ($msg) {
                    case LOGIN_SUCCESS:
                        $msg = 'Login effettuato';
                        break;

                    case LOGIN_FAILED:
                        $msg = 'Password errata';
                        break;

                    case LOGIN_ERROR:
                        $msg = 'Login non riuscito';
                        break;

                    case LOGOUT_SUCCESS:
                        $msg = 'Logout riuscito';
                        break;

                    case LOGOUT_FAILED:
                        $msg = 'Logout non riuscito';
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
