@php
    $isVoucher = $product->category && $product->category->slug === 'voucher';
    $imageUrl = '/desain_sample/screen1.png';
    if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
        $imageUrl = '/uploads/products/' . $product->image;
    }
@endphp

<div class="group relative rounded-2xl overflow-hidden transition-all duration-300 {{ $isVoucher ? 'bg-transparent border-2 border-dashed border-emerald-600 dark:border-emerald-400 hover:shadow-lg' : 'bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 hover:shadow-xl' }}">
    <!-- Image -->
    <div class="relative aspect-square overflow-hidden bg-slate-100 dark:bg-slate-900">
        <!-- Overlay for hover zoom -->
        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://placehold.co/400x400?text=UMKMART'">
        
        <!-- Category Badge -->
        <span class="absolute top-3 left-3 bg-emerald-600/90 text-white font-bold text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full backdrop-blur-sm">
            {{ $product->category->name }}
        </span>
        
        <!-- Voucher specific coupon badge -->
        @if($isVoucher && $product->voucherItems->first())
            <span class="absolute top-3 right-3 bg-amber-500 text-slate-950 font-extrabold text-[10px] px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-md">
                <span class="material-icons text-[12px]">confirmation_number</span>
                {{ $product->voucherItems->first()->voucher_label }}
            </span>
        @endif
    </div>
    
    <!-- Details -->
    <div class="p-4 flex flex-col gap-2">
        <!-- Rating -->
        <div class="flex items-center gap-1">
            <span class="material-icons text-amber-400 text-sm">star</span>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ number_format($product->rating, 1) }}</span>
            <span class="text-[10px] text-slate-400">({{ $product->review_count }})</span>
        </div>
        
        <!-- Title -->
        <h3 class="font-bold text-sm text-slate-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-1">
            <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
        </h3>
        
        <!-- Price & Stock -->
        <div class="flex justify-between items-baseline mt-1">
            <span class="text-sm font-extrabold text-slate-900 dark:text-emerald-400">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            <span class="text-[10px] font-semibold {{ $product->stock < 5 ? 'text-rose-500' : 'text-slate-400' }}">
                Stok: {{ $product->stock }}
            </span>
        </div>
        
        <!-- Action Buttons -->
        <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100 dark:border-slate-850">
            <a href="{{ route('product.detail', $product->id) }}" class="flex items-center justify-center gap-1 text-[11px] font-bold py-2 rounded-xl text-slate-600 dark:text-slate-350 bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">
                <span class="material-icons text-[12px]">info</span> Detail
            </a>
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="w-full flex items-center justify-center gap-1 text-[11px] font-bold py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        <span class="material-icons text-[12px]">shopping_cart</span> Beli
                    </button>
                </form>
            @else
                <button disabled class="w-full flex items-center justify-center gap-1 text-[11px] font-bold py-2 rounded-xl text-slate-400 bg-slate-200 dark:bg-slate-800 cursor-not-allowed">
                    Habis
                </button>
            @endif
        </div>
    </div>
</div>
