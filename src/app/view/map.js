class MapView {
    constructor() {

    }

    printMap(N, M, seats) {
        this.table = document.createElement('table');
        this.parent = document.getElementById('mainDiv');
        this.parent.appendChild(this.table);

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
}