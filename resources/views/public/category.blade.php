@extends('layouts.app')

@section('title', 'Kategori ' . $category->name . ' - UMKMART')

@section('content')
<!-- Category Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <nav class="flex text-xs text-slate-400 font-semibold mb-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600 flex items-center gap-1">
                        <span class="material-icons text-sm">home</span> Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center gap-1">
                        <span class="material-icons text-sm">chevron_right</span>
                        <span class="text-slate-500">Kategori</span>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center gap-1">
                        <span class="material-icons text-sm">chevron_right</span>
                        <span class="text-slate-700 dark:text-slate-350">{{ $category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
            <span class="material-icons text-emerald-600 dark:text-emerald-400">{{ $category->icon }}</span>
            Katalog Kategori: {{ $category->name }}
        </h1>
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

<!-- Category Pills Switcher -->
<div class="mb-8 overflow-x-auto pb-2 flex gap-3">
    @foreach($categories as $cat)
        @php
            $isVoucher = $cat->slug === 'voucher';
            $isActive = $cat->id === $category->id;
        @endphp
        <a href="{{ route('category', $cat->slug) }}" 
           class="flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-350 flex-shrink-0 shadow-sm
           {{ $isActive 
               ? 'bg-emerald-600 text-white border-none' 
               : ($isVoucher 
                   ? 'bg-transparent border-2 border-dashed border-emerald-600 dark:border-emerald-400 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50' 
                   : 'bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 text-slate-600 dark:text-slate-300 hover:bg-slate-50') }}">
            <span class="material-icons text-base">{{ $cat->icon }}</span>
            <span>{{ $cat->name }}</span>
        </a>
    @endforeach
</div>

<div class="flex flex-col lg:flex-row gap-8">
    
    <!-- Filter Sidebar (Left) -->
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 shadow-sm sticky top-24">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-850">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2 text-sm">
                    <span class="material-icons text-sm">filter_alt</span> Filter Produk
                </h3>
                <a href="{{ route('category', $category->slug) }}" class="text-[10px] font-bold text-rose-500 hover:underline">Reset</a>
            </div>
            
            <form id="filter-form" action="{{ route('category', $category->slug) }}" method="GET" class="flex flex-col gap-5">
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
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-12 text-center shadow-sm">
                <span class="material-icons text-slate-300 dark:text-slate-700 text-6xl">shopping_bag</span>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-4">Belum Ada Produk</h3>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1 max-w-sm mx-auto">
                    Maaf, tidak ada produk ditemukan dengan kriteria filter yang Anda terapkan. Silakan reset filter.
                </p>
                <a href="{{ route('category', $category->slug) }}" class="inline-block mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-full text-xs transition-colors">
                    Reset Semua Filter
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
