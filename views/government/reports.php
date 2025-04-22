<?php
session_start();
require_once '../../config.php';

// Check if user is logged in as a government official
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'government_official') {
    header('Location: /gov/views/government-login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #1e3a8a;
        }
        .section-card {
            background-color: #192656;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 p-6 flex flex-col bg-gray-900">
            <div class="mb-10">
                <a href="/gov" class="flex items-center">
                    <span class="text-xl font-bold">EduGov Connect</span>
                </a>
            </div>
            
            <nav class="flex-1">
                <a href="/gov/views/government/dashboard.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">☰</span>
                    <span>Dashboard</span>
                </a>
                
                <a href="/gov/views/government/universities.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">🏛️</span>
                    <span>Universities</span>
                </a>
                
                <a href="/gov/views/government/reports.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">📊</span>
                    <span>Reports</span>
                </a>
                
                <a href="/gov/views/government/settings.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">⚙️</span>
                    <span>Settings</span>
                </a>
                
                <a href="/gov/controllers/auth/logout.php" class="flex items-center py-3 px-4 hover:bg-red-800 rounded-md mt-auto">
                    <span class="mr-2">🚪</span>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold">Reports</h1>
                <p class="text-gray-400">Generate and download educational reports</p>
            </div>
            
            <!-- Reports Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Universities Report -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-2">Universities Report</h2>
                    <p class="text-gray-400 mb-4">Complete data on all registered universities</p>
                    <div class="flex space-x-2">
                        <button onclick="downloadReport('universities', 'csv')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            CSV
                        </button>
                        <button onclick="downloadReport('universities', 'json')" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                            JSON
                        </button>
                    </div>
                </div>
                
                <!-- Students Report -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-2">Students Report</h2>
                    <p class="text-gray-400 mb-4">Aggregated student data across all universities</p>
                    <div class="flex space-x-2">
                        <button onclick="downloadReport('students', 'csv')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            CSV
                        </button>
                        <button onclick="downloadReport('students', 'json')" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                            JSON
                        </button>
                    </div>
                </div>
                
                <!-- Courses Report -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-2">Courses Report</h2>
                    <p class="text-gray-400 mb-4">Details of all courses offered nationwide</p>
                    <div class="flex space-x-2">
                        <button onclick="downloadReport('courses', 'csv')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            CSV
                        </button>
                        <button onclick="downloadReport('courses', 'json')" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                            JSON
                        </button>
                    </div>
                </div>
                
                <!-- Department Statistics Report -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-2">Department Statistics</h2>
                    <p class="text-gray-400 mb-4">Analytics on departments across universities</p>
                    <div class="flex space-x-2">
                        <button onclick="downloadReport('departments', 'csv')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            CSV
                        </button>
                        <button onclick="downloadReport('departments', 'json')" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                            JSON
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadReport(type, format) {
            console.log(`Downloading ${type} report in ${format} format...`);
            window.location.href = `/gov/api/government/generate-report.php?type=${type}&format=${format}`;
        }
    </script>
</body>
</html>
