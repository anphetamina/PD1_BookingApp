<?php
include "src/php/common.php";

global $authenticated;

if ($authenticated) {
    redirect('index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>s264014_BookingApp</title>
    <link rel="stylesheet" href="src/css/style.css"/>
    <script src="lib/jquery-3.4.1.min.js"></script>
    <script src="src/app/unused/user_model.js"></script>
    <script src="src/app/unused/user_controller.js"></script>
    <script src="src/app/unused/user_view.js"></script>
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

</div>

<div id="navigation-div">
    <form action="index.php">
    <button id="map-button">Mappa</button>
    </form>

    <form action="login.php">
        <button id="login-button">Login</button>
    </form>
</div>


<footer>
footer
</footer>
</body>
</html>