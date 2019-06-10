

function testCookie() {
    let cookieEnabled = (navigator.cookieEnabled);

    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
        document.cookie="test_cookie";
        cookieEnabled = (document.cookie.indexOf("test_cookie") !== -1);
    }

    return (cookieEnabled);
}

let map_controller = undefined;
let user_controller = undefined;

$(document).ready(function () {

    if (testCookie()) {
        let map_model = new MapModel();
        let map_view = new MapView();
        map_controller = new MapController(map_model, map_view);
        map_controller.init();
        let user_model = new UserModel();
        let user_view = new UserView();
        user_controller = new UserController(user_model, user_view);
        user_controller.init();

    } else {
        // print msg no cookie
    }
});