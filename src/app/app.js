$(document).ready(function () {
    let map_model = new MapModel();
    let map_view = new MapView();
    let map_controller = new MapController(map_model, map_view);
});