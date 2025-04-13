<?php
session_start();
include_once 'includes/config.php';

// Redirect to login if not logged in


// Handle student addition (basic example)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $year = $_POST['year'];

    $stmt = $conn->prepare("INSERT INTO students (name, email, department, year, university_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $email, $department, $year, $_SESSION['university_id']);
    $stmt->execute();
    $stmt->close();
}

// Fetch students for this university

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Students - EduGov Connect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <style>
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .stats-card { @apply bg-white/5 backdrop-blur-sm hover:bg-white/10 transition-all; }
    .data-table th { @apply px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider; }
    .data-table td { @apply px-6 py-4 whitespace-nowrap text-sm text-gray-200; }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-hidden">
  <div class="fixed inset-0 opacity-10 pointer-events-none">
    <div class="animate-float absolute w-64 h-64 bg-gradient-to-r from-purple-400/20 to-blue-500/20 rounded-full blur-3xl -top-32 -left-32"></div>
    <div class="animate-float absolute w-96 h-96 bg-gradient-to-r from-blue-500/20 to-indigo-400/20 rounded-full blur-3xl -bottom-48 -right-48" style="animation-delay: -3s"></div>
  </div>

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
        <p class="text-gray-400 text-sm">Mumbai University</p>
      </div>
      <nav class="p-4">
        <ul class="space-y-2">
          <li><a href="dashboard.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span></a></li>
          <li><a href="student.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-users w-5"></i><span>Students</span></a></li>
          <li><a href="departments.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-building w-5"></i><span>Departments</span></a></li>
          <li><a href="courses.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-book-open w-5"></i><span>Courses</span></a></li>
          
          <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
      <header class="bg-gray-900/80 border-b border-gray-700/50 px-8 py-4 flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-white">Student Management</h1>
          <p class="text-gray-400">Government of India - Mumbai University</p>
        </div>
        <div class="flex items-center gap-4">
          <button class="p-2 hover:bg-white/5 rounded-full"><i class="fas fa-bell text-gray-400"></i></button>
          <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center"><i class="fas fa-user text-purple-400"></i></div>
        </div>
      </header>

      <main class="p-8 space-y-8">
        <!-- Add Student Form -->
        <div class="bg-gray-900/80 p-6 rounded-xl border border-gray-700/50">
          <h2 class="text-white text-xl font-semibold mb-4">Add New Student</h2>
          <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="Full Name" required class="bg-gray-800 text-white p-2 rounded-lg" />
            <input type="email" name="email" placeholder="Email" required class="bg-gray-800 text-white p-2 rounded-lg" />
            <input type="text" name="department" placeholder="Department" required class="bg-gray-800 text-white p-2 rounded-lg" />
            <input type="text" name="year" placeholder="Year" required class="bg-gray-800 text-white p-2 rounded-lg" />
            <button type="submit" name="add_student" class="col-span-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg">Add Student</button>
          </form>
        </div>

        <!-- Student Table -->
        <div class="bg-gray-900/80 p-6 rounded-xl border border-gray-700/50 overflow-x-auto">
          <h2 class="text-white text-xl font-semibold mb-4">Student List</h2>
          <table class="min-w-full data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Year</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        
      </main>
    </div>
  </div>
</body>
</html>
