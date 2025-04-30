<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR System - Modern Human Resource Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #486edb;
            --secondary-color: #3d5cba;
        }
        .bg-primary {
            background-color: var(--primary-color);
        }
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        .text-primary {
            color: var(--primary-color);
        }
        .border-primary {
            border-color: var(--primary-color);
        }
        .login-container {
            backdrop-filter: blur(5px);
            background-color: rgba(255, 255, 255, 0.9);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        .smooth-scroll {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="font-sans antialiased smooth-scroll">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <div class="text-xl font-bold text-primary mr-2">
                    <i class="far fa-smile-beam"></i>
                </div>
                <span class="font-bold text-xl text-gray-800">HR SYSTEM</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-gray-600 hover:text-primary transition">Home</a>
                <a href="#features" class="text-gray-600 hover:text-primary transition">Features</a>
                <a href="#about" class="text-gray-600 hover:text-primary transition">About Us</a>
                <a href="#contact" class="text-gray-600 hover:text-primary transition">Contact</a>
            </div>
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="outline-none">
                    <i class="fas fa-bars text-primary text-2xl"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white w-full py-2 shadow-inner">
            <div class="container mx-auto px-6 flex flex-col space-y-3">
                <a href="#home" class="text-gray-600 hover:text-primary transition py-2">Home</a>
                <a href="#features" class="text-gray-600 hover:text-primary transition py-2">Features</a>
                <a href="#about" class="text-gray-600 hover:text-primary transition py-2">About Us</a>
                <a href="#contact" class="text-gray-600 hover:text-primary transition py-2">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-24 bg-gray-100">
        <div class="container mx-auto px-6 py-12 md:py-24">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Left side content -->
                <div class="w-full md:w-1/2 mb-12 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                        Modern HR Management System for Growing Businesses
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Streamline your HR processes, manage employees effectively, and make data-driven decisions with our comprehensive HRMS solution.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#contact" class="bg-primary hover:bg-secondary-color text-white font-medium py-3 px-6 rounded-lg transition duration-300 text-center">
                            Request Demo
                        </a>
                        <a href="#features" class="bg-white hover:bg-gray-100 text-primary border border-primary font-medium py-3 px-6 rounded-lg transition duration-300 text-center">
                            Explore Features
                        </a>
                    </div>
                </div>
                
                <!-- Right side login form -->
                <div class="w-full md:w-1/2 md:pl-12">
                    <div class="bg-white rounded-xl shadow-xl p-8 max-w-md mx-auto">
                        <!-- Session Status -->
                        <div class="mb-4"></div>

                        <div class="text-center mb-8">
                            <h1 class="text-3xl font-bold text-primary">Login</h1>
                            <p class="text-gray-600 mt-2">Please enter your Login and your Password</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-4">
                                <div class="relative flex items-center">
                                    <span class="absolute left-4 text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input id="email" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800" 
                                        type="email" name="email" placeholder="Username or E-mail" required autofocus autocomplete="username" />
                                </div>
                                <div class="mt-2 text-red-300"></div>
                            </div>

                            <!-- Password -->
                            <div class="mb-2">
                                <div class="relative flex items-center">
                                    <span class="absolute left-4 text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input id="password" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800"
                                        type="password"
                                        name="password"
                                        placeholder="Password"
                                        required autocomplete="current-password" />
                                </div>
                                <div class="mt-2 text-red-300"></div>
                            </div>
                            
                            <div class="text-right">
                                <a class="text-sm text-primary hover:text-secondary-color" href="#">
                                    Forgot password?
                                </a>
                            </div>

                            <!-- Login Button -->
                            <div class="mt-8">
                                <button type="submit" class="w-full py-3 bg-primary hover:bg-secondary-color text-white font-medium rounded-lg transition duration-300 shadow-md">
                                    Login
                                </button>
                            </div>

                            <!-- Google Login 
                            <div class="mt-4">
                                <a href="#" class="flex items-center justify-center w-full py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition duration-300 shadow-md">
                                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.24 10.285V14.4h6.806c-.275 1.765-2.056 5.174-6.806 5.174-4.095 0-7.439-3.389-7.439-7.574s3.345-7.574 7.439-7.574c2.33 0 3.891.989 4.785 1.849l3.254-3.138C18.189 1.186 15.479 0 12.24 0c-6.635 0-12 5.365-12 12s5.365 12 12 12c6.926 0 11.52-4.869 11.52-11.726 0-.788-.085-1.39-.189-1.989H12.24z" fill="currentColor"/>
                                    </svg>
                                    Or sign-in with Google
                                </a>
                            </div>
                                -->
                            <!-- Register Link 
                            <div class="mt-8 text-center text-gray-600">
                                <span>Not a member yet?</span>
                                <a href="#" class="text-primary font-medium ml-1 hover:underline">
                                    Register
                                </a>
                            </div>
                            -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave Separator -->
        <div class="w-full h-24">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Powerful HR Features</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Our comprehensive HRMS solution offers everything you need to manage your workforce effectively.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md border-t-4 border-primary transition duration-300">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Employee Management</h3>
                    <p class="text-gray-600">Centralize employee data, track performance, and manage all personnel information in one secure location.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md border-t-4 border-primary transition duration-300">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Payroll Management</h3>
                    <p class="text-gray-600">Automate salary calculations, tax deductions, and generate payslips with our powerful payroll engine.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md border-t-4 border-primary transition duration-300">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Leave Management</h3>
                    <p class="text-gray-600">Streamline leave requests, approvals, and balances while ensuring adequate staffing levels at all times.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md border-t-4 border-primary transition duration-300">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Attendance Tracking</h3>
                    <p class="text-gray-600">Monitor employee attendance, track work hours, and manage shifts with our intuitive attendance system.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md border-t-4 border-primary transition duration-300">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Project Management</h3>
                    <p class="text-gray-600">Plan, execute, and track projects, allocate resources, and monitor progress all within one platform.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md border-t-4 border-primary transition duration-300">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Reports & Analytics</h3>
                    <p class="text-gray-600">Generate insightful reports and dashboards to make data-driven decisions for your organization.</p>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#contact" class="inline-block bg-primary hover:bg-secondary-color text-white font-medium py-3 px-8 rounded-lg transition duration-300">
                    Get Started Today
                </a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="w-full lg:w-1/2 mb-12 lg:mb-0">
                    <div class="bg-primary-gradient rounded-lg shadow-xl p-6 md:p-8 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h2 class="text-3xl font-bold mb-4">About Our Company</h2>
                            <p class="mb-6 opacity-90">
                                We are a dedicated team of HR professionals and software engineers passionate about creating tools that transform how businesses manage their most valuable asset - their people.
                            </p>
                            <p class="mb-6 opacity-90">
                                With years of experience in human resources and technology, we understand the challenges organizations face in managing their workforce efficiently. Our mission is to provide intuitive, powerful, and affordable HR solutions that adapt to your unique needs.
                            </p>
                            <div class="flex flex-wrap gap-4 mt-8">
                                <div class="flex items-center">
                                    <div class="text-4xl font-bold mr-2">10+</div>
                                    <div class="text-sm">Years<br>Experience</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-4xl font-bold mr-2">500+</div>
                                    <div class="text-sm">Happy<br>Clients</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-4xl font-bold mr-2">25+</div>
                                    <div class="text-sm">Team<br>Members</div>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-10 rounded-full -ml-24 -mb-24"></div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 lg:pl-16">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Why Choose Our HRMS</h3>
                    
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-xl font-semibold text-gray-800">User-Friendly Interface</h4>
                                <p class="text-gray-600 mt-2">Our intuitive design ensures that employees at all levels can navigate the system with ease, reducing training time.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-xl font-semibold text-gray-800">Customizable Workflows</h4>
                                <p class="text-gray-600 mt-2">Adapt the system to your specific business processes rather than changing your processes to fit the software.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-xl font-semibold text-gray-800">Comprehensive Support</h4>
                                <p class="text-gray-600 mt-2">Our team is available to provide technical assistance and answer questions whenever you need help.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-xl font-semibold text-gray-800">Data Security</h4>
                                <p class="text-gray-600 mt-2">We prioritize the security of your sensitive HR data with industry-leading encryption and protection measures.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">What Our Clients Say</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Hear from businesses that have transformed their HR processes with our system.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">"This HRMS has completely transformed how we manage our HR processes. The interface is intuitive and the support team is always responsive and helpful."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-xl font-bold text-white">A</div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-800">Ahmad Nasser</h4>
                            <p class="text-gray-500 text-sm">HR Director, Tech Solutions</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">"The payroll management feature has saved us countless hours each month. It's accurate, fast, and makes compliance a breeze. Highly recommended!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-xl font-bold text-white">S</div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-800">Sarah Johnson</h4>
                            <p class="text-gray-500 text-sm">CFO, Global Enterprises</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-6">"As a growing company, we needed an HR system that could scale with us. This solution has been flexible, powerful, and exactly what we needed to manage our team effectively."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-xl font-bold text-white">M</div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-800">Mohammed Ali</h4>
                            <p class="text-gray-500 text-sm">CEO, Startup Innovators</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-900 text-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Get In Touch</h2>
                <p class="text-gray-300 max-w-2xl mx-auto">Have questions about our HRMS? Contact us for more information or to schedule a demo.</p>
            </div>
            
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 mb-12 md:mb-0 md:pr-8">
                    <form class="space-y-6" action="https://api.web3forms.com/submit" method="POST">
                        <input type="hidden" name="access_key" value="57969581-209b-4b02-9e96-0517185d01a8">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="name" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-white" placeholder="Your name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                            <input type="email" id="email" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-white" placeholder="Your email">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-300 mb-2">Company</label>
                            <input type="text" id="company" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-white" placeholder="Your company">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-white" placeholder="Your message"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full py-3 bg-primary hover:bg-secondary-color text-white font-medium rounded-lg transition duration-300 shadow-md">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-1/2 md:pl-8">
                    <div class="bg-gray-800 rounded-lg p-6 md:p-8 h-full">
                        <h3 class="text-xl font-bold mb-6">Contact Information</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="text-primary text-xl mr-4">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Office Location</h4>
                                    <p class="text-gray-400">123 Business Avenue, Technology Park<br>Amman, Jordan</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-primary text-xl mr-4">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Phone Number</h4>
                                    <p class="text-gray-400">+962 6 123 4567</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-primary text-xl mr-4">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Email Address</h4>
                                    <p class="text-gray-400">info@hrsystem.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-primary text-xl mr-4">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Working Hours</h4>
                                    <p class="text-gray-400">Sunday - Thursday: 9:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="font-semibold mb-4">Follow Us</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white hover:bg-primary transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white hover:bg-primary transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white hover:bg-primary transition">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white hover:bg-primary transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300">
        <div class="container mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-8 md:mb-0">
                    <div class="flex items-center mb-4">
                        <div class="text-xl font-bold text-primary mr-2">
                            <i class="far fa-smile-beam"></i>
                        </div>
                        <span class="font-bold text-xl text-white">HR SYSTEM</span>
                    </div>
                    <p class="text-gray-400 max-w-xs">Transforming how businesses manage their workforce with intuitive and powerful HR solutions.</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#home" class="hover:text-primary transition">Home</a></li>
                            <li><a href="#features" class="hover:text-primary transition">Features</a></li>
                            <li><a href="#about" class="hover:text-primary transition">About Us</a></li>
                            <li><a href="#contact" class="hover:text-primary transition">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Features</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-primary transition">Employee Management</a></li>
                            <li><a href="#" class="hover:text-primary transition">Payroll</a></li>
                            <li><a href="#" class="hover:text-primary transition">Leave Management</a></li>
                            <li><a href="#" class="hover:text-primary transition">Attendance</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-primary transition">Blog</a></li>
                            <li><a href="#" class="hover:text-primary transition">Documentation</a></li>
                            <li><a href="#" class="hover:text-primary transition">Support</a></li>
                            <li><a href="#" class="hover:text-primary transition">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p>© 2025 HR System. All rights reserved.</p>
                <div class="mt-4 md:mt-0">
                    <ul class="flex space-x-4">
                        <li><a href="#" class="hover:text-primary transition">Terms</a></li>
                        <li><a href="#" class="hover:text-primary transition">Privacy</a></li>
                        <li><a href="#" class="hover:text-primary transition">Cookies</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Close mobile menu if open
                document.getElementById('mobile-menu').classList.add('hidden');
                
                // Scroll to the section
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>