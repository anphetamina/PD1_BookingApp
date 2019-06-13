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

            if (data['user'] !== 'null')
                _this.view.printBookingButton(function (event) {
                    event.preventDefault();
                    _this.bookSeats(_this.view.getSelectedSeats(), event);
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

    bookSeats(selected_seats, event) {
        let _this = this;
        if (selected_seats.length === 0) {
            event.preventDefault();
            _this.view.refreshMsg('Selezionare almeno un posto per effettuare una prenotazione');
        } else _this.view.refreshMsg('Prenotazione in corso');
    }


}

