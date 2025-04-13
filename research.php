<?php
session_start();
include_once 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Research - EduGov Connect</title>
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
          <li><a href="research.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-flask w-5"></i><span>Research</span></a></li>
          <li><a href="reports.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-file-alt w-5"></i><span>Reports</span></a></li>
          <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto p-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Research Projects</h1>
        <button class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm"><i class="fas fa-plus mr-2"></i>Add Research Project</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Research Cards -->
        <?php
          $researchProjects = [
            [
              'title' => 'AI in Education',
              'university' => 'IIT Bombay',
              'lead' => 'Dr. A. Sharma',
              'funding' => '₹20 Crore',
              'status' => 'Ongoing',
              'start_date' => '2024-01-15',
              'end_date' => '2026-01-14'
            ],
            [
              'title' => 'Renewable Energy Solutions',
              'university' => 'Delhi University',
              'lead' => 'Prof. R. Patel',
              'funding' => '₹15 Crore',
              'status' => 'Completed',
              'start_date' => '2023-06-01',
              'end_date' => '2024-05-31'
            ],
            [
              'title' => 'Climate Change Impact',
              'university' => 'University of Calcutta',
              'lead' => 'Dr. S. Banerjee',
              'funding' => '₹10 Crore',
              'status' => 'Pending Approval',
              'start_date' => '2025-03-01',
              'end_date' => '2027-02-28'
            ],
            [
              'title' => 'Biotechnology Advances',
              'university' => 'Manipal Academy',
              'lead' => 'Prof. K. Rao',
              'funding' => '₹25 Crore',
              'status' => 'Ongoing',
              'start_date' => '2024-04-10',
              'end_date' => '2026-04-09'
            ],
            [
              'title' => 'Public Health Studies',
              'university' => 'Anna University',
              'lead' => 'Dr. M. Singh',
              'funding' => '₹12 Crore',
              'status' => 'In Progress',
              'start_date' => '2024-02-01',
              'end_date' => '2025-01-31'
            ],
            [
              'title' => 'Quantum Computing',
              'university' => 'Jawaharlal Nehru University',
              'lead' => 'Prof. V. Gupta',
              'funding' => '₹30 Crore',
              'status' => 'Planned',
              'start_date' => '2025-07-01',
              'end_date' => '2027-06-30'
            ],
          ];

          foreach ($researchProjects as $project) {
            echo '
              <div class="data-card">
                <h2 class="text-xl font-semibold mb-2">' . $project['title'] . '</h2>
                <p><strong>University:</strong> ' . $project['university'] . '</p>
                <p><strong>Lead Researcher:</strong> ' . $project['lead'] . '</p>
                <p><strong>Funding:</strong> ' . $project['funding'] . '</p>
                <p><strong>Status:</strong> <span class="px-2 py-1 rounded ' . 
                  ($project['status'] === 'Ongoing' ? 'bg-yellow-600' : 
                   ($project['status'] === 'Completed' ? 'bg-blue-600' : 
                   ($project['status'] === 'Pending Approval' ? 'bg-red-600' : 'bg-green-600'))) . '">' . $project['status'] . '</span></p>
                <p><strong>Duration:</strong> ' . $project['start_date'] . ' - ' . $project['end_date'] . '</p>
                <a href="#" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                  View Details
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