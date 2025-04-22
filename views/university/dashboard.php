<?php
session_start();
if (!isset($_SESSION['university_id'])) {
    header('Location: /gov/views/university-login.php');
    exit;
}

// Add additional security check to prevent cross-university access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'university_admin') {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['error'] = 'Unauthorized access';
    header('Location: /gov/views/university-login.php');
    exit;
}

// Get university name directly from session with fallback
$university_name = $_SESSION['university_name'] ?? 'University Dashboard';
$university_id = $_SESSION['university_id'] ?? '0';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Dashboard - EduGov Connect</title>
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
        .loading-bar {
            height: 1rem;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
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
                <a href="/gov/views/university/dashboard.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
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
                
                <a href="/gov/views/university/courses.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">📚</span>
                    <span>Courses</span>
                </a>

                <a href="/gov/controllers/auth/logout.php" class="flex items-center py-3 px-4 hover:bg-red-800 rounded-md mt-auto text-red-300">
                <span class="mr-2">🚪</span>
                <span>Logout</span>
            </a>
            </nav>
            
            <!-- Add logout link to sidebar -->
            
        </div>
        
        <!-- Main content -->
        <div class="flex-1 p-8">
            <!-- Header - Show university identity clearly -->
            <div class="mb-8">
                <h1 id="university-name" class="text-3xl font-bold">Welcome, <?php echo htmlspecialchars($university_name); ?></h1>
                <p class="text-gray-400">
                    University Dashboard 
                </p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Students -->
                <div class="section-card">
                    <h2 class="text-lg text-gray-400">Total Students</h2>
                    <h3 id="total-students" class="text-3xl font-bold">Loading...</h3>
                    <a href="/gov/views/university/students.php" class="text-blue-400 text-sm inline-block mt-2">View details →</a>
                </div>
                
                <!-- Departments -->
                <div class="section-card">
                    <h2 class="text-lg text-gray-400">Departments</h2>
                    <h3 id="total-departments" class="text-3xl font-bold">Loading...</h3>
                    <a href="/gov/views/university/departments.php" class="text-blue-400 text-sm inline-block mt-2">View details →</a>
                </div>
                
                <!-- Courses -->
                <div class="section-card">
                    <h2 class="text-lg text-gray-400">Courses</h2>
                    <h3 id="total-courses" class="text-3xl font-bold">Loading...</h3>
                    <a href="/gov/views/university/courses.php" class="text-blue-400 text-sm inline-block mt-2">View details →</a>
                </div>
            </div>
            
            <!-- Recent Updates -->
            <div class="section-card mb-8">
                <h2 class="text-xl font-bold mb-4">Recent Updates</h2>
                <div id="recent-updates">
                    <div class="loading-bar w-full"></div>
                    <div class="loading-bar w-3/4"></div>
                    <div class="loading-bar w-full"></div>
                </div>
            </div>
            
            <!-- Charts and Department Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gender Distribution Chart -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Student Gender Distribution</h2>
                    <div class="h-64">
                        <canvas id="gender-chart"></canvas>
                    </div>
                </div>
                
                <!-- Department-wise Students -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Department-wise Students</h2>
                    <div id="department-stats">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Function to format date for recent updates
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Render gender distribution chart
    function renderGenderChart(data) {
        const ctx = document.getElementById('gender-chart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: data,
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
    }
    
    // Load dashboard data
    async function loadDashboardData() {
        try {
            document.getElementById('total-students').textContent = 'Loading...';
            document.getElementById('total-departments').textContent = 'Loading...';
            document.getElementById('total-courses').textContent = 'Loading...';
            
            // Add timeout to prevent hanging requests
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);
            
            const response = await fetch('/gov/api/university/dashboard-stats.php', {
                signal: controller.signal,
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            
            const responseText = await response.text();
            console.log('Raw API response:', responseText);
            
            // Try to parse as JSON safely
            let data;
            try {
                data = JSON.parse(responseText);
                console.log('Dashboard data:', data);
            } catch (parseError) {
                console.error('JSON parse error:', parseError, 'Raw data:', responseText);
                throw new Error('Invalid JSON response from server');
            }
            
            // Check for error in response
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Update statistics
            document.getElementById('total-students').textContent = data.totalStudents || '0';
            document.getElementById('total-departments').textContent = data.totalDepartments || '0';
            document.getElementById('total-courses').textContent = data.totalCourses || '0';
            
            // Render gender chart if data exists
            if (data.genderDistribution && data.genderDistribution.length === 3) {
                renderGenderChart(data.genderDistribution);
            } else {
                renderGenderChart([0, 0, 0]);
            }
            
            // Render department stats
            const deptStatsEl = document.getElementById('department-stats');
            deptStatsEl.innerHTML = '';
            
            if (data.departments && data.departments.length > 0) {
                data.departments.forEach(dept => {
                    const maxCount = Math.max(...data.departments.map(d => d.students));
                    const percentWidth = maxCount > 0 ? (dept.students / maxCount * 100) : 0;
                    
                    const deptEl = document.createElement('div');
                    deptEl.className = 'mb-4';
                    deptEl.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <span>${dept.name}</span>
                            <span class="font-medium">${dept.students} students</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: ${percentWidth}%"></div>
                        </div>
                    `;
                    deptStatsEl.appendChild(deptEl);
                });
            } else {
                deptStatsEl.innerHTML = '<p class="text-gray-400">No departments found</p>';
            }
            
            // Render recent updates
            const updatesEl = document.getElementById('recent-updates');
            updatesEl.innerHTML = '';
            
            if (data.recentUpdates && data.recentUpdates.length > 0) {
                data.recentUpdates.forEach(update => {
                    const updateEl = document.createElement('div');
                    updateEl.className = 'mb-3 pb-3 border-b border-gray-700';
                    updateEl.innerHTML = `
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">${update.action_type}</p>
                                <p class="text-sm text-gray-400">${update.description}</p>
                            </div>
                            <span class="text-xs text-gray-500">${formatDate(update.created_at)}</span>
                        </div>
                    `;
                    updatesEl.appendChild(updateEl);
                });
            } else {
                updatesEl.innerHTML = '<p class="text-gray-400">No recent updates</p>';
            }
            
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            document.getElementById('total-students').textContent = 'Error';
            document.getElementById('total-departments').textContent = 'Error';
            document.getElementById('total-courses').textContent = 'Error';
            document.getElementById('recent-updates').innerHTML = 
                '<p class="text-red-400">Failed to load updates: ' + error.message + '</p>';
            document.getElementById('department-stats').innerHTML = 
                '<p class="text-red-400">Failed to load department statistics</p>';
        }
    }
    
    // Retry loading if initial load fails
    async function loadWithRetry() {
        let attempts = 0;
        const maxAttempts = 2;
        
        while (attempts <= maxAttempts) {
            try {
                await loadDashboardData();
                break; // Success, exit loop
            } catch (error) {
                attempts++;
                if (attempts > maxAttempts) {
                    console.error('Maximum retry attempts reached');
                    break;
                }
                console.log(`Retrying... Attempt ${attempts} of ${maxAttempts}`);
                await new Promise(resolve => setTimeout(resolve, 1000)); // Wait 1 second before retry
            }
        }
    }
    
    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadWithRetry);
    </script>
</body>
</html>