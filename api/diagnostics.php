<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

// Set security headers to prevent misuse
header('Content-Type: application/json');

// Only allow access in development/testing environments
if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Basic diagnostics array
$diagnostics = [
    'php_version' => PHP_VERSION,
    'server' => $_SERVER['SERVER_SOFTWARE'],
    'timestamp' => date('Y-m-d H:i:s'),
    'session' => [
        'university_id' => $_SESSION['university_id'] ?? 'not set',
    ]
];

// Check if database connection exists, if not create one
if (!isset($db) || $db === null) {
    $diagnostics['db_connection_note'] = 'Database connection not found in config.php, attempting to create one now';
    
    try {
        // Common database connection settings - adjust as needed
        $host = 'localhost';
        $dbname = 'edugov_connect'; // Guessing the database name
        $username = 'root';  // Default XAMPP username
        $password = '';      // Default XAMPP password (empty)
        
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $diagnostics['db_connection'] = 'Connection failed';
        $diagnostics['error'] = 'Could not connect to database: ' . $e->getMessage();
        echo json_encode($diagnostics, JSON_PRETTY_PRINT);
        exit;
    }
}

// Check database connection
try {
    $diagnostics['db_connection'] = 'Connected successfully';
    $diagnostics['pdo_driver'] = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
    
    // Get database tables
    $tables = [];
    $stmt = $db->query("SHOW TABLES");
    
    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        $diagnostics['tables'] = $tables;
        
        // If departments table exists, check its structure
        if (in_array('departments', $tables)) {
            $stmt = $db->query("DESCRIBE departments");
            $columns = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row;
            }
            
            $diagnostics['departments_structure'] = $columns;
            
            // Check for any departments in the table
            $stmt = $db->query("SELECT COUNT(*) FROM departments");
            $count = $stmt->fetchColumn();
            $diagnostics['departments_count'] = $count;
            
            // If user is logged in, check university-specific departments
            if (isset($_SESSION['university_id'])) {
                $university_id = $_SESSION['university_id'];
                $stmt = $db->prepare("SELECT COUNT(*) FROM departments WHERE university_id = ?");
                $stmt->execute([$university_id]);
                $count = $stmt->fetchColumn();
                $diagnostics['university_departments_count'] = $count;
                
                // Sample departments (limit 5)
                $stmt = $db->prepare("SELECT id, name FROM departments WHERE university_id = ? LIMIT 5");
                $stmt->execute([$university_id]);
                $sample = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $diagnostics['university_departments_sample'] = $sample;
            }
        }
    }
} catch (PDOException $e) {
    $diagnostics['db_connection'] = 'Connection failed';
    $diagnostics['error'] = $e->getMessage();
}

echo json_encode($diagnostics, JSON_PRETTY_PRINT);
?>
