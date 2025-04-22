<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Government Official - EduGov Connect</title>
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
        <div class="max-w-2xl mx-auto bg-gray-900 bg-opacity-50 p-8 rounded-xl">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="/gov/assets/images/logo.svg" alt="Government Registration" class="h-12 w-12 text-white">
                </div>
                <h2 class="text-white text-2xl font-semibold">Register as Government Official</h2>
                <p class="text-gray-400 text-sm">Access and monitor national education data</p>
            </div>

            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="mb-6 bg-red-500 text-white p-4 rounded">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div class="mb-6 bg-green-500 text-white p-4 rounded">' . htmlspecialchars($_SESSION['success']) . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <form action="/gov/controllers/auth/register-government.php" method="POST" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-400 mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="Enter first name">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-400 mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="Enter last name">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Official Email</label>
                        <input type="email" id="email" name="email" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="official@gov.in">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-400 mb-2">Department</label>
                        <input type="text" id="department" name="department" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="Enter department name">
                    </div>

                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-400 mb-2">Designation</label>
                        <input type="text" id="designation" name="designation" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="Enter designation">
                    </div>

                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-400 mb-2">Employee ID</label>
                        <input type="text" id="employee_id" name="employee_id" required 
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="Enter employee ID">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-400 mb-2">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-blue-500"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="terms" name="terms" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-700 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-400">
                        I agree to the <a href="#" class="text-blue-500 hover:text-blue-400">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                    Register as Government Official
                </button>
            </form>

            <div class="mt-6 text-center text-gray-400 text-sm">
                Already registered? 
                <a href="/gov/views/government-login.php" class="text-blue-500 hover:text-blue-400">Login here</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>