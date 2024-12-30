// register.php
<?php
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

try {
    // MySQL connection
    $mysql = new PDO(
        "mysql:host=localhost;dbname=user_system",
        "root",
        ""
    );
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if email already exists
    $stmt = $mysql->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert user into MySQL
    $stmt = $mysql->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$data['username'], $data['email'], $hashedPassword]);
    $userId = $mysql->lastInsertId();

    // MongoDB connection for profile
    $mongodb = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongodb->user_profiles->profiles;

    // Create initial profile in MongoDB
    $collection->insertOne([
        'user_id' => $userId,
        'email' => $data['email'],
        'username' => $data['username'],
        'age' => null,
        'dob' => null,
        'contact' => null,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}
