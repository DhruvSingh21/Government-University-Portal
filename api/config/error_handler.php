<?php
// Disable displaying errors in HTML format
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Custom error handler function
function jsonErrorHandler($errno, $errstr, $errfile, $errline) {
    $error = [
        'error' => 'Internal Server Error',
        'details' => $errstr,
        'file' => basename($errfile),
        'line' => $errline
    ];
    
    // Only include technical details if not in production mode
    if (!defined('PRODUCTION') || !PRODUCTION) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode($error);
    } else {
        // In production, only show generic error
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
    exit;
}

// Set the custom error handler
set_error_handler('jsonErrorHandler');

// Handle fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        jsonErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
    }
});