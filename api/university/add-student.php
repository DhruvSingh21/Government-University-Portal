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

// Get university ID from session
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

// Validate required fields based on schema
$requiredFields = ['first_name', 'last_name', 'email', 'department_id', 'course_id', 'gender', 'enrollment_date'];
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
    
    // Validate department ID belongs to this university
    $deptStmt = $db->prepare("SELECT id FROM departments WHERE id = ? AND university_id = ?");
    $deptStmt->execute([$data['department_id'], $university_id]);
    
    if ($deptStmt->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid department selected']);
        exit;
    }
    
    // Validate course ID belongs to the selected department
    $courseStmt = $db->prepare("SELECT id FROM courses WHERE id = ? AND department_id = ?");
    $courseStmt->execute([$data['course_id'], $data['department_id']]);
    
    if ($courseStmt->rowCount() === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid course selected for this department']);
        exit;
    }
    
    // Check if email already exists
    $emailCheck = $db->prepare("SELECT id FROM students WHERE email = ?");
    $emailCheck->execute([$data['email']]);
    
    if ($emailCheck->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'A student with this email already exists']);
        exit;
    }
    
    // Insert student into database - using exact fields from schema
    $stmt = $db->prepare("
        INSERT INTO students (
            university_id, department_id, course_id, first_name, last_name, 
            email, gender, enrollment_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $university_id,
        $data['department_id'],
        $data['course_id'],
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['gender'],
        $data['enrollment_date']
    ]);
    
    $studentId = $db->lastInsertId();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Student added successfully',
        'student_id' => $studentId
    ]);
    
} catch (Exception $e) {
    error_log("Add student error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add student: ' . $e->getMessage()]);
}
?>