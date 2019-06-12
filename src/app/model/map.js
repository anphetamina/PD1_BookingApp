class MapModel {

    constructor() {
    }

    init(callback) {
        $.when(
            $.ajax({
                url: 'src/php/data.php',
                type: 'GET',
                data: {action: 'initDB'},
            }),
            $.ajax({
                url: 'src/php/data.php',
                type: 'GET',
                data: {action: 'getDims'},
            }),
            $.ajax({
                url: 'src/php/data.php',
                type: 'GET',
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


    updateSeat(seat, callback) {
        let id = seat['id'];
        let current_state = seat['current_state'];
        $.ajax({
            url: 'src/php/booking.php',
            type: 'POST',
            data: {action: 'updateSeat', id: id},
            success: function (result) {
                switch (result) {
                    case 'timeOut':
                        // todo print timeout msg
                        break;

                    case 'notAuthenticated':
                        callback(current_state, result);
                        break;

                    default:
                        // todo callback
                        break;
                }
            },
            error: undefined
        });
    }


}
