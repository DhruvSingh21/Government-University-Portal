<?php
// logout.php
session_start();

// Store user name before destroying session
$username = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'User';

// Destroy session completely
session_unset();
session_destroy();
session_write_close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-gray-800/80 backdrop-blur-lg rounded-xl border border-gray-700 p-8 shadow-xl text-center">
            <div class="mb-6">
                <div class="w-16 h-16 bg-red-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sign-out-alt text-3xl text-red-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Logged Out Successfully</h1>
                <p class="text-gray-300">Goodbye, <?= htmlspecialchars($username) ?></p>
            </div>

            <div class="space-y-4">
                <p class="text-gray-400">
                    You have been securely logged out of the system.
                </p>
                
                <div class="mt-6">
                    <a href="login.php" 
                       class="inline-flex items-center px-6 py-2 bg-secondary hover:bg-accent text-white rounded-lg transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Again
                    </a>
                </div>

                <div class="mt-4 text-sm text-gray-500">
                    Redirecting to home page in <span id="countdown">5</span> seconds...
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatic redirect countdown
        let seconds = 5;
        const countdown = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            countdown.textContent = seconds;
            
            if(seconds <= 0) {
                clearInterval(interval);
                window.location.href = 'index.php';
            }
        }, 1000);
    </script>
</body>
</html>