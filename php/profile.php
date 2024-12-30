
// profile.php
<?php
header('Content-Type: application/json');

// Verify token
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'No token provided']);
    exit;
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

// Check token in Redis
$redis = new Redis();
$redis->connect('localhost', 6379);
$userId = $redis->get("session:$token");

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // MongoDB connection
        $mongodb = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $mongodb->user_profiles->profiles;

        // Get profile from MongoDB
        $profile = $collection->findOne(['user_id' => (int)$userId]);

        if ($profile) {
            echo json_encode([
                'success' => true,
                'profile' => [
                    'username' => $profile->username,
                    'email' => $profile->email,
                    'age' => $profile->age,
                    'dob' => $profile->dob,
                    'contact' => $profile->contact
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Profile not found']);
        }
    } 
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // MongoDB connection
        $mongodb = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $mongodb->user_profiles->profiles;

        // Update profile in MongoDB
        $result = $collection->updateOne(
            ['user_id' => (int)$userId],
            ['$set' => [
                'age' => $data['age'],
                'dob' => $data['dob'],
                'contact' => $data['contact'],
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ]]
        );

        if ($result->getModifiedCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}
