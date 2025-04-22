<?php
session_start();
require_once '../../config.php';
require_once 'Auth.php';

// Clear any existing session data to prevent cross-university access
if (isset($_SESSION['university_id'])) {
    // Log the logout
    error_log("Clearing existing university session: {$_SESSION['university_name']} (ID: {$_SESSION['university_id']})");
    session_unset();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $auth = new Auth($db);
        
        // Use the specialized universityLogin method
        if ($auth->universityLogin($email, $password)) {
            // Log the successful login
            error_log("University login successful: {$_SESSION['university_name']} (ID: {$_SESSION['university_id']})");
            
            // Redirect to university dashboard
            header('Location: /gov/views/university/dashboard.php');
            exit;
        } else {
            // Login failed
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /gov/views/university-login.php');
            exit;
        }
    } catch (Exception $e) {
        // Log the error
        error_log("University login error: " . $e->getMessage());
        $_SESSION['error'] = 'An error occurred during login';
        header('Location: /gov/views/university-login.php');
        exit;
    }
} else {
    // Not a POST request
    header('Location: /gov/views/university-login.php');
    exit;
}
?>