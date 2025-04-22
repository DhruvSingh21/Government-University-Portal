<?php
session_start();
require_once '../../config.php';

// Check if user is logged in as a government official
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'government_official') {
    header('Location: /gov/views/government-login.php');
    exit;
}

try {
    // Fetch all universities with more details
    $stmt = $db->prepare("
        SELECT u.*, 
            (SELECT COUNT(*) FROM departments d WHERE d.university_id = u.id) as total_departments,
            (SELECT COUNT(*) FROM students s WHERE s.university_id = u.id) as total_students
        FROM universities u 
        ORDER BY u.name
    ");
    $stmt->execute();
    $universities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universities - EduGov Connect</title>
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
                
                <a href="/gov/views/government/universities.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">🏛️</span>
                    <span>Universities</span>
                </a>
                
                <a href="/gov/views/government/reports.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
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
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Universities</h1>
                    <p class="text-gray-400">Manage and monitor all registered universities</p>
                </div>
                <div>
                    <button onclick="downloadReport()" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                        Download Report
                    </button>
                </div>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                Error: <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <!-- Universities List -->
            <div class="section-card mb-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 text-gray-300">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 text-left">Name</th>
                                <th class="py-3 px-4 text-left">Email</th>
                                <th class="py-3 px-4 text-left">Address</th>
                                <th class="py-3 px-4 text-left">Departments</th>
                                <th class="py-3 px-4 text-left">Students</th>
                                <th class="py-3 px-4 text-left">Registration Date</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($universities)): ?>
                                <?php foreach ($universities as $university): ?>
                                <tr class="border-t border-gray-700">
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['name']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['email']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['address'] ?? 'N/A'); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['total_departments']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['total_students']); ?></td>
                                    <td class="py-3 px-4"><?php echo date('M d, Y', strtotime($university['created_at'])); ?></td>
                                    <td class="py-3 px-4">
                                        <a href="/gov/views/government/university-details.php?id=<?php echo $university['id']; ?>" 
                                           class="text-blue-400 hover:text-blue-300">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="py-3 px-4 text-center">No universities found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Download report function
        function downloadReport() {
            window.location.href = '/gov/api/government/generate-report.php?type=universities&format=csv';
        }
    </script>
</body>
</html>
