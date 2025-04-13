<?php
session_start();
include_once 'includes/config.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Universities - EduGov Connect</title>
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
          <li><a href="universities.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-building w-5"></i><span>Universities</span></a></li>
          
          <li><a href="funding.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-rupee-sign w-5"></i><span>Funding</span></a></li>
          <li><a href="research.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-flask w-5"></i><span>Research</span></a></li>
          <li><a href="reports.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-file-alt w-5"></i><span>Reports</span></a></li>
          <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto p-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Universities</h1>
        <button class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm"><i class="fas fa-plus mr-2"></i>Add University</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- University Cards -->
        <?php
          $universities = [
            [
              'name' => 'Delhi University',
              'location' => 'New Delhi, India',
              'est' => '1922',
              'students' => '1,800',
              'faculty' => 'All Types',
              'accreditation' => 'NAAC A+',
              'courses' => 'Science, Arts, Commerce',
              'website' => 'https://www.du.ac.in'
            ],
            [
              'name' => 'IIT Bombay',
              'location' => 'Mumbai, Maharashtra',
              'est' => '1958',
              'students' => '10,000+',
              'faculty' => 'STEM Focused',
              'accreditation' => 'AICTE Approved',
              'courses' => 'Engineering, Research',
              'website' => 'https://www.iitb.ac.in'
            ],
            [
              'name' => 'University of Calcutta',
              'location' => 'Kolkata, West Bengal',
              'est' => '1857',
              'students' => '18,000+',
              'faculty' => 'Arts, Science, Law',
              'accreditation' => 'UGC, NAAC A',
              'courses' => 'All Streams',
              'website' => 'https://www.caluniv.ac.in'
            ],
            [
              'name' => 'Anna University',
              'location' => 'Chennai, Tamil Nadu',
              'est' => '1978',
              'students' => '15,000+',
              'faculty' => 'Engineering, Tech',
              'accreditation' => 'NAAC A++',
              'courses' => 'Engineering, Architecture',
              'website' => 'https://www.annauniv.edu'
            ],
            [
              'name' => 'Manipal Academy',
              'location' => 'Manipal, Karnataka',
              'est' => '1953',
              'students' => '25,000+',
              'faculty' => 'Private',
              'accreditation' => 'UGC, MHRD',
              'courses' => 'Medical, Tech, Arts',
              'website' => 'https://manipal.edu'
            ],
            [
              'name' => 'Jawaharlal Nehru University',
              'location' => 'New Delhi, India',
              'est' => '1969',
              'students' => '8,000+',
              'faculty' => 'Liberal Arts, Science',
              'accreditation' => 'NAAC A++',
              'courses' => 'Social Sciences, Sciences',
              'website' => 'https://www.jnu.ac.in'
            ],
          ];

          foreach ($universities as $uni) {
            echo '
              <div class="data-card">
                <h2 class="text-xl font-semibold mb-2">'.$uni['name'].'</h2>
                <p><strong>Location:</strong> '.$uni['location'].'</p>
                <p><strong>Established:</strong> '.$uni['est'].'</p>
                <p><strong>Students:</strong> '.$uni['students'].'</p>
                <p><strong>Faculty:</strong> '.$uni['faculty'].'</p>
                <p><strong>Accreditation:</strong> '.$uni['accreditation'].'</p>
                <p><strong>Courses:</strong> '.$uni['courses'].'</p>
                <a href="'.$uni['website'].'" target="_blank" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                  Visit Website
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
