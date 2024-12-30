
// login.php
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

    // Verify user
    $stmt = $mysql->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($data['password'], $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit;
    }

    // Generate token
    $token = bin2hex(random_bytes(32));

    // Store session in Redis
    $redis = new Redis();
    $redis->connect('localhost', 6379);
    $redis->setex("session:$token", 3600, $user['id']); // Expires in 1 hour

    echo json_encode([
        'success' => true,
        'token' => $token,
        'userId' => $user['id']
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Login failed']);
}
