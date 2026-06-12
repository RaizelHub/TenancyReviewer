<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || window.matchMedia('(prefers-color-scheme: dark)').matches }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); if (val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } }); if (darkMode) { document.documentElement.classList.add('dark'); }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ tenant() ? tenant()->name : config('app.name', 'Tenant Portal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
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
                opacity: 0.15;
                filter: blur(80px);
            }
            .bg-enhance::before {
                width: 300px;
                height: 300px;
                background: #7da3e0;
                top: -50px;
                left: -50px;
                animation: float 8s ease-in-out infinite;
            }
            .bg-enhance::after {
                width: 350px;
                height: 350px;
                background: #ffe5f3;
                bottom: -80px;
                right: -50px;
                animation: float 10s ease-in-out infinite reverse;
            }

            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }

            .gradient-text {
                background: linear-gradient(to right, #4473be, #7da3e0);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                color: transparent;
            }

            .feature-card {
                transition: all 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
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

        <!-- Header/Navigation -->
        <header class="w-full max-w-7xl px-6 lg:px-8 py-4 flex items-center justify-between z-10">
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-xl font-bold gradient-text">{{ tenant() ? tenant()->name : config('app.name', 'Tenant Portal') }}</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-4">
                @routeCheck('login')
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] transition text-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login', [], false) }}" class="px-5 py-2 border border-[#4473be] text-[#4473be] rounded-full hover:bg-[#4473be] hover:text-white transition text-sm">
                            Log in
                        </a>
                    @endauth
                @endrouteCheck

                <!-- Dark mode toggle -->
                <button @click="darkMode = !darkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i class="fas" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-700'"></i>
                </button>
            </nav>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </header>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden fixed top-16 inset-x-0 bg-white dark:bg-[#1a1a1a] shadow-lg rounded-b-xl z-20 p-4">
            <nav class="flex flex-col gap-3">
                @routeCheck('login')
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] transition text-center text-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login', [], false) }}" class="px-4 py-2 border border-[#4473be] text-[#4473be] rounded-full hover:bg-[#4473be] hover:text-white transition text-center text-sm">
                            Log in
                        </a>
                    @endauth
                @endrouteCheck

                <!-- Dark mode toggle -->
                <button @click="darkMode = !darkMode" class="flex items-center justify-center gap-2 px-4 py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i class="fas" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-700'"></i>
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>
            </nav>
        </div>

        <!-- Hero Section -->
        <section class="w-full max-w-7xl px-6 lg:px-8 py-16 md:py-24 z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6">
                        Welcome to <span class="gradient-text">{{ tenant() ? tenant()->name : 'Your Tenant Portal' }}</span>
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Access your personalized dashboard, manage your account, and explore all the features available to you.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] transition text-center text-sm">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login', [], false) }}" class="px-6 py-3 bg-[#4473be] text-white rounded-full hover:bg-[#3a5fa0] transition text-center text-sm">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="lg:w-1/2 relative" data-aos="fade-left">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#4473be]/10 rounded-full filter blur-xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-[#7da3e0]/10 rounded-full filter blur-xl"></div>
                    <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Tenant Portal" class="rounded-2xl shadow-2xl w-full">
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="w-full max-w-7xl px-6 lg:px-8 py-16 z-10">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-4">Features & Benefits</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    Discover all the powerful tools and features available to you through our tenant portal.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-[#1a1a1a] p-6 rounded-xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-tachometer-alt text-xl text-[#4473be]"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Personalized Dashboard</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Access your custom dashboard with all your important information and tools in one place.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-[#1a1a1a] p-6 rounded-xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-xl text-[#4473be]"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Secure Access</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Your data is protected with enterprise-grade security and authentication protocols.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-[#1a1a1a] p-6 rounded-xl shadow-lg feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-[#4473be]/10 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-xl text-[#4473be]"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Real-time Analytics</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Monitor your performance and track important metrics with our intuitive analytics tools.
                    </p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="w-full max-w-7xl px-6 lg:px-8 py-16 z-10">
            <div class="bg-gradient-to-r from-[#4473be] to-[#7da3e0] rounded-2xl p-8 md:p-12 text-white text-center" data-aos="fade-up">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-lg mb-6 max-w-3xl mx-auto">
                    Join our platform today and discover all the benefits of our tenant portal.
                </p>
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-white text-[#4473be] font-bold rounded-full hover:bg-gray-100 transition shadow-lg text-sm">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login', [], false) }}" class="px-6 py-3 bg-white text-[#4473be] font-bold rounded-full hover:bg-gray-100 transition shadow-lg text-sm">
                        Sign In
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        <footer class="w-full max-w-7xl px-6 lg:px-8 py-8 border-t border-gray-200 dark:border-gray-800 mt-auto z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ tenant() ? tenant()->name : config('app.name', 'Tenant Portal') }}. All rights reserved.
                </div>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS
                AOS.init();
            });
        </script>
    </body>
</html>
