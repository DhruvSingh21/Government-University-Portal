<?php
session_start();
include_once 'includes/config.php';

if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'university_admin') {
    header('Location: university_dashboard.php');
    exit();
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'university_admin'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_approved']) {
                    $_SESSION['user'] = $user;
                    
                    // Update last login
                    $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                    $updateStmt->execute([$user['user_id']]);
                    
                    // Log the login
                    $logStmt = $pdo->prepare("INSERT INTO audit_log (user_id, action, ip_address) VALUES (?, ?, ?)");
                    $logStmt->execute([$user['user_id'], 'login', $_SERVER['REMOTE_ADDR']]);
                    
                    header('Location: university_dashboard.php');
                    exit();
                } else {
                    $error = 'Your account is pending approval';
                }
            } else {
                $error = 'Invalid university admin credentials';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Admin Login - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .input-field:focus ~ label,
        .input-field:not(:placeholder-shown) ~ label {
            transform: translateY(-24px) scale(0.9);
            @apply text-green-400;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-20">
        <div class="animate-float absolute w-64 h-64 bg-gradient-to-r from-green-500/20 to-green-700/20 rounded-full blur-3xl -top-32 -left-32"></div>
        <div class="animate-float absolute w-96 h-96 bg-gradient-to-r from-green-600/20 to-green-800/20 rounded-full blur-3xl -bottom-48 -right-48" style="animation-delay: -3s"></div>
    </div>

    <!-- Login Card -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-gray-900/80 backdrop-blur-xl rounded-2xl border border-green-700/50 shadow-2xl w-full max-w-md transform transition-all duration-500 hover:border-green-400/30">
            <!-- Header -->
            <div class="p-8 border-b border-green-700/50">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-university text-2xl text-green-400"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white ml-4">University Portal</h1>
                </div>
                <p class="text-gray-400 text-center">Manage your institution's data</p>
            </div>

            <!-- Form Section -->
            <div class="p-8 pt-6">
                <?php if ($error): ?>
                <div class="mb-6 px-4 py-3 bg-red-500/20 border border-red-500/30 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <span class="text-red-300 text-sm"><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <!-- Email Input -->
                    <div class="relative">
                        <input type="email" name="email" id="email" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                              
                        <label for="email" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-green-400">
                            University Email
                        </label>
                        <i class="fas fa-envelope text-gray-500 absolute right-4 top-3.5"></i>
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                              
                        <label for="password" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-green-400">
                            Password
                        </label>
                        <button type="button" onclick="togglePassword()" 
                                class="absolute right-4 top-3.5 text-gray-500 hover:text-green-400 transition-colors">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-6 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20 relative overflow-hidden group">
                        <span class="relative z-10">Login to University Portal</span>
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="mt-8 text-center space-y-4">
                    <p class="text-gray-400">
                        <a href="university_forgot_password.php" class="text-green-400 hover:text-green-300 transition-colors">
                            Forgot Password?
                        </a>
                    </p>
                    <p class="text-gray-400">
                        New to EduGov Connect? 
                        <a href="university_register.php" class="text-green-400 hover:text-green-300 transition-colors font-medium">
                            Register Your University
                        </a>
                    </p>
                    <p class="text-gray-400 text-sm mt-6">
                        <a href="government_login.php" class="hover:text-white transition-colors">
                            Looking for Government Portal? <span class="text-blue-400">Click here</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash');
        }

        // Form Submission Loader
        document.querySelector('form').addEventListener('submit', (e) => {
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            btn.disabled = true;
        });

        // Auto-hide Error Message
        <?php if ($error): ?>
            setTimeout(() => {
                document.querySelector('[role="alert"]').style.opacity = '0';
            }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>