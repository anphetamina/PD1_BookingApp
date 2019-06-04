/*
* BOOKING VIEW
* */

function BookingView(N, M) {
    this.N = N;
    this.M = M;
    this.table = document.createElement('table');
    this.parent = document.getElementById('mainDiv');
    this.parent.appendChild(this.table);

    for (var i = 0; i < this.N; i++) {
        var row = this.table.insertRow(i);
        for (var j = 0; j < this.M; j++) {
            var cell = row.insertCell(j);
            var id = (i+1).toString()+String.fromCharCode("A".charCodeAt(0)+j);
            cell.innerHTML = id;
            cell.setAttribute('id', id);
            cell.setAttribute('class', '');
        }
    }

    BookingView.prototype.addEventListener = function() {

    }

    BookingView.prototype.refreshSeat = function(id, value) {
        $('#id').setAttribute('state', value);
    }
}

/*
* ENTRY POINT
* */
$(document).ready(function () {
    var booking_controller = new BookingController();
    booking_controller.loadMap();
    booking_controller.refreshMap();
});