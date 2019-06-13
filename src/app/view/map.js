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
                let id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
                cell.innerHTML = id;
                cell.addEventListener('click', listener);
                let state = seats[id].state;
                cell.setAttribute('id', id);
                if (state === 'booked' && seats[id].user === user) state = 'selected';
                cell.setAttribute('class', state);
            }
        }
    }

    printBookingButton(listener) {
        $("#navigation-div").load('src/app/template/booking_form.html', function () { // todo replacement
            $("#book-form").submit(listener);

        })
    }

    refreshCell(id, state) {
        let cell = document.getElementById(id);
        cell.setAttribute('class', state);
    }

    refreshMsg(msg) {
        document.getElementById('response-msg').innerHTML = msg;
    }

    getSelectedSeats() {
        return $(".selected").serializeArray();
    }

    getSeat(cell) {
        return {id: cell.getAttribute('id'), current_state: cell.getAttribute('class')};
    }
}