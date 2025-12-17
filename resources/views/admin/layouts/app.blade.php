<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Moeww - Fashion Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    {{-- In your admin.layouts.app or in this file --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'Ocean': '#586879',
                        'Wave': '#92a4ac',
                        'Pearl': '#f8f6f4',
                        'Silk': '#e8e2dc',
                        'Lace': '#f5f1ed',
                    }
                }
            }
        }
    </script>

    @stack('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
            min-height: 100vh;
        }

        .fashion-font {
            font-family: 'Playfair Display', serif;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #586879 0%, #92a4ac 100%);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(88, 104, 121, 0.2);
        }

        /* Loading Spinner */
        .loading-spinner {
            border: 3px solid rgba(88, 104, 121, 0.1);
            border-top-color: #586879;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(88, 104, 121, 0.05);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(88, 104, 121, 0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(88, 104, 121, 0.4);
        }

        /* Utility Classes */
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">

    <!-- Mobile Menu Button -->
    <button id="mobileMenuButton"
        class="lg:hidden fixed top-6 left-6 z-50 bg-white text-Ocean p-3 rounded-xl shadow-md hover:shadow-lg transition-all">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <!-- Sidebar -->
    @include('admin.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-80 min-h-screen">
        <!-- Loading Overlay -->
        <div id="loadingOverlay"
            class="hidden fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white p-8 rounded-2xl text-center shadow-xl">
                <div class="loading-spinner w-16 h-16 rounded-full mx-auto mb-4"></div>
                <p class="text-Ocean font-medium">Loading...</p>
            </div>
        </div>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 50
            });
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuButton')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>

</html>
