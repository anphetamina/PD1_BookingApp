/*
* MAP MODEL
* */

function MapModel(N, M) {
    this.N = N;
    this.M = M;
    this.seats = new Array(this.N * this.M);
    this.selectedSeats = new Array();

    for (var i = 0; i < this.N; i++) {
        for (var j = 0; j < this.M; j++) {
            var x = (i + 1).toString();
            var y = String.fromCharCode("A".charCodeAt(0) + j);
            this.seats[x + y] = new Seat(x, y, '');
        }
    };

    console.log(this.seats);


    MapModel.prototype.updateSeat = function(id, value) {
        this.seats[id].state = value;
        console.log(this.seats);
    };

}
