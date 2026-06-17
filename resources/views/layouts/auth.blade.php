<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk - UMKMART')</title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-stretch">
    <!-- Left Hero Image (Hidden on Mobile) -->
    <div class="hidden lg:flex w-1/2 bg-emerald-700 text-white flex-col justify-between p-12 relative overflow-hidden">
        <!-- Background graphics -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/30 to-emerald-950/40"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-teal-500/20 blur-3xl"></div>
        
        <div class="relative z-10 flex items-center gap-2">
            <span class="material-icons text-3xl text-emerald-300">shopping_basket</span>
            <span class="text-2xl font-bold tracking-tight text-white">UMK</span><span class="text-2xl font-bold tracking-tight text-emerald-300">MART</span>
        </div>
        
        <div class="relative z-10 my-auto max-w-md">
            <h2 class="text-4xl font-extrabold leading-tight mb-4 text-emerald-100">
                Pintu Masuk Katalog UMKM Terlengkap
            </h2>
            <p class="text-emerald-200/90 text-lg leading-relaxed">
                Belanja produk lokal kualitas ekspor dari kategori fashion, aksesoris, kuliner, hingga voucher diskon belanja.
            </p>
        </div>
        
        <div class="relative z-10 text-emerald-300 text-sm">
            &copy; 2026 UMKMART. Project Prototype E-Commerce UMKM.
        </div>
    </div>
    
    <!-- Right Form (Full Width on Mobile) -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 md:p-20 bg-white">
        <div class="w-full max-w-md">
            <!-- Header for mobile -->
            <div class="flex lg:hidden items-center gap-2 mb-8 justify-center">
                <span class="material-icons text-3xl text-emerald-600">shopping_basket</span>
                <span class="text-2xl font-bold text-slate-800">UMK</span><span class="text-2xl font-bold text-emerald-600">MART</span>
            </div>
            
            @yield('content')
        </div>
    </div>
</body>
</html>
