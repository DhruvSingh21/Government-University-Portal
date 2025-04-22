<?php
session_start();
require_once '../../config.php';

// Check if user is logged in as a government official
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'government_official') {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get report type
$reportType = $_GET['type'] ?? '';
$format = strtolower($_GET['format'] ?? 'csv');

if ($format !== 'csv' && $format !== 'json') {
    $format = 'csv';
}

try {
    // Generate appropriate report based on type
    switch ($reportType) {
        case 'universities':
            generateUniversitiesReport($db, $format);
            break;
            
        case 'students':
            generateStudentsReport($db, $format);
            break;
            
        case 'courses':
            generateCoursesReport($db, $format);
            break;
            
        case 'departments':
            generateDepartmentsReport($db, $format);
            break;
            
        default:
            throw new Exception('Invalid report type');
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

function generateUniversitiesReport($db, $format) {
    $stmt = $db->prepare("
        SELECT u.id, u.name, u.email, u.address, u.phone, u.created_at,
            (SELECT COUNT(*) FROM departments d WHERE d.university_id = u.id) as departments_count,
            (SELECT COUNT(*) FROM students s WHERE s.university_id = u.id) as students_count
        FROM universities u
        ORDER BY u.name
    ");
    $stmt->execute();
    $universities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $filename = 'universities_report_' . date('Y-m-d') . '.' . $format;
    
    if ($format === 'json') {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode(['universities' => $universities], JSON_PRETTY_PRINT);
    } else {
        // CSV format
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['ID', 'Name', 'Email', 'Address', 'Phone', 'Registration Date', 'Departments', 'Students']);
        
        // Add rows
        foreach ($universities as $university) {
            fputcsv($output, [
                $university['id'],
                $university['name'],
                $university['email'],
                $university['address'] ?? '',
                $university['phone'] ?? '',
                $university['created_at'],
                $university['departments_count'],
                $university['students_count']
            ]);
        }
        
        fclose($output);
    }
    
    exit;
}

function generateStudentsReport($db, $format) {
    $stmt = $db->prepare("
        SELECT s.id, s.first_name, s.last_name, s.email, s.gender, s.enrollment_date,
            u.name as university_name, d.name as department_name, c.name as course_name, c.code as course_code
        FROM students s
        JOIN universities u ON s.university_id = u.id
        JOIN departments d ON s.department_id = d.id
        JOIN courses c ON s.course_id = c.id
        ORDER BY u.name, s.last_name, s.first_name
    ");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $filename = 'students_report_' . date('Y-m-d') . '.' . $format;
    
    if ($format === 'json') {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode(['students' => $students], JSON_PRETTY_PRINT);
    } else {
        // CSV format
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Gender', 'Enrollment Date', 'University', 'Department', 'Course', 'Course Code']);
        
        // Add rows
        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['first_name'],
                $student['last_name'],
                $student['email'],
                $student['gender'],
                $student['enrollment_date'],
                $student['university_name'],
                $student['department_name'],
                $student['course_name'],
                $student['course_code']
            ]);
        }
        
        fclose($output);
    }
    
    exit;
}

function generateCoursesReport($db, $format) {
    $stmt = $db->prepare("
        SELECT c.id, c.name, c.code, c.duration, 
            d.name as department_name, u.name as university_name,
            (SELECT COUNT(*) FROM students s WHERE s.course_id = c.id) as students_count
        FROM courses c
        JOIN departments d ON c.department_id = d.id
        JOIN universities u ON d.university_id = u.id
        ORDER BY u.name, d.name, c.name
    ");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $filename = 'courses_report_' . date('Y-m-d') . '.' . $format;
    
    if ($format === 'json') {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode(['courses' => $courses], JSON_PRETTY_PRINT);
    } else {
        // CSV format
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['ID', 'Course Name', 'Code', 'Duration', 'Department', 'University', 'Enrolled Students']);
        
        // Add rows
        foreach ($courses as $course) {
            fputcsv($output, [
                $course['id'],
                $course['name'],
                $course['code'],
                $course['duration'] ?? 'Not specified',
                $course['department_name'],
                $course['university_name'],
                $course['students_count']
            ]);
        }
        
        fclose($output);
    }
    
    exit;
}

function generateDepartmentsReport($db, $format) {
    $stmt = $db->prepare("
        SELECT d.id, d.name, d.description, d.hod_name, d.created_at,
            u.name as university_name,
            (SELECT COUNT(*) FROM courses c WHERE c.department_id = d.id) as courses_count,
            (SELECT COUNT(*) FROM students s WHERE s.department_id = d.id) as students_count
        FROM departments d
        JOIN universities u ON d.university_id = u.id
        ORDER BY u.name, d.name
    ");
    $stmt->execute();
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $filename = 'departments_report_' . date('Y-m-d') . '.' . $format;
    
    if ($format === 'json') {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode(['departments' => $departments], JSON_PRETTY_PRINT);
    } else {
        // CSV format
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['ID', 'Department Name', 'Description', 'HOD Name', 'University', 'Courses', 'Students', 'Created Date']);
        
        // Add rows
        foreach ($departments as $dept) {
            fputcsv($output, [
                $dept['id'],
                $dept['name'],
                $dept['description'] ?? '',
                $dept['hod_name'] ?? 'Not specified',
                $dept['university_name'],
                $dept['courses_count'],
                $dept['students_count'],
                $dept['created_at']
            ]);
        }
        
        fclose($output);
    }
    
    exit;
}
?>
