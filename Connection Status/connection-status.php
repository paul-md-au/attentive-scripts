<?php
// Load environment variables
require_once __DIR__ . '/../config/load-env.php';

header('Content-Type: application/json');

$clientId = getenv('CLIENT_ID');

// Database connection
try {
    $db = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASS'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error'
    ]);
    exit;
}

// Get Attentive connection status for client
try {
    $stmt = $db->prepare("
        SELECT status, updated_at
        FROM attentive_oauth_tokens 
        WHERE client_id = :clientId
    ");
    $stmt->bindParam(':clientId', $clientId);
    $stmt->execute();
    $connection = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Database query failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
    exit;
}

// Build response
if (!$connection) {
    echo json_encode([
        'success' => true,
        'status' => 'disconnected',
        'last_verified' => null,
        'message' => 'Client ID not found'
    ]);
} else {
    $status = ($connection['status'] === 'active') ? 'connected' : 'disconnected';
    
    if ($status === 'connected') {
        echo json_encode([
            'success' => true,
            'status' => 'connected',
            'last_verified' => $connection['updated_at']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'status' => 'disconnected',
            'last_verified' => $connection['updated_at'],
            'message' => 'Status is not active'
        ]);
    }
}
?>