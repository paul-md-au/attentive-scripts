<?php
// Load environment variables
require_once __DIR__ . '/../config/load-env.php';

header('Content-Type: application/json');

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON'
    ]);
    exit;
}

// Validate input
$allowedKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
$errors = [];

// Check only allowed keys
foreach ($input as $key => $value) {
    if (!in_array($key, $allowedKeys)) {
        $errors[$key] = 'Invalid UTM parameter';
    }
}

// Check required fields exist and not empty
if (!isset($input['utm_source']) || trim($input['utm_source']) === '') {
    $errors['utm_source'] = 'UTM source is required';
}

if (!isset($input['utm_medium']) || trim($input['utm_medium']) === '') {
    $errors['utm_medium'] = 'UTM medium is required';
}

// Check format and length
foreach ($input as $key => $value) {
    if (strlen($value) > 100) {
        $errors[$key] = 'Maximum 100 characters';
    }
    
    if (!empty($value) && !preg_match('/^[a-zA-Z0-9_\-\s]*$/', $value)) {
        $errors[$key] = 'Invalid characters (use only a-z, 0-9, _, -, space)';
    }
}

// Return validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

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

// Save to database
try {
    $utmJson = json_encode($input);
    $configId = 1;
    
    $stmt = $db->prepare("
        UPDATE global_config 
        SET utm_params = :utmParams, 
            updated_date = NOW() 
        WHERE id = :id
    ");
    
    $stmt->bindParam(':utmParams', $utmJson);
    $stmt->bindParam(':id', $configId, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Configuration not found'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'UTM parameters saved successfully',
        'data' => $input
    ]);
    
} catch (PDOException $e) {
    error_log("Database save failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
?>