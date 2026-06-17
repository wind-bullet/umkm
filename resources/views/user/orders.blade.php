@extends('layouts.app')

@section('title', 'Riwayat Belanja - AstridMart')

@section('content')
<h1 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-6 flex items-center gap-2 text-left">
    <span class="material-icons text-emerald-600 dark:text-emerald-400">history</span>
    Riwayat Belanja Anda
</h1>

<div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 shadow-sm text-left">
    @if($orders->count() > 0)
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase border-b border-slate-100 dark:border-slate-850">
                        <th class="pb-3 pl-2">Kode Order</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Pengiriman</th>
                        <th class="pb-3">Metode Bayar</th>
                        <th class="pb-3">Total Belanja</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right pr-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-850/50">
                    @foreach($orders as $order)
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
                                'selesai' => 'Selesai',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10">
                            <td class="py-4 pl-2 font-bold text-slate-800 dark:text-slate-200">{{ $order->order_code }}</td>
                            <td class="py-4 text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-350">{{ $order->delivery_method }}</td>
                            <td class="py-4 text-slate-500">{{ $order->payment_method }}</td>
                            <td class="py-4 font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-0.5 rounded-full border text-[9px] font-bold {{ $colors[$order->order_status] ?? 'bg-slate-100' }}">
                                    {{ $labels[$order->order_status] ?? $order->order_status }}
                                </span>
                            </td>
                            <td class="py-4 text-right pr-2">
                                <a href="{{ route('order.status', $order->order_code) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-350 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards List (Hidden on Desktop) -->
        <div class="md:hidden flex flex-col gap-4">
            @foreach($orders as $order)
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
                        'selesai' => 'Selesai',
                    ];
                @endphp
                <div class="p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-900/20 flex flex-col gap-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-xs text-slate-800 dark:text-white">{{ $order->order_code }}</h4>
                            <p class="text-[9px] text-slate-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full border text-[8px] font-bold {{ $colors[$order->order_status] ?? 'bg-slate-100' }}">
                            {{ $labels[$order->order_status] ?? $order->order_status }}
                        </span>
                    </div>
                    <hr class="border-slate-100 dark:border-slate-850/50 my-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-550 dark:text-slate-400">Total Belanja</span>
                        <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-550 dark:text-slate-400">Pengiriman</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-350 text-[10px]">{{ $order->delivery_method }}</span>
                    </div>
                    <a href="{{ route('order.status', $order->order_code) }}" class="block w-full text-center mt-2 bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 dark:hover:bg-slate-800 text-[10px] font-bold py-2 rounded-xl text-slate-600 dark:text-slate-350 transition-colors">
                        Lihat Rincian Detail
                    </a>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="py-12 text-center">
            <span class="material-icons text-slate-300 dark:text-slate-750 text-5xl">receipt_long</span>
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mt-2">Belum Ada Transaksi</h3>
            <p class="text-[10px] text-slate-400 max-w-xs mx-auto mt-1">Anda belum pernah melakukan transaksi pembelian di AstridMart.</p>
        </div>
    @endif
</div>
@endsection
