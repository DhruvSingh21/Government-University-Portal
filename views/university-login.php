<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Login - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center">
    <!-- Back to home button (added) -->
    <a href="/gov/views/home.php" class="absolute top-4 left-4 flex items-center text-white hover:text-blue-300 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Home
    </a>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-gray-900 bg-opacity-50 p-8 rounded-xl">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="/gov/assets/images/logo.svg" alt="University Login" class="h-12 w-12 text-white">
                </div>
                <h2 class="text-white text-xl font-semibold">University Login</h2>
                <p class="text-gray-400 text-sm">Access your university dashboard</p>
            </div>

            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="mb-4 bg-red-500 text-white p-3 rounded">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div class="mb-4 bg-green-500 text-white p-3 rounded">' . htmlspecialchars($_SESSION['success']) . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <form action="/gov/controllers/auth/university-login.php" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                        placeholder="university@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500"
                        placeholder="••••••••">
                </div>

                <button type="submit" 
                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition-colors">
                    Login to University Portal
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="#" class="text-gray-400 hover:text-gray-300 text-sm">Forgot Password?</a>
            </div>

            <div class="mt-6 text-center text-gray-400 text-sm">
                New University? 
                <a href="/gov/views/register-university.php" class="text-green-500 hover:text-green-400">Register Here</a>
            </div>

            <div class="mt-4 text-center text-gray-400 text-sm">
                Looking for Government Portal? 
                <a href="/gov/views/government-login.php" class="text-blue-500 hover:text-blue-400">Click here</a>
            </div>
        </div>
    </div>
</body>
</html>