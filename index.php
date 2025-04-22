<?php
require_once 'config.php';

// Router implementation
$request = $_SERVER['REQUEST_URI'];
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$path = str_replace($basePath, '', $request);

// Remove query strings
$path = parse_url($path, PHP_URL_PATH);

// Routes
switch ($path) {
    case '/':
        require 'views/home.php';
        break;
        
    case '/login':
        require 'views/login.php';
        break;
        
    case '/register-university':
        require 'views/register-university.php';
        break;
        
    case '/register-government':
        require 'views/register-government.php';
        break;
        
    case '/university-dashboard':
        require 'views/university/dashboard.php';
        break;
        
    case '/government-dashboard':
        require 'views/government/dashboard.php';
        break;
        
    default:
        http_response_code(404);
        require 'views/404.php';
        break;
}