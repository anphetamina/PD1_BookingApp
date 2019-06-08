$(document).ready(function () {
    /*let map_model = new MapModel();
    let map_view = new MapView();
    let map_controller = new MapController(map_model, map_view);
    map_controller.init();*/

    let user_model = new UserModel();
    let user_view = new UserView();
    let user_controller = new UserController(user_model, user_view);
    user_controller.init();
});