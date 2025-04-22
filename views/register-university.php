<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register University - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12">
    <!-- Back to home button (added) -->
    <a href="/gov/views/home.php" class="absolute top-4 left-4 flex items-center text-white hover:text-blue-300 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Home
    </a>
    
    <div class="container mx-auto px-4">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="max-w-2xl mx-auto mb-4 bg-red-500 text-white p-4 rounded-xl">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="max-w-2xl mx-auto mb-4 bg-green-500 text-white p-4 rounded-xl">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <div class="max-w-2xl mx-auto bg-gray-900 bg-opacity-50 p-8 rounded-xl">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="/gov/assets/images/logo.svg" alt="University Registration" class="h-12 w-12 text-white">
                </div>
                <h2 class="text-white text-2xl font-semibold">Register Your University</h2>
                <p class="text-gray-400 text-sm">Join the national education network</p>
            </div>

            <form action="/gov/controllers/auth/register-university.php" method="POST" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="university_name" class="block text-sm font-medium text-gray-400 mb-2">University Name</label>
                        <input type="text" id="university_name" name="university_name" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                            placeholder="Enter university name">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">University Email</label>
                        <input type="email" id="email" name="email" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                            placeholder="official@university.edu">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Contact Number</label>
                        <input type="tel" id="phone" name="phone" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                            placeholder="Enter contact number">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-400 mb-2">Address</label>
                        <input type="text" id="address" name="address" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                            placeholder="Enter university address">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-400 mb-2">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="terms" name="terms" required class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-700 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-400">
                        I agree to the <a href="#" class="text-green-500 hover:text-green-400">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    Register University
                </button>
            </form>

            <div class="mt-6 text-center text-gray-400 text-sm">
                Already registered? 
                <a href="/gov/views/university-login.php" class="text-green-500 hover:text-green-400">Login here</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const phone = document.getElementById('phone').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            if (!/^\d{10}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
                return;
            }
        });
    </script>
</body>
</html>