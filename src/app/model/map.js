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


    updateSeat(id, callback) {
        $.ajax({
            url: 'src/php/data.php',
            type: 'GET',
            data: {action: 'getSeatState', id: id},
            success: function (result) {
                let new_state = result;
                callback(new_state);
            },
            error: undefined
        });
    }


}
