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

        for (var i = 0; i < N; i++) {
            var row = this.table.insertRow(i);
            for (var j = 0; j < M; j++) {
                var cell = row.insertCell(j);
                var button = document.createElement('button');


                var id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
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


                button.setAttribute('class', state);
                button.setAttribute('type', 'button');

                cell.appendChild(button);
            }
        }
    };

    MapView.prototype.printBookingButton = function (listener) {
        var navigationDiv = document.getElementById("navigation-div");
        var bookingButton = document.createElement('button');
        bookingButton.setAttribute('id', 'book-button');
        bookingButton.setAttribute('name', 'action');
        bookingButton.setAttribute('value', 'bookSeats');
        bookingButton.setAttribute('type', 'submit');
        navigationDiv.appendChild(bookingButton);

        bookingButton.innerHTML = "Acquista posti";


        $("#book-button").on("click", listener);
    };

    MapView.prototype.refreshCell = function (id, state) {
        var cell = document.getElementById(id);
        cell.setAttribute('class', state);
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
}