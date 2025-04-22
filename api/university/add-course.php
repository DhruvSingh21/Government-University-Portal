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

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is valid
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Validate required fields - updated to match the actual schema
$requiredFields = ['name', 'department_id', 'credits'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

try {
    // Connect to database directly
    $host = 'localhost';
    $dbname = 'edugov_connect';
    $username = 'root';
    $password = '';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verify department belongs to this university
    $deptStmt = $db->prepare("SELECT id FROM departments WHERE id = ? AND university_id = ?");
    $deptStmt->execute([$data['department_id'], $university_id]);
    
    if ($deptStmt->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid department selected']);
        exit;
    }
    
    // Generate a course code
    $courseCode = strtoupper(substr(str_replace(' ', '', $data['name']), 0, 3)) . rand(100, 999);
    
    // Check if course name already exists in this department
    $checkStmt = $db->prepare("SELECT id FROM courses WHERE name = ? AND department_id = ?");
    $checkStmt->execute([$data['name'], $data['department_id']]);
    
    if ($checkStmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'A course with this name already exists in this department']);
        exit;
    }
    
    // Set duration based on credits
    $duration = $data['credits'] . ' semesters';
    if (isset($data['duration']) && !empty($data['duration'])) {
        $duration = $data['duration'];
    }
    
    // Insert course into database - updated fields to match schema
    $stmt = $db->prepare("
        INSERT INTO courses (
            department_id, name, code, duration
        ) VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $data['department_id'],
        $data['name'],
        $courseCode,
        $duration
    ]);
    
    $courseId = $db->lastInsertId();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Course added successfully',
        'course_id' => $courseId,
        'course_code' => $courseCode
    ]);
    
} catch (Exception $e) {
    error_log("Add course error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add course: ' . $e->getMessage()]);
}
?>