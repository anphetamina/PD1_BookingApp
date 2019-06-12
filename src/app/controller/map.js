class MapController {

    constructor(model, view) {
        this.model = model;
        this.view = view;
    }

    init() {
        let _this = this;
        this.model.init(function (data) {
            _this.view.printMap(data, function (event) {
                _this.selectSeat(_this.view.getSeat(event.target));
            });
        });
    }


    selectSeat(seat) {
        let _this = this;
        let id = seat['id'];
        this.model.updateSeat(seat, function (new_state, msg) {
            _this.view.refreshCell(id, new_state);
            _this.view.refreshMsg(msg);
        });
    }

    bookSeats(data) {

    }


}

