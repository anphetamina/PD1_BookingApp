class MapController {

    constructor(model, view) {
        this.model = model;
        this.view = view;
        let _this = this;
        this.model.init(function (data) {
            _this.view.printMap(data);
            _this.view.addRefreshListener(function (event) {
                _this.refreshSeat(_this.view.getSeatId(event.target));
            });
        });
    }

    selectSeat(id) {

    }

    refreshSeat(id) {
        let _this = this;
        this.model.refreshSeat(id, function (new_state) {
            _this.view.refreshCell(id, new_state);
        });
    }

    bookSeats(data) {

    }


}

