<?php
session_start();
include_once 'includes/config.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

$errors = [];
$name = $email = $phone = $role = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'] ?? 'student';

    // Validation
    if (empty($name)) $errors['name'] = 'Full name is required';
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    if (empty($phone)) $errors['phone'] = 'Phone number is required';
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }
    if ($password !== $confirm_password) $errors['confirm_password'] = 'Passwords do not match';

    // Check existing email
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) $errors['email'] = 'Email already registered';
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }

    // Register user
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $hashed_password, $role]);
            
            $_SESSION['success'] = 'Registration successful! Please login';
            header('Location: login.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduGov Connect</title>
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
            @apply text-secondary;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-20">
        <div class="animate-float absolute w-64 h-64 bg-gradient-to-r from-secondary/20 to-accent/20 rounded-full blur-3xl -top-32 -left-32"></div>
        <div class="animate-float absolute w-96 h-96 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-full blur-3xl -bottom-48 -right-48" style="animation-delay: -3s"></div>
    </div>

    <!-- Registration Card -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-gray-900/80 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl w-full max-w-md transform transition-all duration-500 hover:border-secondary/30">
            <!-- Header -->
            <div class="p-8 border-b border-gray-700/50">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-2xl text-secondary"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white ml-4">Create Account</h1>
                </div>
                <p class="text-gray-400 text-center">Join EduGov Connect Platform</p>
            </div>

            <!-- Registration Form -->
            <div class="p-8 pt-6">
                <?php if (!empty($errors)): ?>
                    <div class="mb-6 px-4 py-3 bg-red-500/20 border border-red-500/30 rounded-lg">
                        <?php foreach($errors as $error): ?>
                            <p class="text-red-300 text-sm flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <!-- Name Input -->
                    <div class="relative">
                        <input type="text" name="name" id="name" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                               value="<?= htmlspecialchars($name) ?>"
                               placeholder="John Doe">
                        <label for="name" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-secondary">
                            Full Name
                        </label>
                        <i class="fas fa-user text-gray-500 absolute right-4 top-3.5"></i>
                    </div>

                    <!-- Email Input -->
                    <div class="relative">
                        <input type="email" name="email" id="email" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                               value="<?= htmlspecialchars($email) ?>"
                               placeholder="john@example.com">
                        <label for="email" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-secondary">
                            Email Address
                        </label>
                        <i class="fas fa-envelope text-gray-500 absolute right-4 top-3.5"></i>
                    </div>

                    <!-- Phone Input -->
                    <div class="relative">
                        <input type="tel" name="phone" id="phone" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                               value="<?= htmlspecialchars($phone) ?>"
                               placeholder="+91 9876543210">
                        <label for="phone" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-secondary">
                            Phone Number
                        </label>
                        <i class="fas fa-phone text-gray-500 absolute right-4 top-3.5"></i>
                    </div>

                    <!-- Role Selection -->
                    <div class="relative">
                        <select name="role" class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white focus:border-secondary focus:ring-2 focus:ring-secondary/50">
                            <option value="student" <?= $role === 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="government_admin" <?= $role === 'government_admin' ? 'selected' : '' ?>>Government Administrator</option>
                        </select>
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                               placeholder="••••••••">
                        <label for="password" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-secondary">
                            Password
                        </label>
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute right-4 top-3.5 text-gray-500 hover:text-secondary transition-colors">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </button>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <input type="password" name="confirm_password" id="confirm_password" 
                               class="input-field w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg text-white placeholder-transparent peer"
                               placeholder="••••••••">
                        <label for="confirm_password" class="absolute left-4 top-3.5 text-gray-400 transition-all duration-300 pointer-events-none peer-focus:text-secondary">
                            Confirm Password
                        </label>
                        <button type="button" onclick="togglePassword('confirm_password')" 
                                class="absolute right-4 top-3.5 text-gray-500 hover:text-secondary transition-colors">
                            <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-6 bg-gradient-to-r from-secondary to-accent text-white font-semibold rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-secondary/20 relative overflow-hidden group">
                        <span class="relative z-10">Create Account</span>
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>

                <!-- Login Link -->
                <p class="mt-8 text-center text-gray-400">
                    Already have an account? 
                    <a href="login.php" class="text-secondary hover:text-accent transition-colors font-medium">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(`toggle${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)}`);
            field.type = field.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash');
        }

        // Form Submission Loader
        document.querySelector('form').addEventListener('submit', (e) => {
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            btn.disabled = true;
        });
    </script>
</body>
</html>