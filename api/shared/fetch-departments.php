<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set headers for JSON response
header('Content-Type: application/json');

// Check if university is logged in
if (!isset($_SESSION['university_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$university_id = $_SESSION['university_id'];

try {
    // Connect to database directly (avoid potential issues in config.php)
    $host = 'localhost';
    $dbname = 'edugov_connect';
    $username = 'root';
    $password = '';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Simple query to fetch departments
    $stmt = $db->prepare("SELECT id, name FROM departments WHERE university_id = ?");
    $stmt->execute([$university_id]);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return departments as JSON
    echo json_encode(['departments' => $departments]);
    
} catch (Exception $e) {
    error_log("Department fetch error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
