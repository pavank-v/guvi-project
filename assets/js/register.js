$(document).ready(function() {
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        
        const username = $('#username').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();

        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }

        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: JSON.stringify({
                username: username,
                email: email,
                password: password
            }),
            contentType: 'application/json',
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert('Registration successful!');
                    window.location.href = 'login.html';
                } else {
                    alert(data.message || 'Registration failed!');
                }
            },
            error: function() {
                alert('An error occurred during registration.');
            }
        });
    });
});
