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
            success: function (new_state) {
                switch (new_state) {
                    case 'timeOut':
                        $("button").prop("disabled", true);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        callback(current_state, "Sessione scaduta");
                        break;

                    case 'notAuthenticated':
                        callback(current_state, "Per effettuare una prenotazione autenticarsi al sito");
                        break;

                    case 'dbError':
                        callback(current_state, "Errore database");
                        break;

                    default:
                        if (new_state === 'bought') {
                            callback(new_state, "Posto " + id + " già acquistato");
                        } else if (new_state === 'free') {
                            if (current_state === 'selected') callback(new_state, "Posto " + id + " liberato");
                            else callback(new_state, "Posto " + id + " nuovamente liberato");
                        } else if (new_state === 'booked') {
                            if (current_state === 'selected') callback(new_state, "Prenotazione per il posto " + id + " cancellata");
                            else callback(new_state, "Posto " + id + " già prenotato");
                        } else if (new_state === 'selected') {
                            if (current_state === 'free') callback(new_state, "Posto " + id + " prenotato");
                            else callback(new_state, "Prenotazione riconfermata");
                        }

                        break;
                }
            }
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

                    case 'dbError':
                        callback('Errore database');
                        break;

                    default:
                        let not_purchased_seats = JSON.parse(result);
                        if (not_purchased_seats.length !== 0) {
                            let msg = 'Non è stato possibile acquistare i posti';
                            for (let i = 0; i < not_purchased_seats.length; i++) {
                                msg += ' '+not_purchased_seats[i];
                            }
                            $("button").prop("disabled", true);
                            callback(msg);
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            $("button").prop("disabled", true);
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            callback('Acquisto confermato');
                        }
                        break;

                }

            }
        });
    }



}
