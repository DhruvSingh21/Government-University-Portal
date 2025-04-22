<?php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');

// Check if user is logged in as university
if (!isset($_SESSION['university_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$university_id = $_SESSION['university_id'];
$department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

if (!$department_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Department ID is required']);
    exit;
}

try {
    // Connect to database directly for simplicity
    $host = 'localhost';
    $dbname = 'edugov_connect';
    $username = 'root';
    $password = '';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verify department belongs to university
    $verifyStmt = $db->prepare("SELECT id FROM departments WHERE id = ? AND university_id = ?");
    $verifyStmt->execute([$department_id, $university_id]);
    
    if ($verifyStmt->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid department']);
        exit;
    }
    
    // Fetch courses for this department
    $stmt = $db->prepare("SELECT id, name, code FROM courses WHERE department_id = ?");
    $stmt->execute([$department_id]);
    
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['courses' => $courses]);
    
} catch (Exception $e) {
    error_log('Error fetching courses: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch courses: ' . $e->getMessage()]);
}
?>
