class MapModel {

    constructor() {
        /*this.N = 0;
        this.M = 0;
        this.seats = new Object();
        this.free_seats = 0;
        this.booked_seats = 0;
        this.bought_seats = 0;
        this.remote_seats = [];*/
        this.selected_seats = [];
    }

    init(callback) {
        // let _this = this;
        $.when(
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'initDB'},
            }),
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'getDims'},
            }),
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'getSeats'},
            }),
        ).done(function (res1, res2, res3) {
            if (res1[2]['readyState'] === 4) {
                if (res1[2]['status'] === 200 || res1[2]['status'] === 0) {
                    let dims = JSON.parse(res2[0]);
                    let seats = JSON.parse(res3[0]);
                    console.log(dims);
                    console.log(seats);
                    callback({N: dims[0], M: dims[1], seats: seats});
                }
            } else {
                console.log('DB ERROR');
            }
        });


    }


    updateSeat(id, callback) {
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
