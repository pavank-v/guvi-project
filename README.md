# Profile Management System
This project is a simple Profile Management System built with HTML, PHP, MongoDB, Redis, and JavaScript. It allows authenticated users to view and update their profile details such as age, date of birth, and contact information.

## Features
- User Authentication: Secure login/logout functionality using tokens stored in Redis.
- Profile Viewing and Editing: Users can view their username and email and update their age, date of birth, and contact number.
- AJAX Integration: Smooth client-server communication using AJAX for loading and updating profile data without reloading the page.
- Security: Token-based authentication and secure session management.

 
## Technologies Used
### Frontend:

- HTML5, CSS3 (Bootstrap 5.1 for responsive design)
- JavaScript (jQuery for AJAX and DOM manipulation)
  
### Backend:

- PHP for server-side scripting
- MongoDB for profile storage
- Redis for session management

## File Structure
```
project/      # Custom CSS for styling
├── js/
│   └── profile.js        # Frontend JavaScript for profile page
├── php/
│   ├── profile.php       # Backend API for fetching and updating profiles
│   └── logout.php        # Backend API for user logout
├── profile.html          # Profile page UI
└── README.md             # Project documentation
```

## Installation and Setup
### Prerequisites
- A local web server with PHP support (e.g., XAMPP, WAMP, or LAMP).
- MongoDB installed and running locally on port 27017.
- Redis installed and running locally on port 6379.
- A modern browser that supports JavaScript.
  
## Steps
Clone the Repository:

```
git clone https://github.com/your-repo/profile-management-system.git
cd profile-management-system
```

### Setup MongoDB:

Create a database user_profiles.
Create a collection profiles and insert user profile documents in the format:
```
{
  "user_id": 1,
  "username": "john_doe",
  "email": "john@example.com",
  "age": 25,
  "dob": "1998-01-01",
  "contact": "1234567890"
}
```
### Setup Redis:

Run Redis on the default port (6379).
Insert a session token in Redis for testing:
```
redis-cli
set session:<token> 1
```

### Start the Web Server:

- Place the project files in your web server's root directory.
- Access profile.html via your browser.
### Usage
- Open profile.html in your browser.
- Log in using your token (stored in Redis).
- View and update your profile details.
- Logout to end the session.
  
### API Endpoints
#### GET /php/profile.php
- Description: Fetch user profile data.
  
Headers:
Authorization: Bearer <token>
Response:
```
{
  "success": true,
  "profile": {
    "username": "john_doe",
    "email": "john@example.com",
    "age": 25,
    "dob": "1998-01-01",
    "contact": "1234567890"
  }
}
```
#### POST /php/profile.php
- Description: Update user profile data.
Headers:
Authorization: Bearer <token>
Body (JSON):
```
{
  "age": 30,
  "dob": "1993-05-15",
  "contact": "0987654321"
}
Response:
json
Copy code
{
  "success": true
}
```

#### POST /php/logout.php
Description: Logout the user by invalidating the token.

## Future Enhancements
- Add server-side input validation.
- Implement a proper authentication mechanism for login.
- Use HTTPS for secure communication.
- Include pagination for viewing more user details.

## Author
Pavan Kumar
GitHub: @pavank-v
