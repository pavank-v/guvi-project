$(document).ready(function() {
    // Check if user is already logged in
    if (localStorage.getItem('userToken')) {
        window.location.href = 'profile.html';
        return;
    }

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: JSON.stringify({
                email: email,
                password: password
            }),
            contentType: 'application/json',
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    localStorage.setItem('userToken', data.token);
                    localStorage.setItem('userId', data.userId);
                    window.location.href = 'profile.html';
                } else {
                    alert(data.message || 'Login failed!');
                }
            },
            error: function() {
                alert('An error occurred during login.');
            }
        });
    });
});
