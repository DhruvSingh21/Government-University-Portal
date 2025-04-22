<?php
require_once '../../config.php';
require_once 'Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['university_name', 'email', 'phone', 'address', 'password', 'confirm_password'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /gov/views/register-university.php');
            exit;
        }
    }

    // Validate password match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: /gov/views/register-university.php');
        exit;
    }

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
        header('Location: /gov/views/register-university.php');
        exit;
    }

    try {
        // Check if email already exists in universities table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM universities WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'This email is already registered';
            header('Location: /gov/views/register-university.php');
            exit;
        }

        // Check if email already exists in users table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'This email is already registered';
            header('Location: /gov/views/register-university.php');
            exit;
        }

        $auth = new Auth($conn);
        
        if ($auth->registerUniversity($_POST)) {
            $_SESSION['success'] = 'University registered successfully. Please login.';
            header('Location: /gov/views/university-login.php');
            exit;
        }
    } catch (Exception $e) {
        error_log('University registration error: ' . $e->getMessage());
        $_SESSION['error'] = 'An error occurred during registration. Please try again.';
        header('Location: /gov/views/register-university.php');
        exit;
    }
} else {
    header('Location: /gov/views/register-university.php');
    exit;
}