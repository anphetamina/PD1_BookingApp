class UserController {
    constructor(model, view) {
        this.model = model;
        this.view = view;
    }

    init() {
        let _this = this;
        this.model.updateUser(function (data) {
            let authenticated = data[1];
            if (authenticated) {
                _this.view.printLogoutButton();
            } else {
                _this.view.printLoginButton(function () {
                    _this.injectLoginForm(function () {
                        _this.login(_this.view.getLoginUsername(), _this.view.getLoginPassword());
                    });
                });
                _this.view.printRegisterButton();
            }
        });
    }

    injectLoginForm(callback) {
        this.view.printLoginForm(callback);
    }

    login(username, password) {
        let _this = this;
        this.model.updateUserState(username, password, 'login', function (data) {
            console.log(data);
            _this.view.printLogoutButton();
        });
    }

    signUp(username, password) {
        let _this = this;
        this.model.updateUserState(username, password, 'register', function (data) {
            _this.view.printLoginButton();
            _this.view.printRegisterButton();
        });
    }

    logout() {
        let _this = this;
        this.model.updateUserState('', '', 'logout', function (data) {
            _this.view.printLoginButton();
            _this.view.printRegisterButton();

        });
    }
}