class MapModel {

    constructor() {}

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
            $.ajax({
                url: 'src/php/data.php',
                type: 'GET',
                data: {action: 'getUser'},
            }),
        ).done(function (res1, res2, res3, res4) {
            let dims = JSON.parse(res2[0]);
            let seats = JSON.parse(res3[0]);
            let user = res4[0];
            callback({N: dims[0], M: dims[1], seats: seats, user: user});
        });


    }


    updateSeat(seat, callback) {
        let id = seat['id'];
        let current_state = seat['current_state'];
        $.ajax({
            url: 'src/php/booking.php',
            type: 'POST',
            data: {action: 'updateSeat', id: id, current_state: current_state},
            success: function (result) {
                switch (result) {
                    case 'timeOut':
                        callback(current_state, "Sessione scaduta");
                        $("form :input").prop("disabled", true);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;

                    case 'notAuthenticated':
                        callback(current_state, "Per effettuare una prenotazione autenticarsi al sito");
                        break;

                    default:
                        callback(result, "");
                        break;
                }
            },
            error: undefined
        });
    }

    updateSeats(seats, callback) {
        let selected_seats = {};
        for (let i = 0; i < seats.length; i++) {
            selected_seats[i] = seats[i].id;
        }
        let json_seats = JSON.stringify(selected_seats);
        $.ajax({
            url: 'src/php/booking.php',
            type: 'POST',
            data: {action: 'buySeats', selected_seats: json_seats},
            success: function (result) {

                switch (result) {
                    case 'timeOut':
                        callback('Sessione scaduta');
                        break;

                    default:
                        let not_purchased_seats = JSON.parse(result);
                        if (not_purchased_seats.length !== 0) {
                            let msg = 'Non è stato possibile acquistare i posti ';
                            for (let i = 0; i < not_purchased_seats.length; i++) {
                                msg.concat(not_purchased_seats[i]);
                            }
                        } else {
                            $("form :input").prop("disabled", true);
                            $("button").prop("disabled", true);
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            callback('Acquisto confermato');
                        }
                        break;

                }

            },
            error: undefined
        });
    }



}
