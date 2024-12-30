
// logout.php
<?php
header('Content-Type: application/json');

// Verify token
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'No token provided']);
    exit;
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

try {
    // Remove session from Redis
    $redis = new Redis();
    $redis->connect('localhost', 6379);
    $redis->del("session:$token");

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Logout failed']);
}