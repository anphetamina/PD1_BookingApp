<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>title</title>
    <script src="lib/jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#book-button").on("click", function () {
                $("#refresh-form").trigger('submit');

            });

        });
    </script>

</head>
<body>
<header>
    header
</header>


<div id="main-div">

</div>

</div>

<div id="navigation-div">
    <form id="refresh-form" action="test.php">
        <button id="map-button">Refresh</button>
    </form>

    <button id="book-button" type="submit">Buy</button>

</div>


<footer>
    footer
</footer>
</body>
</html>