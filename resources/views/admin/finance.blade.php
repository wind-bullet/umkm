@extends('layouts.admin')

@section('title', 'Laporan Keuangan - Admin UMKMART')
@section('page_title', 'Laporan Arus Keuangan & Transaksi')

@section('content')
<!-- Financial Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 text-left">
    <!-- Card 1: Total Revenue -->
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pendapatan Terkonfirmasi</span>
            <span class="text-2xl font-black text-slate-800 mt-2 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-icons">monetization_on</span>
        </div>
    </div>
    
    <!-- Card 2: Avg Order Value -->
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Rata-rata Nilai Pesanan</span>
            <span class="text-2xl font-black text-slate-800 mt-2 block">Rp {{ number_format($avgOrder, 0, ',', '.') }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <span class="material-icons">receipt</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
    
    <!-- Transactions Table (Left/Main) -->
    <div class="lg:col-span-2 bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
        <h3 class="font-bold text-slate-800 text-sm mb-6 flex items-center gap-2">
            <span class="material-icons text-emerald-600">account_balance_wallet</span> Transaksi Sukses Terbaru
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase border-b border-slate-150">
                        <th class="pb-3 pl-2">Kode Order</th>
                        <th class="pb-3">Metode Bayar</th>
                        <th class="pb-3">Tanggal Bayar</th>
                        <th class="pb-3 text-right pr-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 pl-2 font-bold text-slate-800">{{ $t->order_code }}</td>
                            <td class="py-4 text-slate-600 font-semibold">{{ $t->payment_method }}</td>
                            <td class="py-4 text-slate-400">{{ $t->updated_at->format('d M Y H:i') }}</td>
                            <td class="py-4 text-right pr-2 font-black text-emerald-600">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">Belum ada transaksi sukses.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
    
    <!-- Payment Method breakdown (Right) -->
    <div class="lg:col-span-1 bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
        <h3 class="font-bold text-slate-800 text-sm mb-6 flex items-center gap-2">
            <span class="material-icons text-emerald-650">credit_card</span> Pendapatan per Metode Pembayaran
        </h3>
        <div class="flex flex-col gap-4">
            @forelse($paymentBreakdown as $pb)
                <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100 last:border-none">
                    <span class="font-bold text-slate-650">{{ $pb->payment_method }}</span>
                    <span class="font-black text-slate-850">Rp {{ number_format($pb->revenue, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">Belum ada data pendapatan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
