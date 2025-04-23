<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGov Connect | Government Education Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkblue: {
                            light: '#1e40af',
                            DEFAULT: '#1e3a8a',
                            dark: '#172554'
                        },
                        darkgreen: {
                            light: '#047857',
                            DEFAULT: '#065f46',
                            dark: '#064e3b'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            color: white;
            background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%);
        }
        
        .section-main {
            position: relative;
            overflow: hidden;
        }
        
        .btn-blue {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            transition: all 0.3s ease;
        }
        
        .btn-green {
            background: linear-gradient(135deg, #065f46 0%, #064e3b 100%);
            transition: all 0.3s ease;
        }
        
        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.3);
        }
        
        .btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
        }
        
        .nav-link {
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: #10b981;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .feature-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-darkblue/90 backdrop-blur-md sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="/gov/assets/images/logo.svg" alt="EduGov Connect" class="h-10">
                    <span class="text-xl font-bold text-white">EduGov Connect</span>
                </div>
                
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="nav-link text-white font-medium">Home</a>
                    <a href="#features" class="nav-link text-white font-medium">Features</a>
                    <a href="#partners" class="nav-link text-white font-medium">Partners</a>
                    <a href="#contact" class="nav-link text-white font-medium">Contact</a>
                    <div class="flex space-x-4 ml-6">
                        <a href="/gov/views/login-options.php" class="btn-blue text-white px-6 py-2 rounded-md font-medium">
                            Login
                        </a>
                        <a href="/gov/views/register-options.php" class="btn-green text-white px-6 py-2 rounded-md font-medium">
                            Register
                        </a>
                    </div>
                </nav>
                
                <button id="mobile-menu-button" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <div id="mobile-menu" class="md:hidden mt-4 hidden">
                <div class="flex flex-col space-y-4">
                    <a href="#home" class="nav-link text-white font-medium">Home</a>
                    <a href="#features" class="nav-link text-white font-medium">Features</a>
                    <a href="#partners" class="nav-link text-white font-medium">Partners</a>
                    <a href="#contact" class="nav-link text-white font-medium">Contact</a>
                    <div class="flex space-x-4 pt-2">
                        <a href="/gov/views/login-options.php" class="btn-blue text-white px-6 py-2 rounded-md font-medium w-full text-center">
                            Login
                        </a>
                        <a href="/gov/views/register-options.php" class="btn-green text-white px-6 py-2 rounded-md font-medium w-full text-center">
                            Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="section-main py-24">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#3b82f6] to-[#10b981]">Empowering Education</span> Through Digital Governance
                </h1>
                <p class="text-xl text-gray-300 mb-10 max-w-3xl mx-auto">
                    Transforming education management with our comprehensive platform connecting government agencies and academic institutions.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/gov/views/login-options.php" class="btn-blue text-white px-8 py-3 rounded-md font-medium">
                        Get Started <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#features" class="btn-green text-white px-8 py-3 rounded-md font-medium">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-main py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Key Features</h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Comprehensive tools for modern education governance
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card p-8 rounded-xl">
                    <div class="w-14 h-14 bg-blue-500/20 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-chart-line text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 text-center">Real-time Analytics</h3>
                    <p class="text-gray-300 text-center">
                        Comprehensive dashboards with education metrics and trends for data-driven decision making.
                    </p>
                </div>
                
                <div class="feature-card p-8 rounded-xl">
                    <div class="w-14 h-14 bg-green-500/20 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-database text-green-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 text-center">Unified Data</h3>
                    <p class="text-gray-300 text-center">
                        Centralized repository for all education-related data with secure access controls.
                    </p>
                </div>
                
                <div class="feature-card p-8 rounded-xl">
                    <div class="w-14 h-14 bg-blue-500/20 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-shield-alt text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 text-center">Secure Platform</h3>
                    <p class="text-gray-300 text-center">
                        Enterprise-grade security with role-based access controls and encryption.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section id="partners" class="section-main py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Our Partners</h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Trusted by leading education organizations nationwide
                </p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-12">
                <div class="bg-darkblue/80 p-6 rounded-xl border border-blue-500/20">
                    <img src="/gov/assets/images/ugc.svg" alt="UGC" class="h-16 mx-auto">
                </div>
                <div class="bg-darkblue/80 p-6 rounded-xl border border-blue-500/20">
                    <img src="/gov/assets/images/aicte.svg" alt="AICTE" class="h-16 mx-auto">
                </div>
                <div class="bg-darkblue/80 p-6 rounded-xl border border-blue-500/20">
                    <img src="/gov/assets/images/icar.svg" alt="ICAR" class="h-16 mx-auto">
                </div>
                <div class="bg-darkblue/80 p-6 rounded-xl border border-blue-500/20">
                    <img src="/gov/assets/images/naac.svg" alt="NAAC" class="h-16 mx-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-main py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-white mb-4">Contact Us</h2>
                    <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                        Get in touch with our team for more information
                    </p>
                </div>
                
                <div class="bg-darkblue/80 p-8 rounded-xl border border-blue-500/20">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-4">Ministry of Education</h3>
                            <p class="text-gray-300 mb-6">
                                Government Administrative Complex<br>
                                New Delhi, India 110001
                            </p>
                            <p class="text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-400"></i> support@edugovconnect.gov.in
                            </p>
                            <p class="text-gray-300">
                                <i class="fas fa-phone mr-2 text-blue-400"></i> +91 11 2345 6789
                            </p>
                        </div>
                        <div>
                            <form>
                                <div class="mb-4">
                                    <input type="text" placeholder="Your Name" class="w-full bg-darkblue border border-blue-500/30 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="mb-4">
                                    <input type="email" placeholder="Email Address" class="w-full bg-darkblue border border-blue-500/30 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="mb-4">
                                    <textarea placeholder="Your Message" rows="4" class="w-full bg-darkblue border border-blue-500/30 rounded-md px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <button type="submit" class="btn-blue text-white px-6 py-3 rounded-md font-medium w-full">
                                    Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-darkblue py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-6 md:mb-0">
                    <img src="/gov/assets/images/logo.svg" alt="EduGov Connect" class="h-8 mr-3">
                    <span class="text-lg font-bold text-white">EduGov Connect</span>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>
            <div class="border-t border-blue-900 mt-8 pt-8 text-center text-gray-500">
                <p>&copy; <?php echo date('Y'); ?> EduGov Connect. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    mobileMenu.classList.add('hidden');
                    0
                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
