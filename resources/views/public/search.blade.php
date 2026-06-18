@extends('layouts.app')

@section('title', 'Pencarian: ' . ($q ?: 'Semua Produk') . ' - UMKMART')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <nav class="flex text-xs text-slate-400 font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600 flex items-center gap-1">
                        <span class="material-icons text-sm">home</span> Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center gap-1">
                        <span class="material-icons text-sm">chevron_right</span>
                        <span class="text-slate-700 dark:text-slate-350">Hasil Pencarian</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
            <span class="material-icons text-emerald-600 dark:text-emerald-400">search</span>
            Hasil Pencarian: "{{ $q ?: 'Semua Produk' }}"
        </h1>
        
        <!-- Mobile Search Form -->
        <div class="block md:hidden mt-3">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari produk..." class="w-full bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-full py-2.5 pl-4 pr-10 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-slate-200">
                <button type="submit" class="absolute right-3 top-2.5 text-slate-400 hover:text-emerald-600">
                    <span class="material-icons text-sm">search</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Sorting -->
    <div class="flex items-center gap-2">
        <label for="sort-select" class="text-xs font-bold text-slate-400 uppercase flex-shrink-0">Urutkan:</label>
        <select id="sort-select" onchange="applyFilters()" class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-slate-200">
            <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Terbaru</option>
            <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
            <option value="rating_desc" {{ $sort == 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
        </select>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-8 text-left">
    
    <!-- Filter Sidebar (Left) -->
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 shadow-sm sticky top-24">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-850">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 text-sm">
                    <span class="material-icons text-sm">filter_alt</span> Filter Produk
                </h3>
                <a href="{{ route('search', ['q' => $q]) }}" class="text-[10px] font-bold text-rose-500 hover:underline">Reset</a>
            </div>
            
            <form id="filter-form" action="{{ route('search') }}" method="GET" class="flex flex-col gap-5">
                <input type="hidden" name="q" value="{{ $q }}">
                <input type="hidden" name="sort" id="filter-sort" value="{{ $sort }}">
                
                <!-- Price Range -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-3">Rentang Harga</h4>
                    <div class="flex flex-col gap-2">
                        <div class="relative">
                            <span class="text-xs text-slate-400 absolute left-3 top-2 font-semibold">Min</span>
                            <input type="number" name="min_price" id="min-price" value="{{ request('min_price') }}" placeholder="10.000" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-850 rounded-xl py-1.5 pl-12 pr-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-slate-200">
                        </div>
                        <div class="relative">
                            <span class="text-xs text-slate-400 absolute left-3 top-2 font-semibold">Max</span>
                            <input type="number" name="max_price" id="max-price" value="{{ request('max_price') }}" placeholder="500.000" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-850 rounded-xl py-1.5 pl-12 pr-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-slate-200">
                        </div>
                    </div>
                </div>
                
                <!-- Kategori Filter -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-3">Kategori</h4>
                    <div class="flex flex-col gap-2">
                        @foreach($categories as $cat)
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                                    {{ is_array(request('categories')) && in_array($cat->id, request('categories')) ? 'checked' : '' }} 
                                    class="w-4 h-4 rounded border-slate-300 dark:border-slate-850 text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    {{ $cat->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Rating -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-3">Rating Minimum</h4>
                    <div class="flex flex-col gap-2">
                        @for($i = 5; $i >= 3; $i--)
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" {{ request('rating') == $i ? 'checked' : '' }} class="w-4 h-4 rounded-full border-slate-350 text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 text-xs font-semibold text-slate-600 dark:text-slate-300 flex items-center gap-0.5">
                                    {{ $i }} <span class="material-icons text-amber-400 text-xs">star</span> & Ke atas
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/10">
                    Terapkan Filter
                </button>
            </form>
        </div>
    </div>
    
    <!-- Products Grid (Right) -->
    <div class="flex-grow">
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product, 'showBuyButton' => false])
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-12 text-center shadow-sm">
                <span class="material-icons text-slate-300 dark:text-slate-700 text-6xl">search_off</span>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-4">Tidak Ada Hasil</h3>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1 max-w-sm mx-auto">
                    Maaf, tidak ada produk ditemukan dengan kata kunci "{{ $q }}". Silakan cari kata kunci lain.
                </p>
                <a href="{{ route('search') }}" class="inline-block mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-full text-xs transition-colors">
                    Lihat Semua Produk
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function applyFilters() {
        const sortSelect = document.getElementById('sort-select');
        const filterSort = document.getElementById('filter-sort');
        const filterForm = document.getElementById('filter-form');
        
        if (sortSelect && filterSort && filterForm) {
            filterSort.value = sortSelect.value;
            filterForm.submit();
        }
    }
</script>
@endsection
