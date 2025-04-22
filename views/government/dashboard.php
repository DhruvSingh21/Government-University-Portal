<?php
session_start();
require_once '../../config.php';

// Check if user is logged in as a government official
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'government_official') {
    header('Location: /gov/views/government-login.php');
    exit;
}

try {
    // Fetch university statistics
    $stmt = $db->prepare("SELECT COUNT(*) as total_universities FROM universities");
    $stmt->execute();
    $universityStats = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch student distribution by department
    $stmt = $db->prepare("
        SELECT d.name as department, COUNT(s.id) as student_count 
        FROM departments d
        LEFT JOIN students s ON d.id = s.department_id
        GROUP BY d.id, d.name
        ORDER BY student_count DESC
        LIMIT 10
    ");
    $stmt->execute();
    $studentDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch course analytics by department
    $stmt = $db->prepare("
        SELECT d.name as department, COUNT(c.id) as course_count
        FROM departments d
        LEFT JOIN courses c ON d.id = c.department_id
        GROUP BY d.id, d.name
        ORDER BY course_count DESC
        LIMIT 10
    ");
    $stmt->execute();
    $courseAnalytics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all universities
    $stmt = $db->prepare("SELECT id, name, email, address, created_at FROM universities ORDER BY name");
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
    <title>Government Dashboard - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="/gov/views/government/dashboard.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">☰</span>
                    <span>Dashboard</span>
                </a>
                
                <a href="/gov/views/government/universities.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
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
            <div class="mb-8">
                <h1 class="text-3xl font-bold">Government Dashboard</h1>
                <p class="text-gray-400">Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Official'); ?></p>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                Error: <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Universities -->
                <div class="section-card">
                    <h2 class="text-lg text-gray-400">Total Universities</h2>
                    <h3 class="text-3xl font-bold"><?php echo isset($universityStats['total_universities']) ? $universityStats['total_universities'] : 0; ?></h3>
                    <a href="/gov/views/government/universities.php" class="text-blue-400 text-sm inline-block mt-2">View details →</a>
                </div>
                
                <!-- Total Students -->
                <div class="section-card">
                    <h2 class="text-lg text-gray-400">Total Students</h2>
                    <h3 class="text-3xl font-bold"><?php
                        $totalStudents = 0;
                        foreach ($studentDistribution as $dept) {
                            $totalStudents += (int)$dept['student_count'];
                        }
                        echo $totalStudents;
                    ?></h3>
                </div>
                
                <!-- Total Courses -->
                <div class="section-card">
                    <h2 class="text-lg text-gray-400">Total Courses</h2>
                    <h3 class="text-3xl font-bold"><?php
                        $totalCourses = 0;
                        foreach ($courseAnalytics as $course) {
                            $totalCourses += (int)$course['course_count'];
                        }
                        echo $totalCourses;
                    ?></h3>
                </div>
            </div>
            
            <!-- Charts and University List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Student Distribution Chart -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Student Distribution by Department</h2>
                    <div class="h-64">
                        <canvas id="studentChart"></canvas>
                    </div>
                </div>
                
                <!-- Course Distribution Chart -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Course Analytics</h2>
                    <div class="h-64">
                        <canvas id="courseChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Universities List -->
            <div class="section-card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Registered Universities</h2>
                    <button onclick="downloadReport()" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                        Download Report
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 text-gray-300">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 text-left">Name</th>
                                <th class="py-3 px-4 text-left">Email</th>
                                <th class="py-3 px-4 text-left">Address</th>
                                <th class="py-3 px-4 text-left">Registration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($universities)): ?>
                                <?php foreach ($universities as $university): ?>
                                <tr class="border-t border-gray-700">
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['name']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['email']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($university['address'] ?? 'N/A'); ?></td>
                                    <td class="py-3 px-4"><?php echo date('M d, Y', strtotime($university['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="py-3 px-4 text-center">No universities found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Student Distribution Chart
        const studentCtx = document.getElementById('studentChart').getContext('2d');
        new Chart(studentCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($studentDistribution, 'department')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($studentDistribution, 'student_count')); ?>,
                    backgroundColor: [
                        '#4299E1', '#FC8181', '#68D391', '#F6AD55', '#9F7AEA',
                        '#38B2AC', '#F687B3', '#4FD1C5', '#FBD38D', '#A3BFFA'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#fff',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
        
        // Course Analytics Chart
        const courseCtx = document.getElementById('courseChart').getContext('2d');
        new Chart(courseCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($courseAnalytics, 'department')); ?>,
                datasets: [{
                    label: 'Number of Courses',
                    data: <?php echo json_encode(array_column($courseAnalytics, 'course_count')); ?>,
                    backgroundColor: '#4299E1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#CBD5E0',
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#CBD5E0'
                        },
                        grid: {
                            color: 'rgba(160, 174, 192, 0.1)'
                        }
                    }
                }
            }
        });

        // Download report function
        function downloadReport() {
            window.location.href = '/gov/api/government/generate-report.php?type=universities&format=csv';
        }
    </script>
</body>
</html>