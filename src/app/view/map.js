class MapView {
    constructor() {
        this.total_seats = document.createElement('p');
        this.total_seats.innerHTML = 'Posti totali: ';

        this.free_seats = document.createElement('p');
        this.free_seats.innerHTML = 'Posti liberi: ';

        this.booked_seats = document.createElement('p');
        this.booked_seats.innerHTML = 'Posti prenotati: ';

        this.bought_seats = document.createElement('p');
        this.bought_seats.innerHTML = 'Posti acquistati: ';

        this.table = document.createElement('table');
        this.parent = document.getElementById('map-div');

        this.parent.appendChild(this.table);
        this.parent.appendChild(this.total_seats);
        this.parent.appendChild(this.free_seats);
        this.parent.appendChild(this.booked_seats);
        this.parent.appendChild(this.bought_seats);
    }

    printMap(data) {

        console.log(data);

        let N = data['N'];
        let M = data['M'];
        let seats = data['seats'];
        this.total_seats.append(data['total_seats']);
        this.free_seats.append(data['free_seats']);
        this.booked_seats.append(data['booked_seats']);
        this.bought_seats.append(data['bought_seats']);

        for (let i = 0; i < N; i++) {
            let row = this.table.insertRow(i);
            for (let j = 0; j < M; j++) {
                let cell = row.insertCell(j);
                let id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
                cell.innerHTML = id;
                cell.setAttribute('id', id);
                cell.setAttribute('class', seats[id].state);
            }
        }
    }

    refreshCell(id, state) {
        let cell = document.getElementById(id);
        cell.setAttribute('class', state);
    }

    addRefreshListener(listener) {
        let cells = this.table.getElementsByTagName('td');
        for (let i = 0; i < cells.length; i++) {
            cells[i].addEventListener('click', listener);
        }
    }

    getSeatId(cell) {
        return cell.getAttribute('id');
    }
}