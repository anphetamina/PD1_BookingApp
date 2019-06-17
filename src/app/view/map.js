function MapView() {
    MapView.prototype.printMap = function (data, listener) {
        var _this = this;

        $("#main-div").load('src/app/template/map.html', function () {
            _this.printCells(data, listener);
        });
    };

    MapView.prototype.printCells = function (data, listener) {
        this.table = document.getElementById("map-table");

        var N = data['N'];
        var M = data['M'];
        var seats = data['seats'];
        var user = data['user'];
        var total_seats_count = N*M;
        var free_seats_count = 0;
        var booked_seats_count = 0;
        var bought_seats_count = 0;

        for (var i = 0; i < N; i++) {
            var row = this.table.insertRow(i);

            for (var j = 0; j < M; j++) {
                var cell = row.insertCell(j);
                var button = document.createElement('button');


                var id = String.fromCharCode("A".charCodeAt(0)+j)+(i+1).toString();
                button.innerHTML = id;
                button.addEventListener('click', listener);
                button.setAttribute('id', id);

                var state = 'free';
                /*
                * the seat has been booked or bought
                * */
                if (seats[id] !== undefined) {
                    state = seats[id].state;

                    if (state === 'booked' && seats[id].user === user) state = 'selected';
                }

                switch (state) {
                    case 'free':
                        free_seats_count++;
                        break;

                    case 'booked':
                        booked_seats_count++;
                        break;

                    case 'bought':
                        bought_seats_count++;
                        break;

                    case 'selected':
                        booked_seats_count++;
                        break;

                    default:

                        break;

                }

                button.setAttribute('class', state);
                button.setAttribute('type', 'button');

                cell.appendChild(button);
                document.getElementById("total-seats-label").innerHTML = total_seats_count;
                document.getElementById("free-seats-label").innerHTML = free_seats_count;
                document.getElementById("booked-seats-label").innerHTML = booked_seats_count;
                document.getElementById("bought-seats-label").innerHTML = bought_seats_count;
            }
        }
    };

    MapView.prototype.printBookingButton = function (listener) {
        var navigationDiv = document.getElementById("navigation-nav");
        var bookingButton = document.createElement('button');
        bookingButton.setAttribute('id', 'book-button');
        bookingButton.setAttribute('name', 'action');
        bookingButton.setAttribute('value', 'bookSeats');
        bookingButton.setAttribute('type', 'submit');
        bookingButton.setAttribute('class', 'btn btn-success');
        navigationDiv.appendChild(bookingButton);

        bookingButton.innerHTML = "Acquista posti";


        $("#book-button").on("click", listener);
    };

    MapView.prototype.refreshCell = function (id, state) {
        var cell = document.getElementById(id);
        var old_state = cell.getAttribute('class');
        cell.setAttribute('class', state);

        if (old_state !== state) {
            if (old_state === 'selected') old_state = 'booked';
            if (state === 'selected') state = 'booked';
            this.decrementCounter(old_state);
            this.incrementCounter(state);
        }
    };

    MapView.prototype.refreshMsg = function (msg) {
        document.getElementById('response-msg').innerHTML = msg;
    };

    MapView.prototype.getSelectedSeats = function () {
        return document.getElementsByClassName('selected');
    };

    MapView.prototype.getSeat = function (cell) {
        return {id: cell.getAttribute('id'), current_state: cell.getAttribute('class')};
    };

    MapView.prototype.incrementCounter = function (class_name) {
        var counter = parseInt(document.getElementById(class_name+"-seats-label").innerHTML);
        document.getElementById(class_name+"-seats-label").innerHTML = counter+1;
    };

    MapView.prototype.decrementCounter = function (class_name) {
        var counter = parseInt(document.getElementById(class_name+"-seats-label").innerHTML);
        document.getElementById(class_name+"-seats-label").innerHTML = counter-1;
    };


}