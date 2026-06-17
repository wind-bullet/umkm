<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - UMKMART')</title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        /* Dark mode overrides for admin interface */
        .dark body {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        .dark header {
            background-color: #020617 !important;
            border-color: #1e293b !important;
        }
        .dark header h1 {
            color: #ffffff !important;
        }
        .dark footer {
            background-color: #020617 !important;
            border-color: #1e293b !important;
            color: #94a3b8 !important;
        }
        .dark .bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        .dark .border-slate-150,
        .dark .border-slate-200,
        .dark .border-slate-100 {
            border-color: #334155 !important;
        }
        .dark .text-slate-800,
        .dark .text-slate-850,
        .dark .text-slate-700,
        .dark .text-slate-650,
        .dark .text-slate-900 {
            color: #f1f5f9 !important;
        }
        .dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        .dark .text-slate-400 {
            color: #94a3b8 !important;
        }
        .dark .bg-slate-50 {
            background-color: #0f172a !important;
        }
        .dark .bg-slate-50\/50 {
            background-color: rgba(15, 23, 42, 0.6) !important;
        }
        .dark .bg-slate-50\/20 {
            background-color: rgba(15, 23, 42, 0.3) !important;
        }
        .dark .bg-slate-50\/30 {
            background-color: rgba(15, 23, 42, 0.4) !important;
        }
        .dark .hover\:bg-slate-50:hover {
            background-color: rgba(15, 23, 42, 0.8) !important;
        }
        .dark .bg-emerald-50,
        .dark .bg-emerald-50\/20,
        .dark .bg-emerald-50\/40 {
            background-color: rgba(16, 185, 129, 0.12) !important;
            color: #34d399 !important;
        }
        .dark .bg-rose-50,
        .dark .bg-rose-50\/20 {
            background-color: rgba(244, 63, 94, 0.12) !important;
            color: #fb7185 !important;
        }
        .dark .bg-purple-50,
        .dark .bg-purple-50\/20 {
            background-color: rgba(168, 85, 247, 0.12) !important;
            color: #c084fc !important;
        }
        .dark .bg-blue-50,
        .dark .bg-blue-50\/20 {
            background-color: rgba(59, 130, 246, 0.12) !important;
            color: #60a5fa !important;
        }
        .dark .bg-amber-50,
        .dark .bg-amber-50\/20 {
            background-color: rgba(245, 158, 11, 0.12) !important;
            color: #fbbf24 !important;
        }
        .dark .bg-indigo-50,
        .dark .bg-indigo-50\/20 {
            background-color: rgba(99, 102, 241, 0.12) !important;
            color: #818cf8 !important;
        }
        .dark .border-amber-100, .dark .border-amber-200 {
            border-color: rgba(245, 158, 11, 0.2) !important;
        }
        .dark .border-blue-100, .dark .border-blue-200 {
            border-color: rgba(59, 130, 246, 0.2) !important;
        }
        .dark .border-purple-200 {
            border-color: rgba(168, 85, 247, 0.2) !important;
        }
        .dark .border-indigo-100, .dark .border-indigo-200 {
            border-color: rgba(99, 102, 241, 0.2) !important;
        }
        .dark .border-emerald-200 {
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
        .dark .text-amber-800, .dark .text-amber-700 {
            color: #fbbf24 !important;
        }
        .dark .text-blue-800, .dark .text-blue-700 {
            color: #60a5fa !important;
        }
        .dark .text-purple-800 {
            color: #c084fc !important;
        }
        .dark .text-indigo-800 {
            color: #818cf8 !important;
        }
        .dark .text-emerald-800 {
            color: #34d399 !important;
        }
        .dark input,
        .dark select,
        .dark textarea {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #64748b !important;
        }
        .dark table th {
            color: #94a3b8 !important;
            border-color: #334155 !important;
        }
        .dark table td {
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }
        .dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]) {
            border-color: #334155 !important;
        }
        .dark tr.hover\:bg-slate-50\/50:hover,
        .dark tr:hover {
            background-color: rgba(51, 65, 85, 0.3) !important;
        }
        .dark .bg-emerald-100 {
            background-color: rgba(16, 185, 129, 0.2) !important;
            color: #34d399 !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }
        .dark .text-emerald-800 {
            color: #34d399 !important;
        }
        .dark .bg-slate-100 {
            background-color: rgba(71, 85, 105, 0.2) !important;
            color: #94a3b8 !important;
            border-color: rgba(71, 85, 105, 0.3) !important;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col md:flex-row transition-colors duration-300">
    
    <!-- Admin Sidebar -->
    @include('components.sidebar-admin')
    
    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-h-screen bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
        <!-- Top bar -->
        <header class="bg-white dark:bg-slate-950 border-b border-slate-150 dark:border-slate-800 px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800 dark:text-white">@yield('page_title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <!-- Theme Switcher -->
                <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors mr-2" title="Ubah Tema">
                    <span id="theme-toggle-icon" class="material-icons">dark_mode</span>
                </button>
                <!-- Profile dropdown -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-bold text-sm">
                        A
                    </div>
                    <div class="hidden sm:block text-left text-xs">
                        <p class="font-bold text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</p>
                        <p class="text-slate-400">Administrator</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 ml-2" title="Keluar">
                            <span class="material-icons">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="flex-grow p-6">
            @if(session('success'))
                <div id="alert-success" class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-2">
                    <span class="material-icons">check_circle</span>
                    <span>{{ session('success') }}</span>
                    <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-slate-400 hover:text-slate-600">
                        <span class="material-icons text-sm">close</span>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div id="alert-error" class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-2">
                    <span class="material-icons">error</span>
                    <span>{{ session('error') }}</span>
                    <button onclick="document.getElementById('alert-error').remove()" class="ml-auto text-slate-400 hover:text-slate-600">
                        <span class="material-icons text-sm">close</span>
                    </button>
                </div>
            @endif
            
            @yield('content')
        </main>
        
        <footer class="bg-white dark:bg-slate-950 border-t border-slate-150 dark:border-slate-800 py-4 text-center text-xs text-slate-400 mt-auto">
            &copy; 2026 UMKMART. Dashboard Administrasi Prototipe UMKM.
        </footer>
    </div>
    
    <!-- Theme Toggle Scripts -->
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
    </script>
    
    @yield('scripts')
</body>
</html>
