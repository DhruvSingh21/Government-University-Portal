<?php
// Start the session

// Include database configuration
include_once 'includes/config.php';

// Include header
include_once 'includes/header.php';
?>


<!-- Main Content -->
<main>
    <!-- Hero Section -->
    <header id="home" class="relative overflow-hidden py-32">
        <div class="absolute inset-0 bg-grid-white/5 z-0"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-slideUp">
                    <span class="bg-gradient-to-r from-secondary to-accent bg-clip-text text-transparent">Empowering Education</span>
                    <span class="block mt-3 text-white">With Smart Data Solutions</span>
                </h1>
                <p class="text-xl md:text-2xl mb-10 text-gray-300 animate-fadeIn">
                    Bridging academia and governance through innovative digital transformation
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <?php if (!isset($_SESSION['user'])): ?>
                        <a href="register.php" class="cta-primary">
                            Get Started <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    <?php endif; ?>
                    <a href="#features" class="cta-secondary">
                        Learn More <i class="fas fa-book-open ml-2"></i>
                    </a>
                </div>
            </div>

            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <div class="partner-logo animate-slideUp" style="animation-delay: 0.2s">
                    <i class="fas fa-graduation-cap text-4xl"></i>
                    <span>UGC</span>
                </div>
                <div class="partner-logo animate-slideUp" style="animation-delay: 0.4s">
                    <i class="fas fa-atom text-4xl"></i>
                    <span>AICTE</span>
                </div>
                <div class="partner-logo animate-slideUp" style="animation-delay: 0.6s">
                    <i class="fas fa-flask text-4xl"></i>
                    <span>ICAR</span>
                </div>
                <div class="partner-logo animate-slideUp" style="animation-delay: 0.8s">
                    <i class="fas fa-medal text-4xl"></i>
                    <span>NAAC</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white/5 backdrop-blur-lg relative overflow-hidden">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-accent/10 rounded-full filter blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-secondary/10 rounded-full filter blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-secondary/20 text-secondary rounded-full mb-4">Features</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Transformative Platform Capabilities</h2>
                <p class="max-w-2xl mx-auto text-gray-300">Our comprehensive solution brings together all stakeholders in the education ecosystem</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Cards (6 total) -->
                <?php
                $features = [
                    [
                        'icon' => 'fa-database',
                        'color' => 'secondary',
                        'title' => 'Unified Data Ecosystem',
                        'desc' => 'Centralized repository for academic records with role-based access control.'
                    ],
                    [
                        'icon' => 'fa-shield-alt',
                        'color' => 'accent',
                        'title' => 'Advanced Security',
                        'desc' => 'Military-grade encryption and blockchain verification.'
                    ],
                    [
                        'icon' => 'fa-chart-network',
                        'color' => 'primary',
                        'title' => 'Real-time Analytics',
                        'desc' => 'Interactive dashboards with predictive analytics.'
                    ],
                    [
                        'icon' => 'fa-comments',
                        'color' => 'purple-400',
                        'title' => 'Collaboration Hub',
                        'desc' => 'Integrated communication tools for institutions.'
                    ],
                    [
                        'icon' => 'fa-mobile-alt',
                        'color' => 'pink-400',
                        'title' => 'Mobile First',
                        'desc' => 'Fully responsive design with native mobile apps.'
                    ],
                    [
                        'icon' => 'fa-brain',
                        'color' => 'green-400',
                        'title' => 'AI Integration',
                        'desc' => 'Smart recommendations and predictive modeling.'
                    ]
                ];

                foreach ($features as $index => $feature): ?>
                    <div class="feature-card animate-slideUp" style="animation-delay: <?= 0.1 + ($index * 0.1) ?>s">
                        <div class="feature-icon bg-<?= $feature['color'] ?>/20 text-<?= $feature['color'] ?>">
                            <i class="fas <?= $feature['icon'] ?>"></i>
                        </div>
                        <h3><?= $feature['title'] ?></h3>
                        <p><?= $feature['desc'] ?></p>
                        <a href="#" class="feature-link">Explore <i class="fas fa-chevron-right ml-1"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-20 bg-gradient-to-br from-blue-900/50 to-indigo-900/50 relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-accent/20 text-accent rounded-full mb-4">Impact</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Our Growing Network</h2>
                <p class="max-w-2xl mx-auto text-gray-300">Join thousands of institutions already benefiting from our platform</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto">
                <?php
                $stats = [
                    ['count' => '950', 'label' => 'Universities', 'color' => 'secondary'],
                    ['count' => '15000000', 'label' => 'Students', 'color' => 'accent'],
                    ['count' => '2500', 'label' => 'Programs', 'color' => 'primary'],
                    ['count' => '28', 'label' => 'States', 'color' => 'purple-400']
                ];

                foreach ($stats as $index => $stat): ?>
                    <div class="stat-item animate-slideUp" style="animation-delay: <?= 0.1 + ($index * 0.1) ?>s">
                        <div class="counter text-4xl md:text-5xl font-bold text-<?= $stat['color'] ?> mb-2" 
                             data-target="<?= $stat['count'] ?>">0</div>
                        <p class="text-sm uppercase tracking-widest text-gray-300"><?= $stat['label'] ?></p>
                        <div class="progress-bar mt-4">
                            <div class="progress-fill bg-<?= $stat['color'] ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gradient-to-br from-indigo-900/50 to-blue-900/50 relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div class="contact-card backdrop-blur-sm">
                    <div class="text-center mb-8">
                        <span class="inline-block px-4 py-1 bg-white/20 text-white rounded-full mb-4">Get in Touch</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Connect With Our Team</h2>
                        <p class="text-gray-300">Have questions or want to learn more? Reach out to our support team</p>
                    </div>
                    
                    <form class="space-y-6" action="contact.php" method="POST">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <input type="text" id="name" name="name" placeholder=" " required>
                                <label for="name">Your Name</label>
                                <div class="form-underline"></div>
                            </div>
                            
                            <div class="form-group">
                                <input type="email" id="email" name="email" placeholder=" " required>
                                <label for="email">Email Address</label>
                                <div class="form-underline"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="subject" name="subject" placeholder=" " required>
                            <label for="subject">Subject</label>
                            <div class="form-underline"></div>
                        </div>
                        
                        <div class="form-group">
                            <textarea id="message" name="message" rows="4" placeholder=" " required></textarea>
                            <label for="message">Your Message</label>
                            <div class="form-underline"></div>
                        </div>
                        
                        <button type="submit" class="submit-btn w-full">
                            Send Message <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
include_once 'includes/footer.php';
?>