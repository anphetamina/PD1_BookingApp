class MapView {
    constructor() {
        this.total_seats_count = 0;
        this.free_seats_count = 0;
        this.booked_seats_count = 0;
        this.bought_seats_count = 0;


        this.total_seats = document.createElement('p');
        this.total_seats.innerHTML = 'Posti totali: ';

        this.free_seats = document.createElement('p');
        this.free_seats.innerHTML = 'Posti liberi: ';

        this.booked_seats = document.createElement('p');
        this.booked_seats.innerHTML = 'Posti prenotati: ';

        this.bought_seats = document.createElement('p');
        this.bought_seats.innerHTML = 'Posti acquistati: ';

        this.table = document.createElement('table');
        this.parent = document.getElementById("main-div");

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

        let index = 0;
        for (let i = 0; i < N; i++) {
            let row = this.table.insertRow(i);
            for (let j = 0; j < M; j++) {
                let cell = row.insertCell(j);
                let id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
                cell.innerHTML = id;
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

        this.total_seats.append(this.total_seats_count);
        this.free_seats.append(this.free_seats_count);
        this.booked_seats.append(this.booked_seats_count);
        this.bought_seats.append(this.bought_seats_count);
    }

    refreshCell(id, state) {
        let cell = document.getElementById(id);
        cell.setAttribute('class', state);
    }

    addSelectListener(listener) {
        let cells = this.table.getElementsByTagName('td');
        for (let i = 0; i < cells.length; i++) {
            cells[i].addEventListener('click', listener);
        }
    }

    getSeatId(cell) {
        return cell.getAttribute('id');
    }
}