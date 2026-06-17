@extends('layouts.admin')

@section('title', 'Kelola Voucher - Admin UMKMART')
@section('page_title', 'Kelola Pengaturan Voucher')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
    
    <!-- Add Voucher Setting (Left/Sidebar) -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-150">Tambah Aturan Voucher</h3>
            
            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                
                <!-- Product Selector (Vouchers only) -->
                <div>
                    <label for="product_id" class="block text-xs font-bold text-slate-500 uppercase mb-2">Pilih Item Voucher</label>
                    <select name="product_id" id="product_id" required class="w-full bg-slate-100 border-none rounded-xl py-2.5 px-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        <option value="">-- Pilih Produk Voucher --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Voucher Type -->
                <div>
                    <label for="voucher_type" class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipe Diskon</label>
                    <select name="voucher_type" id="voucher_type" required class="w-full bg-slate-100 border-none rounded-xl py-2.5 px-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        <option value="discount_fixed">Potongan Flat (Rupiah)</option>
                        <option value="discount_percentage">Potongan Persentase (%)</option>
                    </select>
                </div>
                
                <!-- Voucher Label -->
                <div>
                    <label for="voucher_label" class="block text-xs font-bold text-slate-500 uppercase mb-2">Label Voucher</label>
                    <input type="text" name="voucher_label" id="voucher_label" placeholder="Contoh: Voucher Rp 20.000 OFF" required class="w-full bg-slate-100 border-none rounded-xl py-2.5 px-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                </div>
                
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/10 mt-2">
                    Simpan Aturan Voucher
                </button>
            </form>
        </div>
    </div>
    
    <!-- Voucher List Table (Right/Main) -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-slate-150 p-6 rounded-2xl shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-6 pb-2 border-b border-slate-150">Daftar Aktif Aturan Voucher</h3>
            
            @if($vouchers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 font-bold uppercase border-b border-slate-150">
                                <th class="pb-3 pl-2">Nama Produk Voucher</th>
                                <th class="pb-3">Tipe Potongan</th>
                                <th class="pb-3">Label Diskon</th>
                                <th class="pb-3 text-right pr-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($vouchers as $v)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-4 pl-2 font-bold text-slate-800">
                                        {{ $v->product ? $v->product->name : 'Produk Dihapus' }}
                                    </td>
                                    <td class="py-4 text-slate-600 font-semibold">
                                        {{ $v->voucher_type === 'discount_fixed' ? 'Nominal Tetap (Rp)' : 'Persentase (%)' }}
                                    </td>
                                    <td class="py-4 font-extrabold text-amber-600">
                                        {{ $v->voucher_label }}
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <form action="{{ route('admin.vouchers.delete', $v->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan voucher ini?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus">
                                                <span class="material-icons text-sm">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $vouchers->links() }}
                </div>
            @else
                <div class="py-8 text-center text-slate-400">
                    <span class="material-icons text-4xl">confirmation_number</span>
                    <p class="text-xs font-bold mt-2">Belum ada aturan voucher yang disetup.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
