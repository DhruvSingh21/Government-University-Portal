<?php
session_start();
include_once 'includes/config.php';

// Add login and role verification here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>University Admin Dashboard - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }

        .stats-card {
            @apply bg-white/5 backdrop-blur-sm hover:bg-white/10 transition-all;
        }

        .data-table th {
            @apply px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider;
        }

        .data-table td {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-200;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-hidden">
    <!-- Background Animation -->
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
                    <li><a href="dashboard.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span></a></li>
                    <li><a href="student.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-users w-5"></i><span>Students</span></a></li>
                    <li><a href="#" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-building w-5"></i><span>Departments</span></a></li>
                    <li><a href="#" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-book-open w-5"></i><span>Courses</span></a></li>
                    
                    <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-gray-900/80 border-b border-gray-700/50 px-8 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Welcome, University Admin</h1>
                    <p class="text-gray-400">Representing Government of India, Mumbai University</p>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 hover:bg-white/5 rounded-full"><i class="fas fa-bell text-gray-400"></i></button>
                    <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center"><i class="fas fa-user text-purple-400"></i></div>
                </div>
            </header>

            <main class="p-8 space-y-8">
                <!-- Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Students</p>
                                <p class="text-3xl text-white font-bold">1,452</p>
                            </div>
                            <i class="fas fa-users text-3xl text-purple-400"></i>
                        </div>
                        <a href="#" class="text-purple-400 hover:text-pink-400 mt-4 inline-block">View student data →</a>
                    </div>
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Departments</p>
                                <p class="text-3xl text-white font-bold">24</p>
                            </div>
                            <i class="fas fa-building text-3xl text-purple-400"></i>
                        </div>
                        <a href="#" class="text-purple-400 hover:text-pink-400 mt-4 inline-block">View departments →</a>
                    </div>
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Courses</p>
                                <p class="text-3xl text-white font-bold">86</p>
                            </div>
                            <i class="fas fa-book-open text-3xl text-purple-400"></i>
                        </div>
                        <a href="#" class="text-purple-400 hover:text-pink-400 mt-4 inline-block">View courses →</a>
                    </div>
                </div>

                <!-- Placeholder for Tables, Notices, etc. -->
                <div class="bg-gray-900/80 p-6 rounded-xl border border-gray-700/50">
                    <h2 class="text-white text-xl font-semibold">Recent Updates</h2>
                    <p class="text-gray-400 mt-2">Coming soon...</p>
                </div>
                <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
          <h2 class="text-xl text-white mb-2">Total Students</h2>
          <p class="text-4xl font-bold text-secondary">1234</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
          <h2 class="text-xl text-white mb-2">New Registrations</h2>
          <p class="text-4xl font-bold text-green-400">56</p>
        </div>
      </div>

      <!-- Recent Students List -->
      <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
        <h2 class="text-xl font-semibold text-white mb-4">Recent Students</h2>
        <ul class="space-y-2">
          <li class="p-3 bg-gray-800 rounded-lg">Ravi Kumar - B.Tech CSE</li>
          <li class="p-3 bg-gray-800 rounded-lg">Sneha Gupta - BBA</li>
          <li class="p-3 bg-gray-800 rounded-lg">Arjun Sharma - BA History</li>
        </ul>
      </div>
      <div class="bg-gray-900/80 rounded-xl border border-gray-700/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-700/50">
                        <h2 class="text-xl font-bold text-white">Funding Approvals</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full data-table">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="pl-6">Student</th>
                                    <th>Email</th>
                                    <th>Document Title</th>
                                    <th>Type</th>
                                    <th>Uploaded</th>
                                    <th class="pr-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                <tr>
                                    <td class="pl-6 py-4">Amit Kumar</td>
                                    <td>amit@example.com</td>
                                    <td>Semester I Marksheet</td>
                                    <td>Transcript</td>
                                    <td>Apr 8, 2025</td>
                                    <td class="pr-6 space-x-2">
                                        <button class="px-3 py-1 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition-colors">
                                            Approve
                                        </button>
                                        <button class="px-3 py-1 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition-colors">
                                            Reject
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

      <!-- Upload Student Data Form -->
      <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl">
        <h2 class="text-xl font-semibold mb-4">Upload Student Data</h2>
        <form action="upload_student.php" method="POST" enctype="multipart/form-data" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="Student Name" required class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg">
            <input type="email" name="email" placeholder="Email" required class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg">
            <input type="text" name="program" placeholder="Program" required class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg">
            <input type="text" name="roll_no" placeholder="Roll Number" required class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg">
            <input type="text" name="department" placeholder="Department" class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg">
            <input type="date" name="dob" class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg">
          </div>
          <div>
            <label class="block text-gray-300 mb-1">Academic Transcript (PDF)</label>
            <input type="file" name="transcript" accept="application/pdf" class="text-white">
          </div>
          <button type="submit" class="bg-secondary hover:bg-accent text-white px-6 py-2 rounded-lg transition">
            Upload
          </button>
        </form>
      </div>

    </main>
  </div>
</body>
</html>
            </main>
        </div>
    </div>
</body>
</html>
