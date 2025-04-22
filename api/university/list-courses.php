<?php
session_start();

// Set headers for JSON response
header('Content-Type: application/json');

// Check if university is logged in
if (!isset($_SESSION['university_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the department ID from query string
$department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

if (!$department_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Department ID is required']);
    exit;
}

try {
    // Connect to database directly
    $host = 'localhost';
    $dbname = 'edugov_connect';
    $username = 'root';
    $password = '';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get courses for the department
    $stmt = $db->prepare("SELECT id, name, code FROM courses WHERE department_id = ?");
    $stmt->execute([$department_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the courses as JSON
    echo json_encode(['courses' => $courses]);
    
} catch (Exception $e) {
    error_log('Course fetch error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching courses: ' . $e->getMessage()]);
}
?>
