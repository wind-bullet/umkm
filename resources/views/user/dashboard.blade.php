@extends('layouts.app')

@section('title', 'Dashboard - AstridMart')

@section('content')
<!-- Welcome Banner -->
<div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 sm:p-8 rounded-3xl shadow-sm mb-8 text-left">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white">Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-xs text-slate-400 mt-1">Selamat datang kembali di panel belanja AstridMart Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('user.profile') }}" class="flex items-center gap-1.5 px-4 py-2 bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 dark:hover:bg-slate-800 text-xs font-bold rounded-xl text-slate-600 dark:text-slate-350 transition-colors">
                <span class="material-icons text-base">settings</span> Edit Profil
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-xs font-bold rounded-xl text-white transition-colors shadow-lg shadow-emerald-600/15">
                    <span class="material-icons text-base">admin_panel_settings</span> Admin Panel
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Category Pills Section (4 Kartu Kategori Lonjong) -->
<div class="mb-10 text-left">
    <h2 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-4">Pilih Kategori Belanja</h2>
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
                
                <span class="material-icons text-xl flex-shrink-0">{{ $cat->icon }}</span>
                <div class="text-left">
                    <p class="text-xs font-bold leading-none">{{ $cat->name }}</p>
                    <p class="text-[9px] text-slate-400 dark:text-slate-500 leading-none mt-1">Belanja</p>
                </div>
            </a>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 text-left">
    <!-- Active Orders (Left/Main) -->
    <div class="lg:col-span-2">
        <h2 class="text-sm font-bold text-slate-850 dark:text-white uppercase tracking-wider mb-4">Pesanan Aktif</h2>
        <div class="flex flex-col gap-4">
            @forelse($activeOrders as $order)
                @php
                    $colors = [
                        'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900',
                        'dibayar' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900',
                        'diproses' => 'bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-950/20 dark:text-purple-400 dark:border-purple-900',
                        'dikirim' => 'bg-indigo-100 text-indigo-800 border-indigo-200 dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-900',
                    ];
                    $labels = [
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'dibayar' => 'Sudah Dibayar',
                        'diproses' => 'Sedang Diproses',
                        'dikirim' => 'Sedang Dikirim',
                    ];
                @endphp
                <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-5 rounded-3xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <h4 class="font-extrabold text-xs text-slate-800 dark:text-white">{{ $order->order_code }}</h4>
                            <span class="px-2 py-0.5 rounded-full border text-[9px] font-bold {{ $colors[$order->order_status] ?? 'bg-slate-100' }}">
                                {{ $labels[$order->order_status] ?? $order->order_status }}
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
                        <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('order.status', $order->order_code) }}" class="flex items-center gap-1 text-[11px] font-bold py-2 px-4 rounded-xl text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 hover:bg-emerald-100 dark:hover:bg-emerald-950/45 transition-colors">
                        <span>Lacak Pesanan</span>
                        <span class="material-icons text-sm">chevron_right</span>
                    </a>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-8 rounded-3xl text-center shadow-sm">
                    <p class="text-xs text-slate-400">Tidak ada pesanan aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Quick Actions Sidebar (Right) -->
    <div class="lg:col-span-1">
        <h2 class="text-sm font-bold text-slate-850 dark:text-white uppercase tracking-wider mb-4">Aksi Cepat</h2>
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-3xl shadow-sm flex flex-col gap-4">
            <!-- Chat Card -->
            <a href="{{ route('user.chat') }}" class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-950/10 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-emerald-600 dark:text-emerald-400 text-2xl">chat</span>
                    <div>
                        <h4 class="font-bold text-xs text-slate-800 dark:text-white">Hubungi Admin</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Tanyakan info produk</p>
                    </div>
                </div>
                @if($unreadMessagesCount > 0)
                    <span class="w-5 h-5 rounded-full bg-rose-500 text-[10px] text-white flex items-center justify-center font-bold">{{ $unreadMessagesCount }}</span>
                @else
                    <span class="material-icons text-slate-300 text-sm">chevron_right</span>
                @endif
            </a>
            
            <!-- Orders List Link -->
            @if(!Auth::user()->isAdmin())
            <a href="{{ route('user.orders') }}" class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-950/10 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-emerald-600 dark:text-emerald-400 text-2xl">history</span>
                    <div>
                        <h4 class="font-bold text-xs text-slate-800 dark:text-white">Riwayat Belanja</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Lacak pesanan lalu</p>
                    </div>
                </div>
                <span class="material-icons text-slate-300 text-sm">chevron_right</span>
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Recommended Products Grid -->
<div class="text-left">
    <h2 class="text-sm font-bold text-slate-850 dark:text-white uppercase tracking-wider mb-6">Rekomendasi Spesial</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($recommendedProducts as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endsection
