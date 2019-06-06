class MapModel {


    constructor() {
        this.N = 0;
        this.M = 0;
        this.seats = [];
        this.remote_seats = [];
    }

    init(callback) {
        let _this = this;
        $.when(
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'getDims'},
                success: function (result) {
                    let dims = JSON.parse(result);
                    _this.N = dims[0];
                    _this.M = dims[1];
                }
            }),
            $.ajax({
                url: 'src/php/data.php',
                type: 'POST',
                data: {action: 'getSeats'},
                success: function (result) {
                    let seats = JSON.parse(result);
                    _this.remote_seats = seats;
                }
            })
        ).done(function (res1, res2) {
            if(res1 && res2) console.log('done');
            console.log(_this.remote_seats);
            _this.initMap(callback);
        });


    }

    initMap(callback) {
        let index = 0;
        for (let i = 0; i < this.N; i++) {
            for (let j = 0; j < this.M; j++) {
                let x = (i + 1).toString();
                let y = String.fromCharCode("A".charCodeAt(0) + j);
                let id = x + y;
                this.seats[id] = {id: id, state: this.remote_seats[index].state, user: this.remote_seats[index].user};
                index++;
            }
        }
        console.log(this.N);
        console.log(this.M);
        console.log(this.seats);
        callback(this.N, this.M, this.seats);
    }
}
