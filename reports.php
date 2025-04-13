<?php
session_start();
include_once 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reports - EduGov Connect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <style>
    .data-card {
      @apply bg-gray-800 p-6 rounded-xl border border-gray-700/50 text-white shadow-md;
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="sidebar w-64 bg-gray-900/80 text-white border-r border-gray-700/50 flex-shrink-0">
      <div class="p-6 border-b border-gray-700/50">
        <div class="flex items-center space-x-2">
          <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-university text-purple-400"></i>
          </div>
          <h1 class="text-xl font-bold">EduGov Connect</h1>
        </div>
        <p class="text-gray-400 text-sm mt-1">Government of India</p>
      </div>
      <nav class="p-4">
        <ul class="space-y-2">
          <li><a href="user_dashboard.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-chart-line w-5"></i><span>Dashboard</span></a></li>
          <li><a href="universities.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-building w-5"></i><span>Universities</span></a></li>
          <li><a href="funding.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-rupee-sign w-5"></i><span>Funding</span></a></li>
          <li><a href="research.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-flask w-5"></i><span>Research</span></a></li>
          <li><a href="reports.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-file-alt w-5"></i><span>Reports</span></a></li>
          <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto p-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Reports</h1>
        <button class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm"><i class="fas fa-download mr-2"></i>Generate Report</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Report Cards -->
        <?php
          $reports = [
            [
              'title' => 'Annual University Performance 2024',
              'date' => '2024-03-15',
              'type' => 'Performance',
              'size' => '2.5 MB',
              'status' => 'Available'
            ],
            [
              'title' => 'Funding Allocation Report 2023-2024',
              'date' => '2024-04-01',
              'type' => 'Financial',
              'size' => '1.8 MB',
              'status' => 'Available'
            ],
            [
              'title' => 'Research Progress Report Q1 2025',
              'date' => '2025-04-10',
              'type' => 'Research',
              'size' => '3.0 MB',
              'status' => 'In Progress'
            ],
            [
              'title' => 'Student Enrollment Statistics 2024',
              'date' => '2024-06-20',
              'type' => 'Statistical',
              'size' => '2.0 MB',
              'status' => 'Available'
            ],
            [
              'title' => 'Faculty Development Report 2023',
              'date' => '2023-12-10',
              'type' => 'Development',
              'size' => '1.5 MB',
              'status' => 'Available'
            ],
            [
              'title' => 'Infrastructure Audit 2024-2025',
              'date' => '2025-01-05',
              'type' => 'Audit',
              'size' => '2.2 MB',
              'status' => 'Pending'
            ],
          ];

          foreach ($reports as $report) {
            echo '
              <div class="data-card">
                <h2 class="text-xl font-semibold mb-2">' . $report['title'] . '</h2>
                <p><strong>Date:</strong> ' . $report['date'] . '</p>
                <p><strong>Type:</strong> ' . $report['type'] . '</p>
                <p><strong>Size:</strong> ' . $report['size'] . '</p>
                <p><strong>Status:</strong> <span class="px-2 py-1 rounded ' . 
                  ($report['status'] === 'Available' ? 'bg-green-600' : 
                   ($report['status'] === 'In Progress' ? 'bg-yellow-600' : 'bg-red-600')) . '">' . $report['status'] . '</span></p>
                <a href="#" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                  Download
                </a>
              </div>
            ';
          }
        ?>
      </div>
    </div>
  </div>
</body>
</html>