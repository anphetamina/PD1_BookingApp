class UserController {
    constructor(model, view) {
        this.model = model;
        this.view = view;
    }

    init() {
        let _this = this;
        this.model.updateUserAuthentication(function (data) {
            console.log(data);
            _this.view.printForm(data);
        });
    }

    checkAuthentication() {

    }
}