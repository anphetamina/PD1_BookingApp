function testCookie() {
    var cookieEnabled = (navigator.cookieEnabled);

    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
        document.cookie="test_cookie";
        cookieEnabled = (document.cookie.indexOf("test_cookie") !== -1);
    }

    return cookieEnabled;
}

function sanitizeEmail(email) {
    if(email === null || email === undefined || email === "") return false;
    var pattern = /^[a-zA-Z]{1,20}[a-zA-Z0-9_\.\-]{0,20}\@[a-zA-Z0-9]{1,10}[a-zA-Z0-9]{0,10}\.[a-zA-Z]{2,6}$/;

    var result = email.match(pattern);
    if (result === null) return false;

    var matched_string = result[0];
    if (email !== matched_string) return false;

    return true;
}

function sanitizePassword(password) {
    if(password === null || password === undefined || password === "") return false;
    if(password.length < 2 || password.length > 100) return false;
    var pattern = /^[a-z]+[A-Z0-9]+[a-zA-Z0-9]*|[A-Z0-9]+[a-z]+[a-zA-Z0-9]*$/;

    var result = password.match(pattern);
    if (result === null) return false;

    var matched_string = result[0];
    if (password !== matched_string) return false;

    return true;
}

function loadMap() {
    if (testCookie()) {
        var map_model = new MapModel();
        var map_view = new MapView();
        var map_controller = new MapController(map_model, map_view);
        map_controller.init();

    } else {
        document.getElementById("p-msg").innerHTML = 'Per visualizzare la mappa dei posti occorre abilitare i cookie';
    }
}

function loadLoginForm() {
    if (testCookie()) {
        $("#main-div").load("src/app/template/login_form.html", function (event) {
            $("#login-form").submit(function (event) {
                var data = $("#login-form :input").serializeArray();
                var username = data[0]['value'];
                var password = data[1]['value'];

                if(!sanitizeEmail(username)) {
                    event.preventDefault();
                    document.getElementById("p-msg").innerHTML = 'Email inserita non valida';
                } else if (!sanitizePassword(password)) {
                    event.preventDefault();
                    document.getElementById("p-msg").innerHTML = 'Password inserita non valida';
                }

            });

        });

    } else {
        document.getElementById("p-msg").innerHTML = 'Per effettuare il login occorre abilitare i cookie';
    }

}

function loadRegisterForm() {
    if (testCookie()) {
        $("#main-div").load("src/app/template/register_form.html", function () {
            $("#register-form").submit(function (event) {
                var data = $("#register-form :input").serializeArray();
                var username = data[0]['value'];
                var password1 = data[1]['value'];
                var password2 = data[2]['value'];

                if(!sanitizeEmail(username)) {
                    event.preventDefault();
                    document.getElementById("p-msg").innerHTML = 'Email inserita non valida';
                } else if (!sanitizePassword(password1) || !sanitizePassword(password2)) {
                    event.preventDefault();
                    document.getElementById("p-msg").innerHTML = 'Password inserita non valida';
                } else if (password1 !== password2) {
                    event.preventDefault();
                    document.getElementById("p-msg").innerHTML = 'Le password non coincidono';
                }


            });

        });
    } else {
        document.getElementById("p-msg").innerHTML = 'Per effettuare una registrazione occorre abilitare i cookie';
    }
}
