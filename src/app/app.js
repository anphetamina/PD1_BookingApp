function testCookie() {
    let cookieEnabled = (navigator.cookieEnabled);

    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
        document.cookie="test_cookie";
        cookieEnabled = (document.cookie.indexOf("test_cookie") !== -1);
    }

    return cookieEnabled;
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
    } else {
        // todo
    }

}

function loadRegisterForm() {
    if (testCookie()) {
        $("#main-div").load("src/app/template/register_form.html");
    } else {
        // todo
    }
}