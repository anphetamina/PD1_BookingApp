function MapController(model, view) {
    this.model = model;
    this.view = view;

    MapController.prototype.init = function () {
        var _this = this;
        this.model.init(function (data) {

            _this.view.printMap(data, function (event) {
                _this.selectSeat(_this.view.getSeat(event.target));
            });

            if (data['user'] !== 'null')
                _this.view.printBookingButton(function () {
                    _this.buySeats(_this.view.getSelectedSeats());
                });

        });
    };
    
    MapController.prototype.selectSeat = function (seat) {
        var _this = this;
        var id = seat['id'];
        this.model.updateSeat(seat, function (new_state, msg) {
            _this.view.refreshCell(id, new_state);
            _this.view.refreshMsg(msg);
        });
    };

    MapController.prototype.buySeats = function (selected_seats) {
        var _this = this;
        if (selected_seats.length === 0) {
            _this.view.refreshMsg('Selezionare almeno un posto per effettuare un acquisto');
        } else {
            _this.model.updateSeats(selected_seats, function (msg) {
                _this.view.refreshMsg(msg);
            });
        }
    };
}

