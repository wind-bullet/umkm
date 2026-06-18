@extends('layouts.app')

@section('title', 'Keranjang Belanja - UMKMART')

@section('content')
<h1 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-6 flex items-center gap-2 text-left">
    <span class="material-icons text-emerald-600 dark:text-emerald-400">shopping_cart</span>
    Keranjang Belanja Anda
</h1>

<div class="flex flex-col lg:flex-row gap-8">
    
    <!-- Cart Items List (Left/Main) -->
    <div class="flex-grow">
        @if($cartItems->count() > 0)
            <div class="flex flex-col gap-4">
                @foreach($cartItems as $item)
                    <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-4 rounded-3xl flex flex-col sm:flex-row items-center gap-4 shadow-sm relative">
                        <!-- Product Image -->
                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 flex-shrink-0">
                            @if($item->product->image && file_exists(public_path('uploads/products/' . $item->product->image)))
                                <img src="/uploads/products/{{ $item->product->image }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/150x150?text=UMKMART'">
                            @else
                                <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/150x150?text=UMKMART'">
                            @endif
                        </div>
                        
                        <!-- Name & Category -->
                        <div class="text-center sm:text-left flex-grow min-w-0">
                            <h3 class="font-bold text-sm text-slate-800 dark:text-white truncate">
                                <a href="{{ route('product.detail', $item->product_id) }}" class="hover:text-emerald-600">{{ $item->product->name }}</a>
                            </h3>
                            <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">{{ $item->product->category->name }}</p>
                            <p class="text-xs font-bold text-slate-900 dark:text-emerald-400 mt-1">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                        </div>
                        
                        <!-- Qty Adjuster & Price -->
                        <div class="flex items-center gap-4 flex-shrink-0">
                            <!-- Qty buttons -->
                            <div class="flex items-center border border-slate-200 dark:border-slate-800 rounded-xl px-1.5 py-0.5 bg-slate-50 dark:bg-slate-900">
                                <button type="button" onclick="updateCartQty({{ $item->id }}, -1)" class="w-7 h-7 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-850 flex items-center justify-center text-slate-500"><span class="material-icons text-sm">remove</span></button>
                                <span id="qty-val-{{ $item->id }}" class="w-8 text-center font-bold text-xs text-slate-800 dark:text-white">{{ $item->qty }}</span>
                                <button type="button" onclick="updateCartQty({{ $item->id }}, 1)" class="w-7 h-7 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-850 flex items-center justify-center text-slate-500"><span class="material-icons text-sm">add</span></button>
                            </div>
                            
                            <!-- Subtotal item price -->
                            <div class="text-right w-24">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total</p>
                                <p class="text-xs font-black text-emerald-600 dark:text-emerald-400 mt-0.5">Rp <span id="item-total-{{ $item->id }}">{{ number_format($item->qty * $item->product->price, 0, ',', '.') }}</span></p>
                            </div>
                        </div>
                        
                        <!-- Delete Button -->
                        <form action="{{ route('cart.remove') }}" method="POST" class="absolute top-4 right-4 sm:relative sm:top-0 sm:right-0">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors" title="Hapus Item">
                                <span class="material-icons text-lg">delete</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty state -->
            <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-16 text-center shadow-sm">
                <span class="material-icons text-slate-300 dark:text-slate-700 text-6xl">remove_shopping_cart</span>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-4">Keranjang Belanja Kosong</h3>
                <p class="text-slate-400 dark:text-slate-500 text-xs mt-1 max-w-sm mx-auto">
                    Anda belum memasukkan produk apapun ke dalam keranjang belanja. Jelajahi katalog kami untuk mulai berbelanja.
                </p>
                <a href="{{ route('home') }}" class="inline-block mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-full text-xs transition-colors shadow-lg shadow-emerald-600/15">
                    Mulai Belanja Sekarang
                </a>
            </div>
        @endif
    </div>
    
    <!-- Order Summary Sidebar (Right) -->
    @if($cartItems->count() > 0)
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 shadow-sm sticky top-24 text-left">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 pb-3 border-b border-slate-100 dark:border-slate-850">Ringkasan Pesanan</h3>
                
                <div class="flex flex-col gap-3 mb-6">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Subtotal Barang</span>
                        <span class="font-bold text-slate-700 dark:text-slate-350">Rp <span id="cart-subtotal">{{ number_format($subtotal, 0, ',', '.') }}</span></span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Biaya Pengiriman</span>
                        <span class="font-bold text-slate-400">Dihitung di checkout</span>
                    </div>
                    <hr class="border-slate-100 dark:border-slate-850 my-1">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-800 dark:text-white">Total Pembayaran</span>
                        <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp <span id="cart-total">{{ number_format($subtotal, 0, ',', '.') }}</span></span>
                    </div>
                </div>
                
                <a href="{{ route('checkout') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl text-center text-xs transition-colors shadow-lg shadow-emerald-600/15">
                    Proses Ke Checkout
                </a>
                
                <a href="{{ route('home') }}" class="block w-full text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline mt-4">
                    Kembali Belanja
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function updateCartQty(itemId, val) {
        const qtySpan = document.getElementById(`qty-val-${itemId}`);
        if (!qtySpan) return;
        
        let current = parseInt(qtySpan.textContent);
        let next = current + val;
        
        if (next < 1) return; // cannot be less than 1
        
        // Show loading state if wanted
        
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id: itemId,
                qty: next
            })
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => { throw new Error(err.message); });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                qtySpan.textContent = next;
                document.getElementById(`item-total-${itemId}`).textContent = data.item_total;
                document.getElementById('cart-subtotal').textContent = data.subtotal;
                document.getElementById('cart-total').textContent = data.total;
            }
        })
        .catch(err => {
            alert(err.message || 'Gagal mengubah kuantitas barang.');
        });
    }
</script>
@endsection
