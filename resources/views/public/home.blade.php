@extends('layouts.app')

@section('title', 'AstridMart - Belanja Produk UMKM Terbaik')

@section('content')
<!-- Hero Section -->
<div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-emerald-600 to-teal-800 text-white mb-8 shadow-xl shadow-emerald-700/10">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-teal-400/20 blur-3xl"></div>
    
    <div class="relative z-10 px-8 py-16 md:p-20 max-w-2xl flex flex-col items-start gap-4">
        <span class="bg-emerald-500/30 text-emerald-300 font-extrabold text-xs uppercase tracking-widest px-3.5 py-1 rounded-full border border-emerald-400/20">Prototipe AstridMart</span>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight text-white">
            Dukung Produk Lokal, Belanja dari UMKM
        </h1>
        <p class="text-emerald-100/90 text-sm md:text-base leading-relaxed max-w-lg">
            Temukan barang fashion, aksesoris premium, jajanan kuliner nusantara, dan voucher digital menarik hanya di AstridMart.
        </p>
        <a href="#katalog-section" class="mt-4 bg-white text-emerald-800 hover:bg-emerald-50 font-bold py-3 px-6 rounded-full transition-all duration-300 shadow-lg flex items-center gap-2">
            <span>Mulai Belanja</span>
            <span class="material-icons text-sm">arrow_forward</span>
        </a>
    </div>
</div>

<!-- Category Pills Section (4 Kartu Kategori Lonjong) -->
<div class="mb-12">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
        <span class="material-icons text-emerald-600 dark:text-emerald-400">grid_view</span>
        Pilih Kategori Belanja
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $cat)
            @php
                $isVoucher = $cat->slug === 'voucher';
            @endphp
            <a href="{{ route('category', $cat->slug) }}" 
               class="flex items-center gap-3 px-6 py-4 rounded-full transition-all duration-300 shadow-sm
               {{ $isVoucher 
                   ? 'bg-transparent border-2 border-dashed border-emerald-600 dark:border-emerald-400 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/20' 
                   : 'bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 text-slate-700 dark:text-slate-200 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:hover:text-white hover:border-emerald-600 dark:hover:border-emerald-600 hover:scale-[1.02]' }}">
                
                <span class="material-icons text-2xl flex-shrink-0">{{ $cat->icon }}</span>
                <div class="text-left">
                    <p class="text-sm font-bold leading-none">{{ $cat->name }}</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 leading-none mt-1 hover:text-emerald-100">Katalog</p>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Featured Products (Terpopuler) -->
<div class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <span class="material-icons text-amber-500">local_fire_department</span>
            Rekomendasi Terpopuler
        </h2>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($featuredProducts->take(4) as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</div>

<!-- Promo Banner Section -->
<div class="rounded-3xl border border-slate-100 dark:border-slate-850 bg-white dark:bg-slate-950 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 mb-12 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-950/20 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
            <span class="material-icons text-3xl">local_offer</span>
        </div>
        <div class="text-left">
            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Promo Kopi Kenangan & Bioskop XXI</h3>
            <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">Dapatkan potongan voucher diskon hingga Rp 50.000 hanya di kategori Voucher!</p>
        </div>
    </div>
    <a href="{{ route('category', 'voucher') }}" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 px-6 rounded-full text-center transition-colors shadow-lg shadow-amber-500/10">
        Klaim Voucher
    </a>
</div>

<!-- Latest Products (Terbaru) -->
<div id="katalog-section" class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <span class="material-icons text-emerald-600 dark:text-emerald-400">new_releases</span>
            Produk Terbaru
        </h2>
        <a href="{{ route('search') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-0.5">
            <span>Lihat Semua</span>
            <span class="material-icons text-[14px]">chevron_right</span>
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($latestProducts as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endsection
