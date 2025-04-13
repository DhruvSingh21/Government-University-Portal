<?php
session_start();
include_once 'includes/config.php';

// Add proper session validation here

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Government Dashboard - EduGov Connect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <style>
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    .animate-float { animation: float 6s ease-in-out infinite; }

    .stats-card {
      @apply bg-white/5 backdrop-blur-md hover:bg-white/10 transition-all;
    }

    .data-card {
      @apply bg-gray-800 p-4 rounded-xl border border-gray-700/50 text-white;
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-x-hidden">
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
      </div>
      <nav class="p-4">
        <ul class="space-y-2">
          <li><a href="gov_dashboard.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-chart-line w-5"></i><span>Dashboard</span></a></li>
          <li><a href="universities.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-building w-5"></i><span>Universities</span></a></li>
          
          <li><a href="funding.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-rupee-sign w-5"></i><span>Funding</span></a></li>
          <li><a href="research.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-flask w-5"></i><span>Research</span></a></li>
          <li><a href="reports.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-file-alt w-5"></i><span>Reports</span></a></li>
          <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto p-8 space-y-8 text-white">
      <header class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold">Welcome, Dhruv</h1>
          <p class="text-gray-400">Government Admin Dashboard | Educational Data Analytics</p>
        </div>
        <button class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm"><i class="fas fa-download mr-2"></i>Export Reports</button>
      </header>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stats-card p-6 rounded-xl text-center">
          <i class="fas fa-users text-2xl text-blue-400 mb-2"></i>
          <h3 class="text-xl font-bold">8,742</h3>
          <p class="text-gray-300">Total Students</p>
        </div>
        <div class="stats-card p-6 rounded-xl text-center">
          <i class="fas fa-university text-2xl text-green-400 mb-2"></i>
          <h3 class="text-xl font-bold">112</h3>
          <p class="text-gray-300">Universities</p>
        </div>
        <div class="stats-card p-6 rounded-xl text-center">
          <i class="fas fa-network-wired text-2xl text-pink-400 mb-2"></i>
          <h3 class="text-xl font-bold">48</h3>
          <p class="text-gray-300">Departments</p>
        </div>
        <div class="stats-card p-6 rounded-xl text-center">
          <i class="fas fa-graduation-cap text-2xl text-yellow-400 mb-2"></i>
          <h3 class="text-xl font-bold">92%</h3>
          <p class="text-gray-300">Graduation Rate</p>
        </div>
      </div>

      <!-- Main Graphs Section -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Demographics Pie Chart -->
        <div class="data-card col-span-1">
          <h2 class="text-lg font-semibold mb-4">Student Gender Distribution</h2>
          <canvas id="genderChart" class="w-full h-64"></canvas>
        </div>
        
        <!-- Enrollment by Region -->
        <div class="data-card col-span-1">
          <h2 class="text-lg font-semibold mb-4">Enrollment by Region</h2>
          <canvas id="regionChart" class="w-full h-64"></canvas>
        </div>
        
        <!-- Course Popularity -->
        <div class="data-card col-span-1">
          <h2 class="text-lg font-semibold mb-4">Top Courses</h2>
          <canvas id="courseChart" class="w-full h-64"></canvas>
        </div>
      </div>

      <!-- Bottom Row Graphs -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- University Enrollment Bar Chart -->
        <div class="data-card">
          <h2 class="text-lg font-semibold mb-4">Top Universities by Enrollment</h2>
          <canvas id="enrollmentChart" class="w-full h-64"></canvas>
        </div>
        
        <!-- Funding Allocation -->
        <div class="data-card">
          <h2 class="text-lg font-semibold mb-4">Funding Allocation</h2>
          <canvas id="fundingChart" class="w-full h-64"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart JS -->
  <script>
    // Gender Distribution Pie Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
      type: 'pie',
      data: {
        labels: ['Male', 'Female', 'Other'],
        datasets: [{
          data: [52, 45, 3],
          backgroundColor: ['#4f46e5', '#ec4899', '#a78bfa'],
          borderWidth: 0
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#e2e8f0',
              font: {
                size: 12
              }
            }
          }
        }
      }
    });

    // Region Enrollment Chart
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    new Chart(regionCtx, {
      type: 'doughnut',
      data: {
        labels: ['North', 'South', 'East', 'West', 'Central'],
        datasets: [{
          data: [28, 35, 15, 12, 10],
          backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
          borderWidth: 0
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#e2e8f0',
              font: {
                size: 12
              }
            }
          }
        }
      }
    });

    // Course Popularity Chart
   

    // University Enrollment Bar Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
      type: 'bar',
      data: {
        labels: ['Delhi University', 'IIT Bombay', 'Mumbai University', 'Anna University', 'Manipal Academy'],
        datasets: [{
          label: 'Students (in thousands)',
          data: [45, 32, 28, 25, 18],
          backgroundColor: '#38bdf8',
          borderRadius: 4
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: '#e2e8f0'
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.1)'
            }
          },
          x: {
            ticks: {
              color: '#e2e8f0'
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.1)'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: '#e2e8f0'
            }
          }
        }
      }
    });

    // Funding Allocation Chart
    const fundingCtx = document.getElementById('fundingChart').getContext('2d');
    new Chart(fundingCtx, {
      type: 'radar',
      data: {
        labels: ['Infrastructure', 'Research', 'Scholarships', 'Faculty', 'Administration', 'Technology'],
        datasets: [{
          label: 'Funding Allocation (%)',
          data: [30, 25, 20, 15, 5, 5],
          backgroundColor: 'rgba(139, 92, 246, 0.2)',
          borderColor: '#8b5cf6',
          borderWidth: 2,
          pointBackgroundColor: '#8b5cf6',
          pointBorderColor: '#fff',
          pointHoverRadius: 5
        }]
      },
      options: {
        scales: {
          r: {
            angleLines: {
              color: 'rgba(255, 255, 255, 0.1)'
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.1)'
            },
            suggestedMin: 0,
            suggestedMax: 40,
            ticks: {
              color: '#e2e8f0',
              backdropColor: 'transparent'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: '#e2e8f0'
            }
          }
        }
      }
    });
  </script>
</body>
</html>