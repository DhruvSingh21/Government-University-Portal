<?php
session_start();
require_once '../../config.php';

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to client

// Ensure clean output buffer
ob_clean();

header('Content-Type: application/json');

// Check if user is logged in as university
if (!isset($_SESSION['university_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$university_id = $_SESSION['university_id'];

try {
    // Create database connection without using config.php
    $host = 'localhost';
    $dbname = 'edugov_connect';
    $username = 'root';
    $password = '';
    
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stats = [
        'totalStudents' => 0,
        'totalDepartments' => 0,
        'totalCourses' => 0,
        'genderDistribution' => [0, 0, 0],
        'departments' => [],
        'recentUpdates' => []
    ];
    
    // Get total students
    try {
        $stmt = $db->prepare('SELECT COUNT(*) FROM students WHERE university_id = ?');
        $stmt->execute([$university_id]);
        $stats['totalStudents'] = (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("Error getting students count: " . $e->getMessage());
    }

    // Get total departments
    try {
        $stmt = $db->prepare('SELECT COUNT(*) FROM departments WHERE university_id = ?');
        $stmt->execute([$university_id]);
        $stats['totalDepartments'] = (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("Error getting departments count: " . $e->getMessage());
    }

    // Get total courses
    try {
        $stmt = $db->prepare('
            SELECT COUNT(c.id) 
            FROM courses c 
            JOIN departments d ON c.department_id = d.id 
            WHERE d.university_id = ?
        ');
        $stmt->execute([$university_id]);
        $stats['totalCourses'] = (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("Error getting courses count: " . $e->getMessage());
    }

    // Get gender distribution
    try {
        $stmt = $db->prepare('SELECT gender, COUNT(*) as count FROM students WHERE university_id = ? GROUP BY gender');
        $stmt->execute([$university_id]);
        $genderData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($genderData as $data) {
            switch(strtolower($data['gender'])) {
                case 'male': $stats['genderDistribution'][0] = (int)$data['count']; break;
                case 'female': $stats['genderDistribution'][1] = (int)$data['count']; break;
                default: $stats['genderDistribution'][2] += (int)$data['count'];
            }
        }
    } catch (Exception $e) {
        error_log("Error getting gender distribution: " . $e->getMessage());
    }

    // Get department-wise student count
    try {
        $stmt = $db->prepare('
            SELECT d.id, d.name, COUNT(s.id) as students 
            FROM departments d 
            LEFT JOIN students s ON s.department_id = d.id 
            WHERE d.university_id = ? 
            GROUP BY d.id, d.name
            ORDER BY d.name
        ');
        $stmt->execute([$university_id]);
        $stats['departments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting departments: " . $e->getMessage());
    }

    // Get recent updates
    try {
        // Student additions
        $stmt = $db->prepare("
            SELECT 'Student Added' as action_type, 
                CONCAT(first_name, ' ', last_name, ' was enrolled') as description, 
                created_at
            FROM students 
            WHERE university_id = ? 
            ORDER BY created_at DESC LIMIT 3
        ");
        $stmt->execute([$university_id]);
        $stats['recentUpdates'] = array_merge($stats['recentUpdates'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        
        // Department additions
        $stmt = $db->prepare("
            SELECT 'Department Added' as action_type, 
                CONCAT(name, ' department was created') as description, 
                created_at
            FROM departments 
            WHERE university_id = ? 
            ORDER BY created_at DESC LIMIT 2
        ");
        $stmt->execute([$university_id]);
        $stats['recentUpdates'] = array_merge($stats['recentUpdates'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        error_log("Error getting recent updates: " . $e->getMessage());
    }
    
    // Sort recent updates by date (newest first)
    usort($stats['recentUpdates'], function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    // Return JSON response
    echo json_encode($stats);

} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>