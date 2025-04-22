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
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // First verify department belongs to this university
    $deptCheck = $db->prepare("SELECT id FROM departments WHERE id = ? AND university_id = ?");
    $deptCheck->execute([$department_id, $university_id]);
    
    if ($deptCheck->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid department']);
        exit;
    }
    
    // Get courses for this department
    $stmt = $db->prepare("SELECT id, name, code FROM courses WHERE department_id = ?");
    $stmt->execute([$department_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log the result
    error_log("Courses query executed. Found " . count($courses) . " courses");
    error_log("Courses JSON: " . json_encode(['courses' => $courses]));
    
    echo json_encode(['courses' => $courses]);
    
} catch (Exception $e) {
    error_log("Courses fetch error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
