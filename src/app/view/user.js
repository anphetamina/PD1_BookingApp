class UserView {
    constructor() {
        this.navigation_div = document.getElementById("navigation-div");

        this.login_button = document.createElement('button');
        this.login_button.setAttribute('id', 'login-button');
        this.login_button.innerHTML = 'Login';

        this.logout_button = document.createElement('button');
        this.logout_button.setAttribute('id', 'logout-button');
        this.logout_button.innerHTML = 'Logout';

        this.register_button = document.createElement('button');
        this.register_button.setAttribute('id', 'register-button');
        this.register_button.innerHTML = 'Registrazione';
    }

    printLoginButton() {
        this.navigation_div.appendChild(this.login_button);
    }

    printRegisterButton() {
        this.navigation_div.appendChild(this.register_button);
    }

    printLogoutButton() {
        this.navigation_div.appendChild(this.logout_button);
    }

    printRegisterForm() {
        $("#main-div").load('src/app/template/register_form.html');
    }

    printLoginForm() {
        $("#main-div").load('src/app/template/login_form.html');
    }

}