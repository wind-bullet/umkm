@extends('layouts.app')

@section('title', $product->name . ' - UMKMART')

@section('content')
<!-- Breadcrumb -->
<nav class="flex text-xs text-slate-400 font-semibold mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 flex items-center gap-1">
                <span class="material-icons text-sm">home</span> Beranda
            </a>
        </li>
        <li>
            <div class="flex items-center gap-1">
                <span class="material-icons text-sm">chevron_right</span>
                <a href="{{ route('category', $product->category->slug) }}" class="hover:text-emerald-600">{{ $product->category->name }}</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center gap-1">
                <span class="material-icons text-sm">chevron_right</span>
                <span class="text-slate-700 dark:text-slate-350 truncate max-w-48">{{ $product->name }}</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Main Detail -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 mb-12">
    <!-- Product Images -->
    <div class="flex flex-col gap-4">
        @php
            $imageUrl = '/desain_sample/screen1.png';
            if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
                $imageUrl = '/uploads/products/' . $product->image;
            }
        @endphp
        <div class="aspect-square rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-150 dark:border-slate-850">
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/600x600?text=UMKMART'">
        </div>
    </div>
    
    <!-- Product Actions & Info -->
    <div class="flex flex-col justify-start text-left">
        <!-- Category & Voucher Badge -->
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-emerald-100 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-350 font-extrabold text-xs uppercase tracking-wider px-3 py-1 rounded-full">
                {{ $product->category->name }}
            </span>
            
            @if($product->category->slug === 'voucher' && $product->voucherItems->first())
                <span class="bg-amber-100 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 font-extrabold text-xs px-3 py-1 rounded-full flex items-center gap-1">
                    <span class="material-icons text-xs">confirmation_number</span>
                    {{ $product->voucherItems->first()->voucher_label }}
                </span>
            @endif
        </div>
        
        <!-- Name -->
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white leading-tight mb-2">
            {{ $product->name }}
        </h1>
        
        <!-- Rating & Reviews count -->
        <div class="flex items-center gap-2 mb-4">
            <div class="flex items-center gap-0.5 text-amber-400">
                @for($i = 1; $i <= 5; $i++)
                    <span class="material-icons text-sm">{{ $i <= round($product->rating) ? 'star' : 'star_border' }}</span>
                @endfor
            </div>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ number_format($product->rating, 1) }}</span>
            <span class="text-xs text-slate-400">|</span>
            <span class="text-xs text-slate-500 font-semibold">{{ $product->reviews->count() }} Ulasan</span>
        </div>
        
        <!-- Price -->
        <div class="bg-slate-100/50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-850 p-6 rounded-2xl mb-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-2">Harga Spesial</p>
            <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 leading-none">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
        
        <!-- Description Summary -->
        <div class="mb-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase mb-2">Deskripsi Produk</h3>
            <p class="text-slate-600 dark:text-slate-350 text-sm leading-relaxed">{{ $product->description }}</p>
        </div>
        
        <!-- Cart Add Form -->
        <div class="border-t border-slate-100 dark:border-slate-850 pt-6 mt-auto">
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-stretch">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <!-- Qty adjuster -->
                    <div class="flex items-center justify-between border border-slate-200 dark:border-slate-800 rounded-xl px-2 py-1 w-full sm:w-32 bg-white dark:bg-slate-950">
                        <button type="button" onclick="adjustQty(-1)" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-850 flex items-center justify-center text-slate-500"><span class="material-icons text-sm">remove</span></button>
                        <input type="number" name="qty" id="qty-input" value="1" min="1" max="{{ $product->stock }}" class="w-10 text-center font-bold text-sm bg-transparent border-none focus:outline-none focus:ring-0 text-slate-800 dark:text-white">
                        <button type="button" onclick="adjustQty(1)" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-850 flex items-center justify-center text-slate-500"><span class="material-icons text-sm">add</span></button>
                    </div>
                    
                    <!-- Add to Cart Button -->
                    <button type="submit" class="flex-grow bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">shopping_cart</span>
                        <span>Tambah ke Keranjang</span>
                    </button>
                    
                    <!-- Beli Langsung Button -->
                    <button type="submit" formaction="{{ route('buy_now') }}" class="flex-grow bg-slate-900 hover:bg-black dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">flash_on</span>
                        <span>Beli Langsung</span>
                    </button>
                </form>
                <p class="text-[10px] text-slate-400 mt-2 font-semibold flex items-center gap-1">
                    <span class="material-icons text-xs text-emerald-500">check_circle</span> 
                    Stok tersedia: <b>{{ $product->stock }} unit</b>
                </p>
            @else
                <button disabled class="w-full bg-slate-200 dark:bg-slate-800 text-slate-400 font-bold py-3.5 px-6 rounded-xl cursor-not-allowed text-center">
                    Stok Produk Habis
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Details & Reviews Tab -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
    <!-- Reviews Section (Left/Main) -->
    <div class="lg:col-span-2">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
            <span class="material-icons text-amber-500">rate_review</span>
            Ulasan Pelanggan ({{ $product->reviews->count() }})
        </h2>
        
        @php
            $canReview = false;
            if (Auth::check()) {
                $canReview = \App\Models\Order::where('user_id', Auth::id())
                    ->where(function($q) {
                        $q->where('confirmation_requested', true)
                          ->orWhere('confirmed_received', true);
                    })
                    ->whereHas('items', function($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();
            }
        @endphp

        @if($canReview)
            <div id="write-review" class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-2xl text-left shadow-sm mb-6">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 pb-2 border-b border-slate-100 dark:border-slate-850 flex items-center gap-1.5">
                    <span class="material-icons text-emerald-600 dark:text-emerald-400">rate_review</span>
                    Tulis Ulasan Anda
                </h3>
                <form action="{{ route('product.review.store', $product->id) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Rating</label>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="rating" id="star-{{ $i }}" value="{{ $i }}" class="hidden star-radio" {{ $i === 5 ? 'checked' : '' }}>
                                <label for="star-{{ $i }}" class="cursor-pointer text-slate-300 dark:text-slate-600 hover:text-amber-400 transition-colors star-label" data-index="{{ $i }}">
                                    <span class="material-icons text-2xl">star</span>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <label for="review_text" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">Ulasan</label>
                        <textarea name="review_text" id="review_text" rows="3" placeholder="Bagikan pengalaman Anda menggunakan produk ini..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2 px-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-slate-200"></textarea>
                    </div>
                    <button type="submit" class="self-start bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-5 rounded-xl text-xs transition-colors shadow-md shadow-emerald-600/10">
                        Kirim Ulasan
                    </button>
                </form>
            </div>
        @endif
        
        <div class="flex flex-col gap-4">
            @forelse($product->reviews as $review)
                <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-2xl text-left shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-emerald-600 font-bold text-sm">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-800 dark:text-white">{{ $review->user->name }}</h4>
                                <p class="text-[9px] text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-icons text-xs">{{ $i <= $review->rating ? 'star' : 'star_border' }}</span>
                            @endfor
                        </div>
                    </div>
                    
                    <p class="text-xs text-slate-655 dark:text-slate-350 leading-relaxed">{{ $review->review_text }}</p>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-8 rounded-2xl text-center shadow-sm">
                    <p class="text-xs text-slate-400">Belum ada ulasan untuk produk ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Related Products (Right) -->
    <div class="lg:col-span-1">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
            <span class="material-icons text-emerald-600 dark:text-emerald-400">view_carousel</span>
            Produk Terkait
        </h2>
        
        <div class="flex flex-col gap-4">
            @forelse($relatedProducts as $rel)
                <a href="{{ route('product.detail', $rel->id) }}" class="flex items-center gap-3 p-3 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 flex-shrink-0">
                        @if($rel->image && file_exists(public_path('uploads/products/' . $rel->image)))
                            <img src="/uploads/products/{{ $rel->image }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=UMKMART'">
                        @else
                            <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=UMKMART'">
                        @endif
                    </div>
                    <div class="text-left min-w-0">
                        <h4 class="font-bold text-xs text-slate-800 dark:text-white truncate">{{ $rel->name }}</h4>
                        <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-1">Rp {{ number_format($rel->price, 0, ',', '.') }}</p>
                        <div class="flex items-center text-amber-400 text-[10px] mt-0.5">
                            <span class="material-icons text-[10px] mr-0.5">star</span>
                            <span>{{ number_format($rel->rating, 1) }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-xs text-slate-400">Tidak ada produk terkait.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function adjustQty(val) {
        const input = document.getElementById('qty-input');
        if (input) {
            let current = parseInt(input.value);
            let next = current + val;
            let min = parseInt(input.min) || 1;
            let max = parseInt(input.max) || 999;
            
            if (next >= min && next <= max) {
                input.value = next;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const starLabels = document.querySelectorAll('.star-label');
        if (starLabels.length > 0) {
            starLabels.forEach((label) => {
                label.addEventListener('click', () => {
                    const rating = parseInt(label.getAttribute('data-index'));
                    updateStars(rating);
                });
            });

            function updateStars(rating) {
                starLabels.forEach((label) => {
                    const idx = parseInt(label.getAttribute('data-index'));
                    const starIcon = label.querySelector('.material-icons');
                    if (idx <= rating) {
                        starIcon.classList.remove('text-slate-350');
                        starIcon.classList.remove('text-slate-300');
                        starIcon.classList.remove('dark:text-slate-650');
                        starIcon.classList.remove('dark:text-slate-600');
                        starIcon.classList.add('text-amber-400');
                    } else {
                        starIcon.classList.remove('text-amber-400');
                        if (document.documentElement.classList.contains('dark')) {
                            starIcon.classList.add('text-slate-600');
                        } else {
                            starIcon.classList.add('text-slate-300');
                        }
                    }
                });
            }

            // Set initial state based on checks
            updateStars(5);
        }
    });
</script>
@endsection
