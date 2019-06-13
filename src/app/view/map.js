class MapView {
    constructor() {}

    printMap(data, listener) {
        let _this = this;

        $("#main-div").load('src/app/template/map.html', function () {
            _this.printCells(data, listener);
        });
    }

    printCells(data, listener) {
        this.table = document.getElementById("map-table");

        let N = data['N'];
        let M = data['M'];
        let seats = data['seats'];
        let user = data['user'];

        for (let i = 0; i < N; i++) {
            let row = this.table.insertRow(i);
            for (let j = 0; j < M; j++) {
                let cell = row.insertCell(j);
                let button = document.createElement('button');
                cell.append(button);
                let id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
                button.innerHTML = id;
                button.addEventListener('click', listener);
                let state = seats[id].state;
                button.setAttribute('id', id);
                /*button.setAttribute('name', id);
                button.setAttribute('value', id);*/
                if (state === 'booked' && seats[id].user === user) state = 'selected';
                button.setAttribute('class', state);
                button.setAttribute('type', 'button');
            }
        }
    }

    printBookingButton(listener) {
        let navigationDiv = document.getElementById("navigation-div");
        let bookingButton = document.createElement('button');
        bookingButton.setAttribute('id', 'book-button');
        bookingButton.setAttribute('name', 'action');
        bookingButton.setAttribute('value', 'bookSeats');
        bookingButton.setAttribute('type', 'submit');
        navigationDiv.append(bookingButton);

        bookingButton.innerHTML = "Acquista posti";


        $("#book-button").on("click", listener);

    }

    refreshCell(id, state) {
        let cell = document.getElementById(id);
        cell.setAttribute('class', state);
    }

    refreshMsg(msg) {
        document.getElementById('response-msg').innerHTML = msg;
    }

    getSelectedSeats() {
        return document.getElementsByClassName('selected');
    }

    getSeat(cell) {
        return {id: cell.getAttribute('id'), current_state: cell.getAttribute('class')};
    }
}