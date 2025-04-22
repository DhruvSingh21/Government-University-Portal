<?php
// Error reporting for development environment
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'edugov_connect');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    // Create PDO connection
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // Log the error but don't display connection details
    error_log("Database connection failed: " . $e->getMessage());
    die("Could not connect to the database. Please contact the administrator.");
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application configuration
define('APP_NAME', 'EduGov Connect');
define('APP_URL', 'http://localhost/gov');
define('APP_ROOT', dirname(__FILE__));

// Database connection and initialization
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    // Create database if not exists
    $conn->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->exec("USE " . DB_NAME);

    // Check if tables exist before creating them
    $tables = ['users', 'universities', 'departments', 'courses', 'students', 'reports'];
    $tables_to_create = [];
    
    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'")->fetch();
        if (!$stmt) {
            $tables_to_create[] = $table;
        }
    }
    
    if (!empty($tables_to_create)) {
        $sql = file_get_contents(__DIR__ . '/schema.sql');
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (empty($statement)) continue;
            
            // Only execute CREATE TABLE statements for missing tables
            if (strpos(strtoupper($statement), 'CREATE TABLE') !== false) {
                foreach ($tables_to_create as $table) {
                    if (strpos(strtoupper($statement), "CREATE TABLE $table") !== false) {
                        try {
                            $conn->exec($statement);
                        } catch (PDOException $e) {
                            // Ignore table already exists error
                            if ($e->getCode() !== '42S01') {
                                throw $e;
                            }
                        }
                        break;
                    }
                }
            } else {
                // Execute non-CREATE TABLE statements
                $conn->exec($statement);
            }
        }
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>