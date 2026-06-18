@extends('layouts.app')

@section('title', 'Status Pesanan ' . $order->order_code . ' - UMKMART')

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
                <span class="text-slate-500">Status Pesanan</span>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center gap-1">
                <span class="material-icons text-sm">chevron_right</span>
                <span class="text-slate-705 dark:text-slate-350">{{ $order->order_code }}</span>
            </div>
        </li>
    </ol>
</nav>

<div class="flex flex-col lg:flex-row gap-8 text-left">
    <!-- Main Info (Left) -->
    <div class="flex-grow flex flex-col gap-6">
        
        <!-- Status Timeline Card -->
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-3xl shadow-sm">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100 dark:border-slate-850">
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-sm">Status Alur Pengiriman</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Kode Order: {{ $order->order_code }}</p>
                </div>
                <!-- Status Badge -->
                @php
                    $colors = [
                        'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900',
                        'dibayar' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900',
                        'diproses' => 'bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-950/20 dark:text-purple-400 dark:border-purple-900',
                        'dikirim' => 'bg-indigo-100 text-indigo-800 border-indigo-200 dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-900',
                        'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900',
                    ];
                    $labels = [
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'dibayar' => 'Sudah Dibayar',
                        'diproses' => 'Sedang Diproses',
                        'dikirim' => 'Sedang Dikirim',
                        'selesai' => 'Pesanan Selesai',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full border text-xs font-bold {{ $colors[$order->order_status] ?? 'bg-slate-100' }}">
                    {{ $labels[$order->order_status] ?? $order->order_status }}
                </span>
            </div>
            
            <!-- Horizontal Timeline (Hidden on Mobile) -->
            <div class="hidden md:flex justify-between items-center px-4 py-6 relative">
                <!-- Line background -->
                <div class="absolute left-16 right-16 top-1/2 -translate-y-1/2 h-1 bg-slate-100 dark:bg-slate-800 z-0"></div>
                
                @php
                    $steps = ['menunggu_pembayaran', 'dibayar', 'diproses', 'dikirim', 'selesai'];
                    $currentIdx = array_search($order->order_status, $steps);
                @endphp
                
                @foreach($steps as $idx => $step)
                    @php
                        $isPast = $idx <= $currentIdx;
                        $isCurrent = $idx === $currentIdx;
                    @endphp
                    <div class="flex flex-col items-center gap-2 z-10 w-24">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-md border-2
                            {{ $isCurrent 
                                ? 'bg-emerald-600 border-emerald-400 text-white animate-pulse' 
                                : ($isPast 
                                    ? 'bg-emerald-100 border-emerald-200 dark:bg-emerald-950 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400' 
                                    : 'bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-800 text-slate-400') }}">
                            @if($isPast && !$isCurrent)
                                <span class="material-icons text-sm">check</span>
                            @else
                                <span>{{ $idx + 1 }}</span>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight 
                            {{ $isCurrent 
                                ? 'text-emerald-600 dark:text-emerald-400 font-extrabold' 
                                : ($isPast ? 'text-slate-800 dark:text-slate-350' : 'text-slate-400') }}">
                            {{ $labels[$step] }}
                        </span>
                    </div>
                @endforeach
            </div>
            
            <!-- Vertical Timeline (Mobile Only) -->
            <div class="md:hidden flex flex-col gap-6 pl-4 border-l-2 border-slate-150 dark:border-slate-800 py-2">
                @foreach($steps as $idx => $step)
                    @php
                        $isPast = $idx <= $currentIdx;
                        $isCurrent = $idx === $currentIdx;
                    @endphp
                    <div class="relative flex items-center gap-3">
                        <div class="absolute -left-6.5 w-5 h-5 rounded-full flex items-center justify-center border-2 shadow-sm
                            {{ $isCurrent 
                                ? 'bg-emerald-600 border-emerald-400 text-white' 
                                : ($isPast 
                                    ? 'bg-emerald-100 border-emerald-250 dark:bg-emerald-950 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400' 
                                    : 'bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-800 text-slate-400') }}">
                            @if($isPast && !$isCurrent)
                                <span class="material-icons text-[10px]">check</span>
                            @else
                                <span class="text-[8px] font-bold">{{ $idx + 1 }}</span>
                            @endif
                        </div>
                        <span class="text-xs font-bold {{ $isCurrent ? 'text-emerald-600 dark:text-emerald-400' : ($isPast ? 'text-slate-800 dark:text-slate-350' : 'text-slate-400') }}">
                            {{ $labels[$step] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Detail Items Card -->
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-3xl shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 pb-2 border-b border-slate-100 dark:border-slate-850">Detail Pembelian Barang</h3>
            <div class="flex flex-col gap-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 flex-shrink-0">
                            @if($item->product && $item->product->image && file_exists(public_path('uploads/products/' . $item->product->image)))
                                <img src="/uploads/products/{{ $item->product->image }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=UMKMART'">
                            @else
                                <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=UMKMART'">
                            @endif
                        </div>
                        <div class="min-w-0 flex-grow">
                            <h4 class="font-bold text-xs text-slate-800 dark:text-white truncate">{{ $item->product->name }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->qty }}x @ Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-350">Rp {{ number_format($item->qty * $item->price_snapshot, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Billing Info (Right) -->
    <div class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-6">
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 pb-3 border-b border-slate-100 dark:border-slate-850">Rincian Pembayaran</h3>
            
            <div class="flex flex-col gap-3 text-xs mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Subtotal Belanja</span>
                    <span class="font-bold text-slate-700 dark:text-slate-350">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Ongkos Kirim</span>
                    <span class="font-bold text-slate-700 dark:text-slate-350">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Metode Kirim</span>
                    <span class="font-bold text-slate-700 dark:text-slate-350">{{ $order->delivery_method }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Metode Bayar</span>
                    <span class="font-bold text-slate-700 dark:text-slate-350">{{ $order->payment_method }}</span>
                </div>
                <hr class="border-slate-100 dark:border-slate-850 my-1">
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-slate-800 dark:text-white">Total Tagihan</span>
                    <span class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                
                @if($order->shipping_address)
                <div class="flex flex-col gap-1 mt-3 pt-3 border-t border-slate-100 dark:border-slate-850">
                    <span class="text-slate-500 font-semibold">Alamat Pengiriman:</span>
                    <p class="text-[11px] text-slate-650 dark:text-slate-350 leading-relaxed bg-slate-50 dark:bg-slate-900/40 p-2.5 rounded-xl border border-slate-100 dark:border-slate-850 whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
                @endif
            </div>
            
            <!-- Instructions depending on status -->
            @if($order->order_status === 'menunggu_pembayaran')
                <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 rounded-2xl p-4 text-xs text-amber-800 dark:text-amber-400 mb-6">
                    <p class="font-bold flex items-center gap-1 mb-1">
                        <span class="material-icons text-sm">info</span> Menunggu Pembayaran
                    </p>
                    <p>Silakan simulasikan pembayaran dengan masuk ke Dashboard Admin dan memperbarui status pesanan ini menjadi 'Dibayar'.</p>
                </div>
            @endif
            
            <a href="{{ route('user.dashboard') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl text-center text-xs transition-colors shadow-lg shadow-emerald-600/15">
                Kembali Ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
