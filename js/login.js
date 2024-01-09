$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    let notification = null;
    
    for (const [key, value] of urlParams.entries()) {
        if (value === "Registration successful") {
            notification = value;
            break;
        }
    }

    if (notification) {
        $.notify(notification, "success"); 
    }

    document.getElementById('loginForm').addEventListener('submit', function (event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        let isValid = true;

        document.getElementById('usernameError').textContent = '';
        document.getElementById('passwordError').textContent = '';

        if (username.trim() === '') {
            document.getElementById('usernameError').textContent = 'Username is required.';
            isValid = false;
        }

        if (password.trim() === '') {
            document.getElementById('passwordError').textContent = 'Password is required.';
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
