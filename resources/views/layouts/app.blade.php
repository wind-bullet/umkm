<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UMKMART - E-Commerce UMKM')</title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        /* Glassmorphism style for active state cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass-card {
            background: rgba(31, 41, 55, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-300">
    
    <!-- Navbar -->
    @include('components.navbar')
    
    <!-- Main Content -->
    <main class="flex-grow pt-20 pb-24 md:pb-12 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div id="alert-success" class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                <span class="material-icons">check_circle</span>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <span class="material-icons text-sm">close</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div id="alert-error" class="mb-4 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 flex items-center gap-2">
                <span class="material-icons">error</span>
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('alert-error').remove()" class="ml-auto text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <span class="material-icons text-sm">close</span>
                </button>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="hidden md:block bg-white dark:bg-slate-950 border-t border-slate-100 dark:border-slate-850 py-8 text-center text-sm text-slate-500 dark:text-slate-400 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <span class="font-bold text-slate-800 dark:text-white">UMK</span><span class="font-bold text-emerald-600 dark:text-emerald-400">MART</span> &copy; 2026. Prototipe E-Commerce UMKM.
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-emerald-600">Tentang Kami</a>
                <a href="#" class="hover:text-emerald-600">Bantuan</a>
                <a href="#" class="hover:text-emerald-600">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>
    
    <!-- Mobile Bottom Navigation -->
    @include('components.bottom-nav')
    
    <!-- Scripts -->
    <script>
        // Global Theme Toggle
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const icon = document.getElementById('theme-toggle-icon');
            if (icon) {
                if (document.documentElement.classList.contains('dark')) {
                    icon.textContent = 'light_mode';
                    icon.classList.add('text-amber-400');
                } else {
                    icon.textContent = 'dark_mode';
                    icon.classList.remove('text-amber-400');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcon);
        
        // Polling Notification Bell Badge
        @auth
        function pollNotificationsCount() {
            fetch('/api/notifications/count')
                .then(res => res.json())
                .then(data => {
                    const badges = document.querySelectorAll('.notification-badge');
                    badges.forEach(badge => {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    });
                });
        }
        
        pollNotificationsCount();
        setInterval(pollNotificationsCount, 5000); // Poll every 5s
        @endauth
    </script>
    @yield('scripts')
</body>
</html>
