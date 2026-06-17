@extends('layouts.admin')

@section('title', 'Kelola Customer - Admin UMKMART')
@section('page_title', 'Daftar Customer Terdaftar')

@section('content')
<div class="bg-white border border-slate-150 rounded-2xl p-6 shadow-sm text-left">
    @if($customers->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase border-b border-slate-150">
                        <th class="pb-3 pl-2">Nama Customer</th>
                        <th class="pb-3">Alamat Email</th>
                        <th class="pb-3">Nomor Telepon</th>
                        <th class="pb-3">Total Pesanan</th>
                        <th class="pb-3">Tanggal Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($customers as $c)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 pl-2 font-bold text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-emerald-600 font-bold flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $c->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-slate-600 font-semibold">{{ $c->email }}</td>
                            <td class="py-4 text-slate-500 font-semibold">{{ $c->phone_number }}</td>
                            <td class="py-4 font-bold text-slate-800">{{ $c->orders_count }} Transaksi</td>
                            <td class="py-4 text-slate-400">{{ $c->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    @else
        <div class="py-8 text-center text-slate-400">
            <span class="material-icons text-4xl">people_outline</span>
            <p class="text-xs font-bold mt-2">Tidak ada customer terdaftar.</p>
        </div>
    @endif
</div>
@endsection
