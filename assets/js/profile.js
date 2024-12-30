$(document).ready(function() {
    // Check if user is logged in
    const token = localStorage.getItem('userToken');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    loadProfile();

    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    // Handle logout
    $('#logoutBtn').on('click', function() {
        logout();
    });
});

function loadProfile() {
    const token = localStorage.getItem('userToken');
    const userId = localStorage.getItem('userId');

    $.ajax({
        url: 'php/profile.php',
        type: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`
        },
        data: { userId: userId },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                $('#username').val(data.profile.username);
                $('#email').val(data.profile.email);
                $('#age').val(data.profile.age);
                $('#dob').val(data.profile.dob);
                $('#contact').val(data.profile.contact);
            } else {
                alert('Failed to load profile');
                logout();
            }
        },
        error: function() {
            alert('An error occurred while loading profile');
            logout();
        }
    });
}

function updateProfile() {
    const token = localStorage.getItem('userToken');
    const userId = localStorage.getItem('userId');

    const profileData = {
        userId: userId,
        age: $('#age').val(),
        dob: $('#dob').val(),
        contact: $('#contact').val()
    };

    $.ajax({
        url: 'php/profile.php',
        type: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`
        },
        data: JSON.stringify(profileData),
        contentType: 'application/json',
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                alert('Profile updated successfully!');
                $('#profileForm').addClass('form-submitted');
                setTimeout(() => {
                    $('#profileForm').removeClass('form-submitted');
                }, 500);
            } else {
                alert(data.message || 'Failed to update profile');
            }
        },
        error: function() {
            alert('An error occurred while updating profile');
        }
    });
}

function logout() {
    const token = localStorage.getItem('userToken');
    
    $.ajax({
        url: 'php/logout.php',
        type: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`
        },
        success: function() {
            localStorage.removeItem('userToken');
            localStorage.removeItem('userId');
            window.location.href = 'login.html';
        },
        error: function() {
            localStorage.removeItem('userToken');
            localStorage.removeItem('userId');
            window.location.href = 'login.html';
        }
    });
}