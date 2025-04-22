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
    // Fetch all students for this university
    $stmt = $db->prepare("
        SELECT s.*, d.name as department_name, c.name as course_name 
        FROM students s
        LEFT JOIN departments d ON s.department_id = d.id
        LEFT JOIN courses c ON s.course_id = c.id
        WHERE s.university_id = ?
        ORDER BY s.last_name, s.first_name
    ");
    $stmt->execute([$university_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get gender distribution
    $stmt = $db->prepare("SELECT gender, COUNT(*) as count FROM students WHERE university_id = ? GROUP BY gender");
    $stmt->execute([$university_id]);
    $genderData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format gender data for chart
    $genderLabels = [];
    $genderCounts = [];
    foreach ($genderData as $data) {
        $genderLabels[] = ucfirst($data['gender']);
        $genderCounts[] = (int)$data['count'];
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
    <title>Students - <?php echo htmlspecialchars($university_name); ?></title>
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
                
                <a href="/gov/views/university/students.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">👤</span>
                    <span>Students</span>
                </a>
                
                <a href="/gov/views/university/departments.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
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
                    <h1 class="text-3xl font-bold">Students</h1>
                    <p class="text-gray-400"><?php echo htmlspecialchars($university_name); ?></p>
                </div>
                <a href="/gov/views/university/add-student.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Student
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                    Error: <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div class="section-card lg:col-span-3">
                    <h2 class="text-xl font-bold mb-4">Students List</h2>
                    
                    <?php if (empty($students)): ?>
                        <div class="text-center py-6 text-gray-400">
                            <p>No students found. Add students to get started.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-800 text-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 text-left">Name</th>
                                        <th class="py-3 px-4 text-left">Email</th>
                                        <th class="py-3 px-4 text-left">Department</th>
                                        <th class="py-3 px-4 text-left">Course</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                    <tr class="border-t border-gray-700">
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($student['department_name'] ?? 'N/A'); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($student['course_name'] ?? 'N/A'); ?></td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="/gov/views/university/edit-student.php?id=<?php echo $student['id']; ?>" class="text-blue-400 hover:text-blue-300">Edit</a>
                                                <a href="/gov/controllers/university/delete-student.php?id=<?php echo $student['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Analytics Card -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Gender Distribution</h2>
                    <div class="h-64">
                        <canvas id="gender-chart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <h3 class="font-medium mb-2">Total Students</h3>
                        <p class="text-2xl font-bold"><?php echo count($students); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gender Distribution Chart
        <?php if (!empty($genderLabels)): ?>
        const genderCtx = document.getElementById('gender-chart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($genderLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($genderCounts); ?>,
                    backgroundColor: ['#3b82f6', '#ec4899', '#10b981'],
                    borderWidth: 0
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
        <?php endif; ?>
    </script>
</body>
</html>
