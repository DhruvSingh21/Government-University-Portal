<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduGov Connect - University-Government Data Portal</title>
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#6366f1',
            secondary: '#10b981',
            accent: '#f59e0b',
            dark: '#0f172a',
          },
          animation: {
            fadeIn: 'fadeIn 1s ease-in-out',
            slideUp: 'slideUp 0.8s ease-out',
            gradient: 'gradient 8s ease infinite',
            float: 'float 6s ease-in-out infinite',
            pulse: 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideUp: {
              '0%': { transform: 'translateY(100px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            },
            gradient: {
              '0%, 100%': { 'background-position': '0% 50%' },
              '50%': { 'background-position': '100% 50%' },
            },
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-20px)' },
            },
            pulse: {
              '0%, 100%': { opacity: '1' },
              '50%': { opacity: '0.5' },
            }
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="min-h-screen font-sans scroll-smooth bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 animate-gradient">
  <!-- Animated background elements -->
  <div class="fixed inset-0 overflow-hidden z-[-1]">
    <div class="absolute w-2 h-2 bg-white/10 rounded-full animate-float" style="left: 10%; top: 20%; animation-delay: 0s"></div>
    <div class="absolute w-3 h-3 bg-secondary/20 rounded-full animate-float" style="right: 15%; top: 50%; animation-delay: -2s"></div>
    <div class="absolute w-2.5 h-2.5 bg-accent/20 rounded-full animate-float" style="left: 30%; bottom: 30%; animation-delay: -1s"></div>
    <div class="absolute w-1.5 h-1.5 bg-white/10 rounded-full animate-float" style="right: 25%; top: 70%; animation-delay: -3s"></div>
  </div>
  
  <!-- Scroll progress indicator -->
  <div class="h-1.5 bg-gradient-to-r from-secondary to-accent fixed top-0 left-0 z-50 transition-all duration-300" id="scroll-progress"></div>

  <!-- Navigation -->
  <nav class="bg-dark/80 backdrop-blur-lg text-white shadow-2xl sticky top-0 z-50 transition-all duration-300 border-b border-white/10">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <a href="index.php" class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
            <i class="fas fa-university text-white"></i>
          </div>
          <div class="text-2xl font-bold hover:text-accent transition-colors cursor-pointer">EduGov Connect</div>
        </a>
      </div>
      
      <div class="hidden md:flex space-x-8 items-center">
        <a href="index.php#home" class="nav-link group">
          <span class="group-hover:text-secondary transition-colors">Home</span>
          <div class="nav-underline"></div>
        </a>
        <a href="index.php#features" class="nav-link group">
          <span class="group-hover:text-secondary transition-colors">Features</span>
          <div class="nav-underline"></div>
        </a>
        <a href="index.php#stats" class="nav-link group">
          <span class="group-hover:text-secondary transition-colors">Stats</span>
          <div class="nav-underline"></div>
        </a>
        <a href="index.php#contact" class="nav-link group">
          <span class="group-hover:text-secondary transition-colors">Contact</span>
          <div class="nav-underline"></div>
        </a>
      </div>
      
      <div class="hidden md:flex space-x-3">
        <?php if(isset($_SESSION['user'])): ?>
          <a href="dashboard.php" class="btn-login">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
          </a>
          <a href="logout.php" class="btn-register">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        <?php else: ?>
          <a href="login.php" class="btn-login">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
          </a>
          <a href="register.php" class="btn-register">
            <i class="fas fa-user-plus mr-2"></i> Register
          </a>
        <?php endif; ?>
      </div>
      
      <!-- Mobile menu button -->
      <button id="mobile-menu-button" class="md:hidden text-2xl focus:outline-none">
        <i class="fas fa-bars"></i>
      </button>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-dark/95 px-4 pb-4">
      <div class="flex flex-col space-y-3">
        <a href="index.php#home" class="mobile-nav-link">Home</a>
        <a href="index.php#features" class="mobile-nav-link">Features</a>
        <a href="index.php#stats" class="mobile-nav-link">Stats</a>
        <a href="index.php#contact" class="mobile-nav-link">Contact</a>
        <div class="pt-2 border-t border-white/10">
          <?php if(isset($_SESSION['user'])): ?>
            <a href="dashboard.php" class="mobile-btn-login">Dashboard</a>
            <a href="logout.php" class="mobile-btn-register">Logout</a>
          <?php else: ?>
            <a href="login.php" class="mobile-btn-login">Login</a>
            <a href="register.php" class="mobile-btn-register">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>