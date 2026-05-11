<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%);
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stat-counter {
            transition: all 0.5s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-shield-virus text-blue-600 text-2xl mr-3"></i>
                    <span class="font-bold text-xl text-gray-800">BiteCare System</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#features" class="text-gray-600 hover:text-blue-600 transition">Features</a>
                    <a href="#about" class="text-gray-600 hover:text-blue-600 transition">About</a>
                    <a href="#statistics" class="text-gray-600 hover:text-blue-600 transition">Statistics</a>
                    <a href="<?php echo BASE_URL; ?>auth/login" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="<?php echo BASE_URL; ?>auth/register" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Animal Bite Center Management<br>
                    <span class="text-yellow-300">and Scheduling System</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                    A digital healthcare solution for efficient patient management, vaccination scheduling, and treatment monitoring.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo BASE_URL; ?>auth/register" 
                       class="bg-yellow-400 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition transform hover:scale-105">
                        <i class="fas fa-rocket mr-2"></i>Get Started
                    </a>
                    <a href="<?php echo BASE_URL; ?>auth/login" 
                       class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    About Animal Bite Management
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Rabies is a preventable viral disease that affects the central nervous system. 
                    Proper animal bite management and timely vaccination are crucial for saving lives.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Rabies Prevention</h3>
                    <p class="text-gray-600">
                        Immediate and proper wound cleaning followed by timely vaccination 
                        can prevent rabies infection after animal bites.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-syringe text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Vaccination Schedule</h3>
                    <p class="text-gray-600">
                        Following the correct vaccination schedule is essential for 
                        effective protection against rabies virus.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-laptop-medical text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Digital Management</h3>
                    <p class="text-gray-600">
                        Computerized systems ensure accurate tracking, timely reminders, 
                        and efficient patient care management.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    System Features
                </h2>
                <p class="text-lg text-gray-600">
                    Comprehensive tools for managing animal bite cases efficiently
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="bg-blue-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Patient Management</h3>
                    <p class="text-gray-600">
                        Complete patient records with medical history, bite details, and treatment progress tracking.
                    </p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="bg-green-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Appointment Scheduling</h3>
                    <p class="text-gray-600">
                        Smart scheduling system with calendar view, reminders, and queue management.
                    </p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="bg-purple-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-syringe text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Vaccination Monitoring</h3>
                    <p class="text-gray-600">
                        Track vaccine schedules, dosage records, and treatment progress with timeline visualization.
                    </p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="bg-orange-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Report Generation</h3>
                    <p class="text-gray-600">
                        Generate comprehensive reports with statistics, charts, and export capabilities.
                    </p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="bg-red-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-user-shield text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Role Management</h3>
                    <p class="text-gray-600">
                        Secure role-based access control for different user types with appropriate permissions.
                    </p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="bg-yellow-100 w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Notifications & Reminders</h3>
                    <p class="text-gray-600">
                        Automated reminders for appointments, vaccinations, and follow-up schedules.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="statistics" class="py-20 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    System Impact
                </h2>
                <p class="text-lg text-blue-100">
                    Real-time statistics showing our system's effectiveness
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="stat-counter bg-white bg-opacity-20 rounded-lg p-6">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <div class="text-4xl font-bold mb-2" data-target="1250">0</div>
                        <p class="text-lg">Registered Patients</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="stat-counter bg-white bg-opacity-20 rounded-lg p-6">
                        <i class="fas fa-syringe text-4xl mb-4"></i>
                        <div class="text-4xl font-bold mb-2" data-target="3750">0</div>
                        <p class="text-lg">Vaccinations Completed</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="stat-counter bg-white bg-opacity-20 rounded-lg p-6">
                        <i class="fas fa-calendar-check text-4xl mb-4"></i>
                        <div class="text-4xl font-bold mb-2" data-target="850">0</div>
                        <p class="text-lg">Scheduled Appointments</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="stat-counter bg-white bg-opacity-20 rounded-lg p-6">
                        <i class="fas fa-user-md text-4xl mb-4"></i>
                        <div class="text-4xl font-bold mb-2" data-target="45">0</div>
                        <p class="text-lg">Active Staff</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-shield-virus text-blue-400 text-2xl mr-3"></i>
                        <span class="font-bold text-xl">BiteCare System</span>
                    </div>
                    <p class="text-gray-400">
                        Comprehensive animal bite center management solution for healthcare facilities.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#about" class="hover:text-white transition">About</a></li>
                        <li><a href="<?php echo BASE_URL; ?>auth/login" class="hover:text-white transition">Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>auth/register" class="hover:text-white transition">Register</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Contact Information</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>+63 (2) 1234-5678</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@bitecenter.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Manila, Philippines</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Animal Bite Center Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animated counter for statistics
        function animateCounter() {
            const counters = document.querySelectorAll('.stat-counter [data-target]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current).toLocaleString();
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };
                
                updateCounter();
            });
        }

        // Trigger counter animation when statistics section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('#statistics');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Add shadow to navigation on scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-2xl');
            } else {
                nav.classList.remove('shadow-2xl');
            }
        });
    </script>
</body>
</html>
