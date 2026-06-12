<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Central App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        /* Global styles */
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        /* Background enhancement using layered blobs */
        .bg-enhance::before,
        .bg-enhance::after {
            content: '';
            position: absolute;
            z-index: 0;
            border-radius: 9999px;
            opacity: 0.2;
            filter: blur(100px);
        }
        .bg-enhance::before {
            width: 400px;
            height: 400px;
            background: #7da3e0;
            top: -100px;
            left: -100px;
            animation: float 8s ease-in-out infinite;
        }
        .bg-enhance::after {
            width: 500px;
            height: 500px;
            background: #ffe5f3;
            bottom: -120px;
            right: -100px;
            animation: float 10s ease-in-out infinite reverse;
        }

        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-20px) translateX(20px); }
            100% { transform: translateY(0) translateX(0); }
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(90deg, #4473be, #7da3e0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Card hover effects */
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Testimonial carousel */
        .testimonial-slide {
            transition: opacity 0.5s ease;
        }

        /* Pricing card highlight */
        .pricing-card {
            transition: all 0.3s ease;
        }
        .pricing-card:hover {
            transform: scale(1.03);
        }
        .popular-badge {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);
        }

        /* FAQ accordion */
        .faq-item {
            transition: all 0.3s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #7da3e0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4473be;
        }
    </style>
</head>

<body class="relative bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col items-center overflow-x-hidden" x-data="{ mobileMenuOpen: false }" x-init="AOS.init({ duration: 800, once: true })">

    <!-- Background layer -->
    <div class="absolute inset-0 bg-enhance pointer-events-none"></div>

    <!-- Snackbar Notification -->
    @if(session('success'))
        <x-snackbar message="{{ session('success') }}" type="success" :show="true" />
    @endif

    @if(session('error'))
        <x-snackbar message="{{ session('error') }}" type="error" :show="true" />
    @endif

    <!-- Header -->
    <header class="w-full max-w-7xl py-6 px-6 lg:px-8 mb-8 z-20 sticky top-0 bg-[#FDFDFC]/80 dark:bg-[#0a0a0a]/80 backdrop-blur-md">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-[#4473be] to-[#7da3e0] flex items-center justify-center text-white font-bold text-xl">C</div>
                <span class="ml-3 text-xl font-bold gradient-text">Central</span>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Features</a>
                <a href="#how-it-works" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">How It Works</a>
                <a href="#pricing" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Pricing</a>
                <a href="#faq" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">FAQ</a>

                @routeCheck('login')
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 border border-[#4473be] text-[#4473be] rounded-full hover:bg-[#4473be] hover:text-white transition">
                            Log in
                        </a>
                    @endauth
                @endrouteCheck
            </nav>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-[#1b1b18] dark:text-[#EDEDEC]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mobileMenuOpen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mobileMenuOpen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
            <nav class="flex flex-col gap-4 mt-6 bg-white dark:bg-[#1a1a1a] p-4 rounded-lg shadow-lg">
                <a href="#features" @click="mobileMenuOpen = false" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] py-2 transition">Features</a>
                <a href="#how-it-works" @click="mobileMenuOpen = false" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] py-2 transition">How It Works</a>
                <a href="#pricing" @click="mobileMenuOpen = false" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] py-2 transition">Pricing</a>
                <a href="#faq" @click="mobileMenuOpen = false" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] py-2 transition">FAQ</a>

                @routeCheck('login')
                    @auth
                        <a href="{{ url('/dashboard') }}" @click="mobileMenuOpen = false" class="px-5 py-2 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] text-center transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="px-5 py-2 border border-[#4473be] text-[#4473be] rounded-full hover:bg-[#4473be] hover:text-white text-center transition">
                            Log in
                        </a>
                    @endauth
                @endrouteCheck
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="w-full max-w-7xl px-6 lg:px-8 mb-24 z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2" data-aos="fade-right">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6">
                    Empower Your <span class="gradient-text">Teaching</span> Journey
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                    The all-in-one platform for instructors and academies to create, manage, and grow their educational business.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#pricing" class="px-8 py-3 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] transition text-center">
                        Get Started
                    </a>
                    <a href="#how-it-works" class="px-8 py-3 border border-[#4473be] text-[#4473be] rounded-full hover:bg-[#4473be] hover:text-white transition text-center">
                        Learn More
                    </a>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex -space-x-2">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                        <img src="https://randomuser.me/api/portraits/men/44.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                        <img src="https://randomuser.me/api/portraits/women/56.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                        <img src="https://randomuser.me/api/portraits/men/78.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-bold">1,000+</span> instructors trust us
                    </p>
                </div>
            </div>
            <div class="lg:w-1/2 relative" data-aos="fade-left">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#4473be]/10 rounded-full filter blur-xl"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-[#7da3e0]/10 rounded-full filter blur-xl"></div>
                <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Teaching Platform" class="rounded-2xl shadow-2xl w-full">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="w-full max-w-7xl px-6 lg:px-8 mb-24 z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4">
                Powerful <span class="gradient-text">Features</span> for Educators
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Everything you need to create, manage, and grow your educational business in one place.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chalkboard-teacher text-2xl text-[#4473be]"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Course Management</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Create and organize courses with ease. Upload videos, PDFs, quizzes, and assignments all in one place.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-users text-2xl text-[#4473be]"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Student Management</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Track student progress, manage enrollments, and communicate with your students all from one dashboard.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-2xl text-[#4473be]"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Analytics & Insights</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Get detailed analytics on student engagement, course performance, and revenue to make data-driven decisions.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="w-14 h-14 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-credit-card text-2xl text-[#4473be]"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Payment Processing</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Accept payments securely with multiple payment options. Manage subscriptions and one-time purchases.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="500">
                <div class="w-14 h-14 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-mobile-alt text-2xl text-[#4473be]"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Mobile Friendly</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Fully responsive design ensures your students can access your courses on any device, anywhere, anytime.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="600">
                <div class="w-14 h-14 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-certificate text-2xl text-[#4473be]"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Certificates</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Create and issue custom certificates to your students upon course completion to recognize their achievements.
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="w-full max-w-7xl px-6 lg:px-8 mb-24 z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4">
                How It <span class="gradient-text">Works</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Get started in just a few simple steps and transform your teaching experience.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="relative" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg h-full">
                    <div class="absolute -top-5 -left-5 w-12 h-12 bg-[#4473be] rounded-full flex items-center justify-center text-white font-bold text-xl">1</div>
                    <h3 class="text-xl font-bold mb-4 mt-4">Choose Your Plan</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Select the subscription plan that best fits your needs and the size of your educational business.
                    </p>
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Choose Plan" class="rounded-lg w-full h-40 object-cover">
                </div>
                <div class="hidden md:block absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 z-10">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#4473be" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg h-full">
                    <div class="absolute -top-5 -left-5 w-12 h-12 bg-[#4473be] rounded-full flex items-center justify-center text-white font-bold text-xl">2</div>
                    <h3 class="text-xl font-bold mb-4 mt-4">Set Up Your Academy</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Customize your academy with your branding, create instructor accounts, and set up your courses.
                    </p>
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Setup Academy" class="rounded-lg w-full h-40 object-cover">
                </div>
                <div class="hidden md:block absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 z-10">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#4473be" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Step 3 -->
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-lg h-full">
                    <div class="absolute -top-5 -left-5 w-12 h-12 bg-[#4473be] rounded-full flex items-center justify-center text-white font-bold text-xl">3</div>
                    <h3 class="text-xl font-bold mb-4 mt-4">Start Teaching</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Upload your content, invite students, and start teaching! Our platform handles the rest.
                    </p>
                    <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Start Teaching" class="rounded-lg w-full h-40 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section id="pricing" class="w-full max-w-7xl px-6 lg:px-8 mb-24 z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4">
                Choose Your <span class="gradient-text">Plan</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Flexible pricing for every type of instructor or academy.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 w-full max-w-6xl mx-auto" data-aos="fade-up">
            <!-- Basic Plan -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-8 shadow-xl flex flex-col text-[#4473be] backdrop-blur-sm hover:shadow-2xl transition border border-gray-100 dark:border-gray-800 pricing-card">
                <h3 class="text-xl font-bold">Basic</h3>
                <p class="mt-2 text-3xl font-bold">₱999<span class="text-base font-normal">/ month</span></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Billed yearly</p>
                <div class="mt-6 mb-6 h-1 w-20 bg-gradient-to-r from-[#4473be] to-[#7da3e0] rounded-full"></div>
                <ul class="mt-2 space-y-4 text-sm text-[#1b1b18] dark:text-[#EDEDEC] flex-grow">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        3 subjects
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        30 Students
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Up to 30 Students
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Basic classroom management
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Limited file uploads
                    </li>
                </ul>
                <button onclick="openSubscriptionModal('Basic')" class="mt-8 bg-[#7da3e0] text-white font-bold py-3 px-6 rounded-full hover:bg-[#5b86cc] transition">
                    Get Started
                </button>
            </div>

            <!-- Premium Plan (Most Popular) -->
            <div class="bg-[#f0f6ff] dark:bg-[#1a1a1a] dark:bg-opacity-95 border-2 border-[#7da3e0] rounded-2xl p-8 shadow-xl flex flex-col relative text-[#1b1b18] dark:text-[#EDEDEC] hover:shadow-2xl transition backdrop-blur-sm pricing-card transform scale-105">
                <div class="absolute -top-5 left-0 right-0 mx-auto w-40 h-10 bg-gradient-to-r from-[#4473be] to-[#7da3e0] text-white text-sm font-bold flex items-center justify-center rounded-full popular-badge">
                    Most Popular
                </div>
                <h3 class="text-xl font-bold text-[#4473be]">Premium</h3>
                <p class="mt-2 text-3xl font-bold">₱2,499 <span class="text-base font-normal">/ month</span></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Billed yearly</p>
                <div class="mt-6 mb-6 h-1 w-20 bg-gradient-to-r from-[#4473be] to-[#7da3e0] rounded-full"></div>
                <ul class="mt-2 space-y-4 text-sm flex-grow">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        5 Subjects
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        50 Students
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Advanced classroom management
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Priority email support
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Quiz functionality
                    </li>
                </ul>
                <button onclick="openSubscriptionModal('Premium')" class="mt-8 bg-gradient-to-r from-[#4473be] to-[#7da3e0] text-white font-bold py-3 px-6 rounded-full hover:from-[#3a5fa0] hover:to-[#5b86cc] transition shadow-lg">
                    Get Started
                </button>
            </div>

            <!-- Pro Plan -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-8 shadow-xl flex flex-col text-[#4473be] hover:shadow-2xl transition backdrop-blur-sm border border-gray-100 dark:border-gray-800 pricing-card">
                <h3 class="text-xl font-bold">Pro</h3>
                <p class="mt-2 text-3xl font-bold">₱4,999 <span class="text-base font-normal">/ month</span></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Billed yearly</p>
                <div class="mt-6 mb-6 h-1 w-20 bg-gradient-to-r from-[#4473be] to-[#7da3e0] rounded-full"></div>
                <ul class="mt-2 space-y-4 text-sm text-[#1b1b18] dark:text-[#EDEDEC] flex-grow">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Unlimited Subjects
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Unlimited  Students
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Complete classroom management
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        All activity types
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Priority 24/7 support
                    </li>
                </ul>
                <button onclick="openSubscriptionModal('Pro')" class="mt-8 bg-[#7da3e0] text-white font-bold py-3 px-6 rounded-full hover:bg-[#5b86cc] transition">
                    Get Started
                </button>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="w-full max-w-7xl px-6 lg:px-8 mb-24 z-10" x-data="{ activeQuestion: null }">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4">
                Frequently Asked <span class="gradient-text">Questions</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                Find answers to common questions about our platform.
            </p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4" data-aos="fade-up">
            <!-- Question 1 -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md overflow-hidden faq-item">
                <button @click="activeQuestion = activeQuestion === 1 ? null : 1" class="flex justify-between items-center w-full p-6 text-left">
                    <h3 class="text-lg font-semibold">How do I get started with Central?</h3>
                    <svg :class="{ 'rotate-180': activeQuestion === 1 }" class="w-5 h-5 text-[#4473be] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeQuestion === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    <p>Getting started is easy! Simply choose a subscription plan that fits your needs, fill out the registration form, and you'll have access to your academy dashboard within minutes. From there, you can customize your academy, create courses, and invite students.</p>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md overflow-hidden faq-item">
                <button @click="activeQuestion = activeQuestion === 2 ? null : 2" class="flex justify-between items-center w-full p-6 text-left">
                    <h3 class="text-lg font-semibold">Can I upgrade or downgrade my plan later?</h3>
                    <svg :class="{ 'rotate-180': activeQuestion === 2 }" class="w-5 h-5 text-[#4473be] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeQuestion === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    <p>Yes, you can upgrade or downgrade your subscription plan at any time from your account settings. When upgrading, you'll immediately gain access to the new features. When downgrading, the changes will take effect at the end of your current billing cycle.</p>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md overflow-hidden faq-item">
                <button @click="activeQuestion = activeQuestion === 3 ? null : 3" class="flex justify-between items-center w-full p-6 text-left">
                    <h3 class="text-lg font-semibold">What payment methods do you accept?</h3>
                    <svg :class="{ 'rotate-180': activeQuestion === 3 }" class="w-5 h-5 text-[#4473be] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeQuestion === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    <p>We accept all major credit cards (Visa, Mastercard, American Express, Discover), PayPal, and bank transfers for annual plans. All payments are processed securely through our payment gateway.</p>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md overflow-hidden faq-item">
                <button @click="activeQuestion = activeQuestion === 4 ? null : 4" class="flex justify-between items-center w-full p-6 text-left">
                    <h3 class="text-lg font-semibold">Is there a free trial available?</h3>
                    <svg :class="{ 'rotate-180': activeQuestion === 4 }" class="w-5 h-5 text-[#4473be] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeQuestion === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    <p>Yes, we offer a 14-day free trial for all our subscription plans. No credit card is required to start your trial. You can explore all the features of your chosen plan during the trial period before making a commitment.</p>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md overflow-hidden faq-item">
                <button @click="activeQuestion = activeQuestion === 5 ? null : 5" class="flex justify-between items-center w-full p-6 text-left">
                    <h3 class="text-lg font-semibold">Can I cancel my subscription at any time?</h3>
                    <svg :class="{ 'rotate-180': activeQuestion === 5 }" class="w-5 h-5 text-[#4473be] transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeQuestion === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-4" class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                    <p>Yes, you can cancel your subscription at any time from your account settings. If you cancel, you'll still have access to your academy until the end of your current billing period. We don't offer refunds for partial months or years of service.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="w-full max-w-7xl px-6 lg:px-8 mb-24 z-10">
        <div class="bg-gradient-to-r from-[#4473be] to-[#7da3e0] rounded-3xl p-12 text-white text-center" data-aos="fade-up">
            <h2 class="text-3xl lg:text-4xl font-bold mb-6">Ready to Transform Your Teaching?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Join thousands of educators who are growing their business with our platform.
            </p>
            <button onclick="openSubscriptionModal('Premium')" class="px-8 py-4 bg-white text-[#4473be] font-bold rounded-full hover:bg-gray-100 transition shadow-lg">
                Get Started Today
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="w-full max-w-7xl px-6 lg:px-8 mb-8 z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div>
                <div class="flex items-center mb-4">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-[#4473be] to-[#7da3e0] flex items-center justify-center text-white font-bold text-xl">C</div>
                    <span class="ml-3 text-xl font-bold gradient-text">Central</span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    The all-in-one platform for instructors and academies to create, manage, and grow their educational business.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-[#4473be] hover:text-[#3a5fa0] transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-[#4473be] hover:text-[#3a5fa0] transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-[#4473be] hover:text-[#3a5fa0] transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-[#4473be] hover:text-[#3a5fa0] transition">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4">Product</h3>
                <ul class="space-y-2">
                    <li><a href="#features" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Features</a></li>
                    <li><a href="#pricing" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Pricing</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Integrations</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Updates</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4">Resources</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Documentation</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Tutorials</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Blog</a></li>
                    <li><a href="#faq" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4">Company</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">About Us</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Careers</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Contact</a></li>
                    <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center text-gray-600 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} Central. All rights reserved.</p>
        </div>
    </footer>

    <!-- Subscription Modal -->
    <div id="subscriptionModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden backdrop-blur-sm" x-data="{ step: 1, plan: '' }">
        <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-8 max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-100 dark:border-gray-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold gradient-text">Subscribe to <span id="planName">Plan</span></h3>
                <button onclick="closeSubscriptionModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Progress Steps -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div :class="{ 'bg-[#4473be]': step >= 1, 'bg-gray-300 dark:bg-gray-700': step < 1 }" class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold">1</div>
                    <div :class="{ 'bg-[#4473be]': step >= 1, 'bg-gray-300 dark:bg-gray-700': step < 1 }" class="h-1 w-12"></div>
                </div>
                <div class="flex items-center">
                    <div :class="{ 'bg-[#4473be]': step >= 2, 'bg-gray-300 dark:bg-gray-700': step < 2 }" class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold">2</div>
                    <div :class="{ 'bg-[#4473be]': step >= 2, 'bg-gray-300 dark:bg-gray-700': step < 2 }" class="h-1 w-12"></div>
                </div>
                <div class="flex items-center">
                    <div :class="{ 'bg-[#4473be]': step >= 3, 'bg-gray-300 dark:bg-gray-700': step < 3 }" class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold">3</div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('subscription.apply') }}" method="POST" class="space-y-4" id="subscriptionForm"
                x-data="{
                    formValid: false,
                    submitting: false,
                    errors: {},
                    submitForm() {
                        this.submitting = true;
                        this.errors = {};

                        // Get form data
                        const form = document.getElementById('subscriptionForm');
                        const formData = new FormData(form);

                        // Submit using fetch
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.submitting = false;

                            if (data.success) {
                                // Show success message
                                showSnackbar(data.message || 'Subscription successful! Please wait for the email when your application is ready.', 'success');

                                // Reset form and close modal
                                form.reset();
                                closeSubscriptionModal();
                            } else if (data.errors) {
                                // Handle validation errors
                                this.errors = data.errors;
                                // Scroll to first error
                                setTimeout(() => {
                                    const firstError = document.querySelector('.text-red-500');
                                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }, 100);
                            } else {
                                // Handle other errors
                                showSnackbar(data.message || 'An error occurred. Please try again.', 'error');
                            }
                        })
                        .catch(error => {
                            this.submitting = false;
                            showSnackbar('An error occurred. Please try again.', 'error');
                            console.error('Submission error:', error);
                        });
                    }
                }"
                @submit.prevent="submitForm">
                @csrf
                <input type="hidden" name="subscription_plan" id="subscriptionPlan">

                <!-- Step 1: Company Information -->
                <div x-show="step === 1">
                    <h4 class="text-lg font-semibold mb-4 text-[#4473be] dark:text-[#7da3e0]">Company Information</h4>

                    <div class="mb-4">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                        <input type="text" name="company_name" id="company_name" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#111] text-gray-900 dark:text-white shadow-sm focus:border-[#4473be] focus:ring focus:ring-[#4473be] focus:ring-opacity-50">
                        @error('company_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-red-500 text-xs mt-1" x-show="errors.company_name" x-text="errors.company_name"></p>
                    </div>

                    <div class="mb-4">
                        <label for="domain_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Domain Name</label>
                        <div class="flex rounded-lg shadow-sm">
                            <input type="text" name="domain_name" id="domain_name" required class="flex-1 min-w-0 block w-full px-4 py-3 rounded-l-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#111] text-gray-900 dark:text-white focus:border-[#4473be] focus:ring focus:ring-[#4473be] focus:ring-opacity-50">
                            <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                .{{ config('app.domain') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This will be your unique subdomain for accessing the application.</p>
                        @error('domain_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-red-500 text-xs mt-1" x-show="errors.domain_name" x-text="errors.domain_name"></p>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" @click="step = 2" class="px-6 py-2 bg-[#4473be] text-white rounded-lg hover:bg-[#3a5fa0] transition">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Personal Information -->
                <div x-show="step === 2">
                    <h4 class="text-lg font-semibold mb-4 text-[#4473be] dark:text-[#7da3e0]">Personal Information</h4>

                    <div class="mb-4">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                        <input type="text" name="full_name" id="full_name" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#111] text-gray-900 dark:text-white shadow-sm focus:border-[#4473be] focus:ring focus:ring-[#4473be] focus:ring-opacity-50">
                        @error('full_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-red-500 text-xs mt-1" x-show="errors.full_name" x-text="errors.full_name"></p>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#111] text-gray-900 dark:text-white shadow-sm focus:border-[#4473be] focus:ring focus:ring-[#4473be] focus:ring-opacity-50">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></p>
                    </div>

                    <div class="mb-4">
                        <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Number</label>
                        <input type="text" name="contact" id="contact" required class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-[#111] text-gray-900 dark:text-white shadow-sm focus:border-[#4473be] focus:ring focus:ring-[#4473be] focus:ring-opacity-50">
                        @error('contact')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-red-500 text-xs mt-1" x-show="errors.contact" x-text="errors.contact"></p>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" @click="step = 1" class="px-6 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" @click="step = 3" class="px-6 py-2 bg-[#4473be] text-white rounded-lg hover:bg-[#3a5fa0] transition">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Review & Submit -->
                <div x-show="step === 3">
                    <h4 class="text-lg font-semibold mb-4 text-[#4473be] dark:text-[#7da3e0]">Review & Submit</h4>

                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Plan:</span>
                            <span class="font-medium" id="reviewPlan">Premium</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Company:</span>
                            <span class="font-medium" x-text="document.getElementById('company_name').value"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Domain:</span>
                            <span class="font-medium" x-text="document.getElementById('domain_name').value + '.{{ config('app.domain') }}'"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Name:</span>
                            <span class="font-medium" x-text="document.getElementById('full_name').value"></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Email:</span>
                            <span class="font-medium" x-text="document.getElementById('email').value"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Contact:</span>
                            <span class="font-medium" x-text="document.getElementById('contact').value"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" required class="rounded border-gray-300 text-[#4473be] shadow-sm focus:border-[#4473be] focus:ring focus:ring-[#4473be] focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">I agree to the <a href="#" class="text-[#4473be] hover:underline">Terms of Service</a> and <a href="#" class="text-[#4473be] hover:underline">Privacy Policy</a></span>
                        </label>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" @click="step = 2" class="px-6 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#4473be] to-[#7da3e0] text-white rounded-lg hover:from-[#3a5fa0] hover:to-[#5b86cc] transition shadow-md" :disabled="submitting">
                            <template x-if="!submitting">
                                <span><i class="fas fa-check mr-2"></i> Submit Application</span>
                            </template>
                            <template x-if="submitting">
                                <span><i class="fas fa-spinner fa-spin mr-2"></i> Submitting...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init();

            // Check for success message in URL params (for when redirected back)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                // Create and show a snackbar
                const message = urlParams.get('message') || 'Subscription successful! Please wait for the email when your application is ready.';
                showSnackbar(message, 'success');
            }
        });

        function openSubscriptionModal(plan) {
            document.getElementById('subscriptionModal').classList.remove('hidden');
            document.getElementById('planName').textContent = plan;
            document.getElementById('reviewPlan').textContent = plan;
            document.getElementById('subscriptionPlan').value = plan;
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        function closeSubscriptionModal() {
            document.getElementById('subscriptionModal').classList.add('hidden');
            document.body.style.overflow = ''; // Re-enable scrolling
        }

        function showSnackbar(message, type = 'success') {
            // Create snackbar element
            const snackbar = document.createElement('div');
            snackbar.className = `fixed bottom-4 right-4 z-50 flex items-center p-4 mb-4 rounded-lg shadow-lg cursor-pointer ${
                type === 'success'
                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border-l-4 border-green-500'
                    : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-l-4 border-red-500'
            }`;

            // Icon container
            const iconContainer = document.createElement('div');
            iconContainer.className = `inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${
                type === 'success'
                    ? 'text-green-500 bg-green-100/50 dark:bg-green-800/30 dark:text-green-200'
                    : 'text-red-500 bg-red-100/50 dark:bg-red-800/30 dark:text-red-200'
            } rounded-lg`;

            // Icon
            const icon = document.createElement('div');
            icon.innerHTML = type === 'success'
                ? '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>'
                : '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.5 11.5a1 1 0 0 1-2 0v-4a1 1 0 0 1 2 0v4Zm-3.5-2a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/></svg>';
            iconContainer.appendChild(icon);

            // Message
            const messageElement = document.createElement('div');
            messageElement.className = 'ml-3 text-sm font-normal';
            messageElement.textContent = message;

            // Assemble snackbar
            snackbar.appendChild(iconContainer);
            snackbar.appendChild(messageElement);

            // Add to document
            document.body.appendChild(snackbar);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                snackbar.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => {
                    document.body.removeChild(snackbar);
                }, 300);
            }, 5000);

            // Click to dismiss
            snackbar.addEventListener('click', () => {
                snackbar.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => {
                    document.body.removeChild(snackbar);
                }, 300);
            });
        }

        // Form submission is now handled by Alpine.js
    </script>
</body>
</html>
