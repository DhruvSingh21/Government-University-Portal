<?php
session_start();

// Save the user type before clearing session
$wasGovernment = isset($_SESSION['role']) && $_SESSION['role'] === 'government_official';

// Log the logout
if (isset($_SESSION['university_id'])) {
    error_log("University logout: {$_SESSION['university_name']} (ID: {$_SESSION['university_id']})");
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'government_official') {
    error_log("Government official logout: {$_SESSION['first_name']} {$_SESSION['last_name']}");
}

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Start a new session for flash messages
session_start();
$_SESSION['success'] = 'You have been logged out successfully';

// Redirect based on user type
if ($wasGovernment) {
    header('Location: /gov/views/government-login.php');
} else {
    header('Location: /gov/views/university-login.php');
}
exit;
?>
