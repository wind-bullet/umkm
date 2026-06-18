@extends('layouts.admin')

@section('title', 'Analisis Penjualan - Admin UMKMART')
@section('page_title', 'Statistik & Pelacakan Penjualan')

@section('content')
<!-- Status Breakdown Grid -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8 text-left">
    @php
        $colors = [
            'menunggu_pembayaran' => 'border-amber-200 bg-amber-50/20 text-amber-800',
            'dibayar' => 'border-blue-200 bg-blue-50/20 text-blue-800',
            'diproses' => 'border-purple-200 bg-purple-50/20 text-purple-800',
            'dikirim' => 'border-indigo-200 bg-indigo-50/20 text-indigo-800',
            'selesai' => 'border-emerald-200 bg-emerald-50/20 text-emerald-800',
        ];
        $labels = [
            'menunggu_pembayaran' => 'Menunggu Bayar',
            'dibayar' => 'Sudah Dibayar',
            'diproses' => 'Diproses',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
        ];
    @endphp
    @foreach($orderStats as $status => $count)
        <div class="p-4 rounded-2xl border flex flex-col gap-1 {{ $colors[$status] ?? 'border-slate-200 bg-slate-50 text-slate-800' }}">
            <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">{{ $labels[$status] ?? $status }}</span>
            <span class="text-2xl font-black block mt-1">{{ $count }} Order</span>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
    <!-- Top Selling Products Catalog List -->
    <div class="lg:col-span-2 bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
        <h3 class="font-bold text-slate-800 text-sm mb-6 flex items-center gap-2">
            <span class="material-icons text-amber-500">local_fire_department</span> Top Produk Terlaris
        </h3>
        
        @if($topProducts->count() > 0)
            <div class="flex flex-col gap-4">
                @foreach($topProducts as $item)
                    @if($item->product)
                        <div class="flex items-center gap-4 py-2 border-b border-slate-50 last:border-0">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                                @if($item->product->image && file_exists(public_path('uploads/products/' . $item->product->image)))
                                    <img src="/uploads/products/{{ $item->product->image }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=UMKMART'">
                                @else
                                    <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=UMKMART'">
                                @endif
                            </div>
                            <div class="min-w-0 flex-grow">
                                <h4 class="font-bold text-xs text-slate-800 truncate">{{ $item->product->name }}</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Kategori: {{ $item->product->category->name }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs font-black text-slate-850">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-emerald-600 font-bold mt-0.5">Terjual: {{ $item->total_qty }} unit</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 text-center py-6">Belum ada data penjualan produk.</p>
        @endif
    </div>
    
    <!-- Promotion Campaign card -->
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-150">Ringkasan Promo Aktif</h3>
            <div class="flex flex-col gap-3 text-xs">
                <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl">
                    <p class="font-bold text-amber-800">Voucher Kopi Kenangan</p>
                    <p class="text-[10px] text-amber-700 mt-1">Diskon potongan nominal Rp 20.000 flat untuk pelanggan.</p>
                </div>
                <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl">
                    <p class="font-bold text-blue-800">Voucher Bioskop XXI</p>
                    <p class="text-[10px] text-blue-700 mt-1">Diskon potongan nominal Rp 50.000 flat untuk nonton film.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
