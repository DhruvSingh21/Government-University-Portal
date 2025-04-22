<?php
session_start();
require_once '../../config.php';
require_once 'Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $auth = new Auth($db);
        
        if ($auth->governmentLogin($email, $password)) {
            // Successful government login
            header('Location: /gov/views/government/dashboard.php');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /gov/views/government-login.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
        header('Location: /gov/views/government-login.php');
        exit;
    }
} else {
    header('Location: /gov/views/government-login.php');
    exit;
}
?>