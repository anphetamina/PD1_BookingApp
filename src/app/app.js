function testCookie() {
    let cookieEnabled = (navigator.cookieEnabled);

    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
        document.cookie="test_cookie";
        cookieEnabled = (document.cookie.indexOf("test_cookie") !== -1);
    }

    return cookieEnabled;
}

function sanitizeEmail(email) {
    if(email === null || email === undefined || email === "") return false;
    let pattern = /^[a-zA-Z][a-zA-Z0-9_]{0,49}\@[a-zA-Z][a-zA-Z0-9]{0,49}\.[a-z]{1,20}$/;

    let result = email.match(pattern);
    if (result === null) return false;

    let matched_string = result[0];
    if (email !== matched_string) return false;

    return true;
}

function sanitizePassword(password) {
    if(password === null || password === undefined || password === "") return false;
    let pattern = /^[^\`\¬\`\¦\!\"\£\$\%\^\&\*\(\)\_\-\+\=\[\]\{\}\:\;\@\'\#\~\?\/\.\>\<\,\\\|\€\n\r\t]{1,100}$/;

    let result = password.match(pattern);
    if (result === null) return false;

    let matched_string = result[0];
    if (password !== matched_string) return false;

    return true;
}

function loadMap() {
    if (testCookie()) {
        let map_model = new MapModel();
        let map_view = new MapView();
        let map_controller = new MapController(map_model, map_view);
        map_controller.init();

    } else {
        // todo print msg no cookie
    }
}

function loadLoginForm() {
    if (testCookie()) {
        $("#main-div").load("src/app/template/login_form.html");
        $("#login-form").submit(function (event) {
            let data = $("#register-form :input").serializeArray();
            let username = data[0]['value'];
            let password1 = data[1]['value'];
            let password2 = data[2]['value'];

            if(!sanitizeEmail(username) || !sanitizePassword(password1) || !sanitizePassword(password2) || password1!==password2) {
                event.preventDefault();
                document.getElementById("p-msg").innerHTML = 'Dati inseriti non validi';
            }

        });
    } else {
        // todo
    }

}

function loadRegisterForm() {
    if (testCookie()) {
        $("#main-div").load("src/app/template/register_form.html", function () {
            $("#register-form").submit(function (event) {
                let data = $("#register-form :input").serializeArray();
                let username = data[0]['value'];
                let password1 = data[1]['value'];
                let password2 = data[2]['value'];

                if(!sanitizeEmail(username) || !sanitizePassword(password1) || !sanitizePassword(password2) || password1!==password2) {
                    event.preventDefault();
                    document.getElementById("p-msg").innerHTML = 'Dati inseriti non validi';
                }

            });

        });
    } else {
        // todo
    }
}