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
    // Fetch all courses for this university
    $stmt = $db->prepare("
        SELECT c.*, d.name as department_name,
        (SELECT COUNT(*) FROM students s WHERE s.course_id = c.id) as student_count
        FROM courses c
        LEFT JOIN departments d ON c.department_id = d.id
        WHERE d.university_id = ?
        ORDER BY d.name, c.name
    ");
    $stmt->execute([$university_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch departments for dropdown
    $stmt = $db->prepare("SELECT id, name FROM departments WHERE university_id = ? ORDER BY name");
    $stmt->execute([$university_id]);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get department-wise course counts for chart
    $stmt = $db->prepare("
        SELECT d.name, COUNT(c.id) as course_count
        FROM departments d
        LEFT JOIN courses c ON d.id = c.department_id
        WHERE d.university_id = ?
        GROUP BY d.id, d.name
        ORDER BY course_count DESC
    ");
    $stmt->execute([$university_id]);
    $deptCourseCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $deptLabels = [];
    $courseCounts = [];
    
    foreach ($deptCourseCounts as $dept) {
        $deptLabels[] = $dept['name'];
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
    <title>Courses - <?php echo htmlspecialchars($university_name); ?></title>
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
                
                <a href="/gov/views/university/departments.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">📁</span>
                    <span>Departments</span>
                </a>
                
                <a href="/gov/views/university/courses.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
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
                    <h1 class="text-3xl font-bold">Courses</h1>
                    <p class="text-gray-400"><?php echo htmlspecialchars($university_name); ?></p>
                </div>
                <a href="/gov/views/university/add-course.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Course
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                    Error: <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Course List -->
                <div class="section-card lg:col-span-2">
                    <h2 class="text-xl font-bold mb-4">Course List</h2>
                    
                    <?php if (empty($courses)): ?>
                        <div class="text-center py-6 text-gray-400">
                            <p>No courses found. Add courses to get started.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-800 text-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 text-left">Course Name</th>
                                        <th class="py-3 px-4 text-left">Code</th>
                                        <th class="py-3 px-4 text-left">Department</th>
                                        <th class="py-3 px-4 text-left">Duration</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                    <tr class="border-t border-gray-700">
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($course['name']); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($course['code']); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($course['department_name'] ?? 'N/A'); ?></td>
                                        <td class="py-3 px-4"><?php echo htmlspecialchars($course['duration'] ?? 'N/A'); ?></td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <a href="/gov/views/university/edit-course.php?id=<?php echo $course['id']; ?>" class="text-blue-400 hover:text-blue-300">Edit</a>
                                                <a href="/gov/controllers/university/delete-course.php?id=<?php echo $course['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Course Distribution Chart -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Courses by Department</h2>
                    <div class="h-64">
                        <canvas id="course-chart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <h3 class="font-medium mb-2">Total Courses</h3>
                        <p class="text-2xl font-bold"><?php echo count($courses); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Course Categories -->
            <?php if (!empty($deptCourseCounts)): ?>
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Department Course Distribution</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($deptCourseCounts as $dept): ?>
                            <div class="bg-gray-800 p-4 rounded-lg text-center">
                                <h3 class="font-bold"><?php echo htmlspecialchars($dept['name']); ?></h3>
                                <div class="text-3xl font-bold mt-2"><?php echo $dept['course_count']; ?></div>
                                <div class="text-xs text-gray-400 mt-1">courses</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        <?php if (!empty($deptLabels)): ?>
        // Department-wise Course Distribution Chart
        const courseCtx = document.getElementById('course-chart').getContext('2d');
        new Chart(courseCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($deptLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($courseCounts); ?>,
                    backgroundColor: [
                        '#3B82F6', '#EC4899', '#10B981', '#F59E0B', 
                        '#6366F1', '#0EA5E9', '#8B5CF6', '#EF4444'
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
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
