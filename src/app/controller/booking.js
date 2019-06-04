/*
* BOOKING CONTROLLER
* */

function BookingController() {

    this.map_model;
    this.booking_view;

    BookingController.prototype.loadMap = function() {
        $.ajax({
            url: 'src/php/data.php',
            type: 'POST',
            data: {action: 'getDims'},
            success: function(result) {
                var dimension = JSON.parse(result);
                this.map_model = new MapModel(dimension[0], dimension[1]);
                this.booking_view = new BookingView(dimension[0], dimension[1]);
            }
        })
    }

    BookingController.prototype.refreshMap = function() {
        $.ajax({
            url: 'src/php/data.php',
            type: 'POST',
            data: {action: 'getSeats'},
            success: function(result) {
                var seats = JSON.parse(result);
                for (var i = 0; i < seats.length; i++) {
                    this.map_model.updateSeat(seats['seat_id'], seats['state']);
                    this.booking_view.refreshSeat(seats['seat_id'], seats['state']);
                }
            }
        })
    }
}

