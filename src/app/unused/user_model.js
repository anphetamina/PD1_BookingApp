class UserModel {
    constructor() {
        this.user = undefined;
        this.authenticated = undefined;
    }

    updateUser(callback) {
        let _this = this;
        $.get('src/php/data.php', {action: 'getUser'}, function (data, status, xhr) {
            if (status === 'success') {
                let user = JSON.parse(data);
                _this.user = user[0];
                _this.authenticated = user[1];
                console.log(user);
                callback(user);
            }
        });
    }

    updateUserState(username, password, action, callback) {

        let _this = this;

        switch (action) {
            case 'login':
                $.ajax({
                    url: 'src/php/auth.php',
                    type: 'POST',
                    data: {action: 'login', username: username, password: password},
                    success: function (result) {
                        _this.user = username;
                        _this.authenticated = true;
                        callback(result);
                    },
                    error: function () {
                        _this.user = undefined;
                        _this.authenticated = false;

                    }
                });
                break;

            case 'logout':
                $.ajax({
                    url: 'src/php/auth.php',
                    type: 'POST',
                    data: {action: 'logout'},
                    success: function (result) {
                        _this.user = undefined;
                        _this.authenticated = false;
                        callback(result);
                    },
                    error: undefined
                });
                break;

            case 'register':
                $.ajax({
                    url: 'src/php/signup.php',
                    type: 'POST',
                    data: {action: 'register', username: username, password: password},
                    success: function (result) {
                        callback(result);
                    },
                    error: undefined
                });
                break;

            default:
                undefined;
                break;

        }

    }
}