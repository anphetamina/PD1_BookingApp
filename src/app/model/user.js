class UserModel {
    constructor() {
    }

    updateUserAuthentication(callback) {
        $.get('src/php/data.php', {action: 'getAuth'}, function (data, status, xhr) {
            if (status === 'success') {
                callback(data);
            }
        });
    }
}