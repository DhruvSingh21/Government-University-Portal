<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Options - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center">
    <a href="/gov/views/home.php" class="absolute top-4 left-4 flex items-center text-white hover:text-blue-300 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Home
    </a>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center text-white mb-8">Choose Login Type</h1>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Government Login Card -->
                <div class="card bg-gray-900 bg-opacity-70 rounded-xl p-8 text-center">
                    <div class="bg-blue-700 rounded-full mx-auto p-4 inline-block mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-white text-2xl font-semibold mb-2">Government Login</h2>
                    <p class="text-gray-400 mb-6">Access national education data and generate reports as a government official.</p>
                    <a href="/gov/views/government-login.php" class="inline-block bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors w-full">
                        Login as Government Official
                    </a>
                </div>
                
                <!-- University Login Card -->
                <div class="card bg-gray-900 bg-opacity-70 rounded-xl p-8 text-center">
                    <div class="bg-green-700 rounded-full mx-auto p-4 inline-block mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <h2 class="text-white text-2xl font-semibold mb-2">University Login</h2>
                    <p class="text-gray-400 mb-6">Manage your university's departments, courses, and student records.</p>
                    <a href="/gov/views/university-login.php" class="inline-block bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors w-full">
                        Login as University Administrator
                    </a>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <p class="text-white">Don't have an account? <a href="/gov/views/register-options.php" class="text-blue-400 hover:text-blue-300">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
