</main> <!-- Close main content if needed -->

<footer class="bg-dark/90 backdrop-blur-lg border-t border-white/10 pt-12 pb-8">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-10 mb-12">
            <!-- Brand Column -->
            <div class="footer-col">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                        <i class="fas fa-university text-white"></i>
                    </div>
                    <div class="text-xl font-bold">EduGov Connect</div>
                </div>
                <p class="text-gray-400 mb-4">
                    Empowering education through innovative data solutions and digital transformation.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="social-icon hover:bg-secondary">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon hover:bg-primary">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-icon hover:bg-pink-600">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon hover:bg-gray-700">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="index.php#home" class="footer-link">Home</a></li>
                    <li><a href="index.php#features" class="footer-link">Features</a></li>
                    <li><a href="index.php#stats" class="footer-link">Statistics</a></li>
                    <li><a href="index.php#contact" class="footer-link">Contact</a></li>
                    <li><a href="documentation.php" class="footer-link">Documentation</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div class="footer-col">
                <h4 class="text-white font-semibold mb-4">Resources</h4>
                <ul class="space-y-3">
                    <li><a href="api-docs.php" class="footer-link">API Documentation</a></li>
                    <li><a href="developers.php" class="footer-link">Developer Portal</a></li>
                    <li><a href="case-studies.php" class="footer-link">Case Studies</a></li>
                    <li><a href="white-papers.php" class="footer-link">White Papers</a></li>
                    <li><a href="blog.php" class="footer-link">Blog</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-col">
                <h4 class="text-white font-semibold mb-4">Contact Info</h4>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt text-secondary mt-1 mr-3"></i>
                        <span class="text-gray-400">Education Tower, Sector 62, Noida, UP 201309</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt text-secondary mr-3"></i>
                        <span class="text-gray-400">+91 98765 43210</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope text-secondary mr-3"></i>
                        <span class="text-gray-400">contact@edugovconnect.in</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 text-sm mb-4 md:mb-0">
                &copy; 2025 EduGov Connect. All rights reserved.
            </p>
            <div class="flex space-x-6">
                <a href="privacy.php" class="text-gray-500 hover:text-white text-sm">Privacy Policy</a>
                <a href="terms.php" class="text-gray-500 hover:text-white text-sm">Terms of Service</a>
                <a href="cookies.php" class="text-gray-500 hover:text-white text-sm">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scroll-top" class="scroll-top-btn">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll Progress Indicator
    const scrollProgress = document.getElementById('scroll-progress');
    window.addEventListener('scroll', () => {
        const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.scrollY / windowHeight) * 100;
        scrollProgress.style.width = scrolled + '%';
    });

    // Animated Counters
    const animateCounters = () => {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / 200;

                if(count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            }
            updateCount();
        });
    }

    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add('animate-slideUp');
                if(entry.target.classList.contains('counter')) {
                    animateCounters();
                }
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.animate-on-scroll').forEach(element => {
        observer.observe(element);
    });
});
</script>

</body>
</html>