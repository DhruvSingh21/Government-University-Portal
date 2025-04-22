<?php
session_start();
require_once '../../config.php';
require_once 'Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'email', 'department', 'designation', 'employee_id', 'password', 'confirm_password'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /gov/views/register-government.php');
            exit;
        }
    }

    // Validate password match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: /gov/views/register-government.php');
        exit;
    }

    // Validate email format and domain - Fixed pattern to match exactly @gov.in at the end
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/@gov\.in$/', $_POST['email'])) {
        $_SESSION['error'] = 'Invalid government email format. Must end with @gov.in';
        header('Location: /gov/views/register-government.php');
        exit;
    }

    try {
        $auth = new Auth($db);
        
        if ($auth->registerGovernment($_POST)) {
            // Set success message that will be displayed on the login page
            $_SESSION['success'] = 'Registration successful! You can now login with your credentials.';
            
            // Log the successful registration
            error_log("Government official registered successfully: {$_POST['email']}");
            
            header('Location: /gov/views/government-login.php');
            exit;
        } else {
            throw new Exception('Registration failed');
        }
    } catch (Exception $e) {
        // Set detailed error message
        $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
        
        // Log the error
        error_log("Government registration error: " . $e->getMessage());
        
        header('Location: /gov/views/register-government.php');
        exit;
    }
} else {
    // If not POST request, just display the registration form
    header('Location: /gov/views/register-government.php');
    exit;
}
?>