<?php
session_start();
if (!isset($_SESSION['university_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'university_admin') {
    header('Location: /gov/views/university-login.php');
    exit;
}

require_once '../../config.php';

$university_id = $_SESSION['university_id'];
$university_name = $_SESSION['university_name'] ?? 'University';

try {
    // Fetch all departments for this university
    $stmt = $db->prepare("
        SELECT d.*, 
        (SELECT COUNT(*) FROM students s WHERE s.department_id = d.id) as student_count,
        (SELECT COUNT(*) FROM courses c WHERE c.department_id = d.id) as course_count
        FROM departments d
        WHERE d.university_id = ?
        ORDER BY d.name
    ");
    $stmt->execute([$university_id]);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get department statistics for chart
    $deptNames = [];
    $studentCounts = [];
    $courseCounts = [];
    
    foreach ($departments as $dept) {
        $deptNames[] = $dept['name'];
        $studentCounts[] = (int)$dept['student_count'];
        $courseCounts[] = (int)$dept['course_count'];
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - <?php echo htmlspecialchars($university_name); ?></title>
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
        .sidebar {
            background-color: #192656;
        }
    </style>
</head>
<body class="text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 p-6 flex flex-col">
            <div class="mb-10">
                <a href="/gov" class="flex items-center">
                    <span class="text-xl font-bold">EduGov Connect</span>
                </a>
            </div>
            
            <nav class="flex-1">
                <a href="/gov/views/university/dashboard.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">☰</span>
                    <span>Dashboard</span>
                </a>
                
                <a href="/gov/views/university/students.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">👤</span>
                    <span>Students</span>
                </a>
                
                <a href="/gov/views/university/departments.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">📁</span>
                    <span>Departments</span>
                </a>
                
                <a href="/gov/views/university/courses.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">📚</span>
                    <span>Courses</span>
                </a>
                
                <a href="/gov/controllers/auth/logout.php" class="flex items-center py-3 px-4 hover:bg-red-800 rounded-md mt-auto text-red-300">
                    <span class="mr-2">🚪</span>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Departments</h1>
                    <p class="text-gray-400"><?php echo htmlspecialchars($university_name); ?></p>
                </div>
                <a href="/gov/views/university/add-department.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Department
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                    Error: <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Departments List and Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Departments Table -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Department List</h2>
                    
                    <?php if (empty($departments)): ?>
                        <div class="text-center py-6 text-gray-400">
                            <p>No departments found. Add departments to get started.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-800 text-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 text-left">Name</th>
                                        <th class="py-3 px-4 text-left">Students</th>
                                        <th class="py-3 px-4 text-left">Courses</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($departments as $dept): ?>
                                    <tr class="border-t border-gray-700">
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($dept['name']); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($dept['student_count']); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($dept['course_count']); ?></td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="/gov/views/university/edit-department.php?id=<?php echo $dept['id']; ?>" class="text-blue-400 hover:text-blue-300">Edit</a>
                                                <a href="/gov/controllers/university/delete-department.php?id=<?php echo $dept['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this department?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Department Statistics Chart -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Department Statistics</h2>
                    <div class="h-64">
                        <canvas id="dept-chart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <h3 class="font-medium mb-2">Total Departments</h3>
                        <p class="text-2xl font-bold"><?php echo count($departments); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Department details -->
            <div class="section-card">
                <h2 class="text-xl font-bold mb-4">Department Details</h2>
                
                <?php if (empty($departments)): ?>
                    <div class="text-center py-6 text-gray-400">
                        <p>No departments found.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($departments as $dept): ?>
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($dept['name']); ?></h3>
                                <div class="mb-2 text-sm text-gray-400">
                                    <?php echo htmlspecialchars($dept['description'] ?? 'No description available'); ?>
                                </div>
                                <div class="flex justify-between mt-4 pt-4 border-t border-gray-700 text-sm">
                                    <div>
                                        <span class="text-gray-400">Students:</span> 
                                        <span class="font-medium"><?php echo $dept['student_count']; ?></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400">Courses:</span> 
                                        <span class="font-medium"><?php echo $dept['course_count']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        <?php if (!empty($deptNames)): ?>
        // Department Statistics Chart
        const deptCtx = document.getElementById('dept-chart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($deptNames); ?>,
                datasets: [
                    {
                        label: 'Students',
                        data: <?php echo json_encode($studentCounts); ?>,
                        backgroundColor: '#3b82f6'
                    },
                    {
                        label: 'Courses',
                        data: <?php echo json_encode($courseCounts); ?>,
                        backgroundColor: '#10b981'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#fff',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#CBD5E0'
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
        <?php endif; ?>
    </script>
</body>
</html>
