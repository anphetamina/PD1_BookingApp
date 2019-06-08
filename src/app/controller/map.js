class MapController {

    constructor(model, view) {
        this.model = model;
        this.view = view;
    }

    init() {
        let _this = this;
        this.model.init(function (data) {
            _this.view.printMap(data);
            _this.view.addSelectListener(function (event) {
                _this.selectSeat(_this.view.getSeatId(event.target));
            });
        });
    }


    selectSeat(id) {
        let _this = this;
        this.model.updateSeat(id, function (new_state) {
            _this.view.refreshCell(id, new_state);
        });
    }

    bookSeats(data) {

    }


}

