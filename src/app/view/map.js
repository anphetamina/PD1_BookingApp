class MapView {
    constructor() {
        this.total_seats_count = 0;
        this.free_seats_count = 0;
        this.booked_seats_count = 0;
        this.bought_seats_count = 0;

    }

    printButton() {

    }

    printMap(data, listener) {
        let _this = this;

        $("#main-div").load('src/app/template/map.html', function () {
            _this.printCells(data, listener);
        });
    }

    printCells(data, listener) {
        this.table = document.getElementById("map-table");

        console.log(data);

        let N = data['N'];
        let M = data['M'];
        let seats = data['seats'];

        let index = 0;
        for (let i = 0; i < N; i++) {
            let row = this.table.insertRow(i);
            for (let j = 0; j < M; j++) {
                let cell = row.insertCell(j);
                let id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
                cell.innerHTML = id;
                cell.addEventListener('click', listener);
                let state = seats[index++].state;

                switch (state) {
                    case 'free':
                        this.free_seats_count++;
                        break;
                    case 'booked':
                        this.booked_seats_count++;
                        break;
                    case 'taken':
                        this.booked_seats_count++;
                        break;
                    case 'bought':
                        this.bought_seats_count++;
                        break;
                    default:
                        undefined;
                }

                cell.setAttribute('id', id);
                cell.setAttribute('class', state);
            }
        }

        this.total_seats_count = seats.length;

        document.getElementById('total-seats-label').innerHTML = 'Posti totali: '+this.total_seats_count;
        document.getElementById('free-seats-label').innerHTML = 'Posti liberi: '+this.free_seats_count;
        document.getElementById('booked-seats-label').innerHTML = 'Posti prenotati: '+this.booked_seats_count;
        document.getElementById('bought-seats-label').appendChild(document.createTextNode(this.bought_seats_count));
    }

    refreshCell(id, state) {
        let cell = document.getElementById(id);
        cell.setAttribute('class', state);
    }

    refreshCounter() {

    }


    getSeatId(cell) {
        return cell.getAttribute('id');
    }
}