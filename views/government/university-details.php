<?php
session_start();
require_once '../../config.php';

// Check if user is logged in as a government official
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'government_official') {
    header('Location: /gov/views/government-login.php');
    exit;
}

// Get university ID from query string
$university_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$university_id) {
    header('Location: /gov/views/government/universities.php');
    exit;
}

try {
    // Fetch university details
    $stmt = $db->prepare("
        SELECT * FROM universities WHERE id = ?
    ");
    $stmt->execute([$university_id]);
    $university = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$university) {
        throw new Exception('University not found');
    }
    
    // Fetch departments
    $stmt = $db->prepare("
        SELECT d.*, COUNT(s.id) as student_count
        FROM departments d
        LEFT JOIN students s ON d.id = s.department_id
        WHERE d.university_id = ?
        GROUP BY d.id
        ORDER BY d.name
    ");
    $stmt->execute([$university_id]);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch courses
    $stmt = $db->prepare("
        SELECT c.*, d.name as department_name
        FROM courses c
        JOIN departments d ON c.department_id = d.id
        WHERE d.university_id = ?
        ORDER BY d.name, c.name
    ");
    $stmt->execute([$university_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total students
    $stmt = $db->prepare("SELECT COUNT(*) FROM students WHERE university_id = ?");
    $stmt->execute([$university_id]);
    $totalStudents = $stmt->fetchColumn();
    
    // Get gender distribution
    $stmt = $db->prepare("
        SELECT gender, COUNT(*) as count 
        FROM students 
        WHERE university_id = ? 
        GROUP BY gender
    ");
    $stmt->execute([$university_id]);
    $genderData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $genderDistribution = [0, 0, 0]; // [Male, Female, Other]
    foreach ($genderData as $data) {
        switch(strtolower($data['gender'])) {
            case 'male': $genderDistribution[0] = (int)$data['count']; break;
            case 'female': $genderDistribution[1] = (int)$data['count']; break;
            default: $genderDistribution[2] += (int)$data['count'];
        }
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
    <title>University Details - EduGov Connect</title>
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
            <!-- Header with back button -->
            <div class="mb-8">
                <div class="flex items-center mb-2">
                    <a href="/gov/views/government/universities.php" class="text-blue-400 hover:text-blue-300 mr-3">
                        &larr; Back to Universities
                    </a>
                </div>
                <?php if (isset($university)): ?>
                    <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($university['name']); ?></h1>
                    <p class="text-gray-400">University Details</p>
                <?php endif; ?>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                    Error: <?php echo htmlspecialchars($error); ?>
                </div>
            <?php elseif (isset($university)): ?>
                
                <!-- University Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="section-card">
                        <h2 class="text-xl font-bold mb-4">University Information</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-400">Name</p>
                                <p><?php echo htmlspecialchars($university['name']); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Email</p>
                                <p><?php echo htmlspecialchars($university['email']); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Address</p>
                                <p><?php echo htmlspecialchars($university['address'] ?? 'Not provided'); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Phone</p>
                                <p><?php echo htmlspecialchars($university['phone'] ?? 'Not provided'); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Registration Date</p>
                                <p><?php echo date('F j, Y', strtotime($university['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-card">
                        <h2 class="text-xl font-bold mb-4">Summary</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-900 bg-opacity-50 p-4 rounded-lg">
                                <p class="text-lg text-gray-300">Total Departments</p>
                                <p class="text-3xl font-bold"><?php echo count($departments); ?></p>
                            </div>
                            <div class="bg-blue-900 bg-opacity-50 p-4 rounded-lg">
                                <p class="text-lg text-gray-300">Total Students</p>
                                <p class="text-3xl font-bold"><?php echo $totalStudents; ?></p>
                            </div>
                            <div class="bg-blue-900 bg-opacity-50 p-4 rounded-lg">
                                <p class="text-lg text-gray-300">Total Courses</p>
                                <p class="text-3xl font-bold"><?php echo count($courses); ?></p>
                            </div>
                            <div class="bg-blue-900 bg-opacity-50 p-4 rounded-lg">
                                <p class="text-lg text-gray-300">Created</p>
                                <p class="text-xl font-bold"><?php echo date('Y', strtotime($university['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gender Distribution and Departments -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="section-card">
                        <h2 class="text-xl font-bold mb-4">Student Gender Distribution</h2>
                        <div class="h-64">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="section-card">
                        <h2 class="text-xl font-bold mb-4">Department Statistics</h2>
                        <div class="space-y-4">
                            <?php foreach($departments as $dept): ?>
                                <div>
                                    <div class="flex justify-between items-center">
                                        <span><?php echo htmlspecialchars($dept['name']); ?></span>
                                        <span class="font-medium"><?php echo $dept['student_count']; ?> students</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 mt-1">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo ($totalStudents > 0) ? ($dept['student_count'] / $totalStudents * 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Courses Table -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Courses (<?php echo count($courses); ?>)</h2>
                    <?php if (count($courses) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-gray-800 text-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 text-left">Department</th>
                                        <th class="py-2 px-4 text-left">Course Name</th>
                                        <th class="py-2 px-4 text-left">Code</th>
                                        <th class="py-2 px-4 text-left">Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                    <tr class="border-t border-gray-700">
                                        <td class="py-2 px-4"><?php echo htmlspecialchars($course['department_name']); ?></td>
                                        <td class="py-2 px-4"><?php echo htmlspecialchars($course['name']); ?></td>
                                        <td class="py-2 px-4"><?php echo htmlspecialchars($course['code']); ?></td>
                                        <td class="py-2 px-4"><?php echo htmlspecialchars($course['duration'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400">No courses available for this university.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        <?php if (isset($genderDistribution)): ?>
        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: <?php echo json_encode($genderDistribution); ?>,
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
