<?php
session_start();
require_once '../../config.php';
require_once '../config/error_handler.php';

// Ensure all responses are JSON
// Headers already set in error handler
error_reporting(E_ALL);

// Prevent any output buffering issues
ob_start();
while (ob_get_level() > 0) {
    ob_end_clean();
}

// Headers already set in error handler

// Check if user is logged in as university
if (!isset($_SESSION['university_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}







// Get and validate POST data
$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (strpos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON format']);
        exit;
    }
} else {
    $data = $_POST;
}

// Validate required fields
if (!isset($data['name']) || !isset($data['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Sanitize input data
$name = trim($data['name']);
$description = trim($data['description']);
$hod_name = isset($data['hod_name']) ? trim($data['hod_name']) : null;

$university_id = $_SESSION['university_id'];
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error']);
    exit;
}

try {
    // Check if department name already exists for this university
    $stmt = $db->prepare('SELECT id FROM departments WHERE name = ? AND university_id = ?');
    $stmt->execute([$data['name'], $university_id]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Department already exists']);
        exit;
    }

    // Insert department
    $stmt = $db->prepare('INSERT INTO departments (name, description, hod_name, university_id, created_at) VALUES (?, ?, ?, ?, NOW())');
    $stmt->execute([
        $name,
        $description,
        $hod_name,
        $university_id
    ]);

    echo json_encode(['message' => 'Department added successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}
// Clean any remaining output buffers

?>