<?php
session_start();
include_once 'includes/config.php';

if (isset($_SESSION['user'])) {
    header('Location: ' . ($_SESSION['user']['role'] === 'government_admin' ? 'government_dashboard.php' : 'university_dashboard.php'));
    exit();
}

$error = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGov Connect - Login Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-20">
        <div class="animate-float absolute w-64 h-64 bg-gradient-to-r from-secondary/20 to-accent/20 rounded-full blur-3xl -top-32 -left-32"></div>
        <div class="animate-float absolute w-96 h-96 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-full blur-3xl -bottom-48 -right-48" style="animation-delay: -3s"></div>
    </div>

    <!-- Main Content -->
    <div class="relative min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-secondary/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-university text-3xl text-secondary"></i>
                </div>
                <h1 class="text-4xl font-bold text-white ml-4">EduGov Connect</h1>
            </div>
            <p class="text-xl text-gray-300">Unified Education Management Platform</p>
        </div>

        <!-- Login Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">
            <!-- Government Admin Login -->
            <a href="government_login.php" class="login-card relative bg-gray-900/80 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-8 transition-all duration-300 hover:border-secondary/30">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-landmark text-2xl text-blue-400"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-white text-center mb-3">Government Portal</h2>
                <p class="text-gray-400 text-center mb-6">Access national education data and analytics</p>
                <button class="w-full py-3 px-6 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                    Government Login
                </button>
            </a>

            <!-- University Admin Login -->
            <a href="university_login.php" class="login-card relative bg-gray-900/80 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-8 transition-all duration-300 hover:border-secondary/30">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-university text-2xl text-green-400"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-white text-center mb-3">University Portal</h2>
                <p class="text-gray-400 text-center mb-6">Manage your institution's data and reports</p>
                <button class="w-full py-3 px-6 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors">
                    University Login
                </button>
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-gray-400">
            <p>Need help? <a href="contact.php" class="text-secondary hover:text-accent">Contact support</a></p>
        </div>
    </div>
</body>
</html>