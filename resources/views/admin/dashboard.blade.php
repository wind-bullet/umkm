@extends('layouts.admin')

@section('title', 'Admin Dashboard - UMKMART')
@section('page_title', 'Ringkasan Bisnis')

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 text-left">
    <!-- Card 1: Revenue -->
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pendapatan Bersih</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">Rp {{ number_format($netRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-icons">payments</span>
        </div>
    </div>
    
    <!-- Card 2: Orders -->
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pesanan</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">{{ $totalOrders }} Order</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <span class="material-icons">shopping_bag</span>
        </div>
    </div>
    
    <!-- Card 3: Products -->
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Kelola Produk</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">{{ $totalProducts }} Item</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
            <span class="material-icons">inventory_2</span>
        </div>
    </div>
    
    <!-- Card 4: Customers -->
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Customer</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">{{ $totalUsers - 1 }} Akun</span> <!-- Minus admin -->
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <span class="material-icons">people</span>
        </div>
    </div>
</div>

<!-- Graph & Activities Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 text-left">
    
    <!-- Sales Chart (Left/Main) -->
    <div class="lg:col-span-2 bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
        <h3 class="font-bold text-slate-800 text-sm mb-6 flex items-center gap-2">
            <span class="material-icons text-emerald-600">trending_up</span> Tren Pendapatan Bulanan (2026)
        </h3>
        <div class="h-80 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    
    <!-- Top Products (Right) -->
    <div class="lg:col-span-1 bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
        <h3 class="font-bold text-slate-800 text-sm mb-6 flex items-center gap-2">
            <span class="material-icons text-amber-500">local_fire_department</span> Terlaris (Qty Terjual)
        </h3>
        <div class="flex flex-col gap-4">
            @forelse($topProducts as $item)
                @if($item->product)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                            @if($item->product->image && file_exists(public_path('uploads/products/' . $item->product->image)))
                                <img src="/uploads/products/{{ $item->product->image }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/50x50?text=UMKMART'">
                            @else
                                <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/50x50?text=UMKMART'">
                            @endif
                        </div>
                        <div class="min-w-0 flex-grow">
                            <h4 class="font-bold text-xs text-slate-800 truncate">{{ $item->product->name }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5">Terjual: {{ $item->total_qty }} unit</p>
                        </div>
                        <span class="text-xs font-black text-emerald-600">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                    </div>
                @endif
            @empty
                <p class="text-xs text-slate-400 text-center py-4">Belum ada barang terjual.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="bg-white border border-slate-150 p-6 rounded-2xl shadow-sm text-left">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
            <span class="material-icons text-emerald-600">history</span> Pesanan Terbaru
        </h3>
        <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 font-bold uppercase border-b border-slate-150">
                    <th class="pb-3 pl-2">Kode Order</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Tanggal</th>
                    <th class="pb-3">Total</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right pr-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    @php
                        $colors = [
                            'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'dibayar' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'diproses' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'dikirim' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        ];
                        $labels = [
                            'menunggu_pembayaran' => 'Menunggu Pembayaran',
                            'dibayar' => 'Sudah Dibayar',
                            'diproses' => 'Sedang Diproses',
                            'dikirim' => 'Sedang Dikirim',
                            'selesai' => 'Selesai',
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 pl-2 font-bold text-slate-800">{{ $order->order_code }}</td>
                        <td class="py-4 text-slate-700 font-semibold">{{ $order->user->name }}</td>
                        <td class="py-4 text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="py-4 font-bold text-slate-850">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="py-4">
                            <span class="px-2 py-0.5 rounded-full border text-[9px] font-bold {{ $colors[$order->order_status] ?? 'bg-slate-100' }}">
                                {{ $labels[$order->order_status] ?? $order->order_status }}
                            </span>
                        </td>
                        <td class="py-4 text-right pr-2">
                            <!-- Quick change status form -->
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                @csrf
                                <select name="order_status" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-lg py-1 px-2 text-[10px] focus:outline-none text-slate-650 font-bold">
                                    <option value="menunggu_pembayaran" {{ $order->order_status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="dibayar" {{ $order->order_status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                    <option value="diproses" {{ $order->order_status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $order->order_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="selesai" {{ $order->order_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-400">Belum ada pesanan terbaru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartMonths) !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($chartRevenueValues) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
