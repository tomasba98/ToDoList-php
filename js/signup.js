$(document).ready(function () {
    document.getElementById('signupForm').addEventListener('submit', function (event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        let isValid = true;

        // Reset any previous error messages
        document.getElementById('usernameError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('confirmPasswordError').textContent = '';

        // Validate username
        if (username.trim() === '') {
            document.getElementById('usernameError').textContent = 'Username is required.';
            isValid = false;
        }

        // Validate password (you can add more checks as needed)
        if (password.trim() === '') {
            document.getElementById('passwordError').textContent = 'Password is required.';
            isValid = false;
        }

        // Confirm password validation
        if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        } else {
            $.notify("Registration successful", "success");
        }
    });
});
