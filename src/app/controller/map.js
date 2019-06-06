class MapController {

    constructor(model, view) {
        this.model = model;
        this.view = view;
        let _this = this;
        this.model.init(function (N, M, seats) {
            _this.view.printMap(N, M, seats);
        });
    }
}

