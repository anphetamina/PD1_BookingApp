class MapModel {

    constructor() {
        this.N = 0;
        this.M = 0;
        this.seats = new Object();
        this.selected_seats = [];
        this.free_seats = 0;
        this.booked_seats = 0;
        this.bought_seats = 0;
        this.remote_seats = [];
    }

    init(callback) {
        let _this = this;
        $.when(
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'getDims'},
                success: function (result) {
                    let dims = JSON.parse(result);
                    _this.N = dims[0];
                    _this.M = dims[1];
                }
            }),
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'getSeats'},
                success: function (result) {
                    let seats = JSON.parse(result);
                    _this.remote_seats = seats;
                }
            })
        ).done(function (res1, res2) {
            if(res1 && res2) console.log('done');
            console.log(_this.remote_seats);
            _this.initMap(callback);
        });


    }

    initMap(callback) {
        let index = 0;
        for (let i = 0; i < this.N; i++) {
            for (let j = 0; j < this.M; j++) {
                let x = (i + 1).toString();
                let y = String.fromCharCode("A".charCodeAt(0) + j);
                let id = x + y;
                let state = this.remote_seats[index].state;
                let user = this.remote_seats[index].user;
                switch (state) {
                    case 'free':
                        this.free_seats++;
                        break;
                    case 'booked':
                        this.booked_seats++;
                        break;
                    case 'bought':
                        this.bought_seats++;
                        break;
                    default:
                        undefined;
                }
                this.seats[id] = {id: id, state: state, user: user};
                index++;
            }
        }
        let data = {N: this.N, M: this.M, seats: this.seats, total_seats: this.N*this.M, free_seats: this.free_seats, booked_seats: this.booked_seats, bought_seats: this.bought_seats};
        callback(data);
    }

    updateSeat(id) {

    }

    refreshSeat(id, callback) {
        let _this = this;
        $.ajax({
            url: 'src/php/data.php',
            type: 'POST',
            data: {action: 'getSeatState', id: id},
            success: function (result) {
                let new_state = result;
                _this.seats[id].state = new_state;
                callback(new_state);
            }
        });
    }


}
