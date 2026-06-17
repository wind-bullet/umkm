@extends('layouts.admin')

@section('title', 'Kelola Produk - Admin AstridMart')
@section('page_title', 'Kelola Inventori Produk')

@section('content')
<!-- Header Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 text-left">
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Produk</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">{{ $totalCount }} Item</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <span class="material-icons">inventory_2</span>
        </div>
    </div>
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Stok Menipis (&lt; 5)</span>
            <span class="text-xl font-black text-rose-600 mt-2 block">{{ $lowStockCount }} Item</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
            <span class="material-icons">warning</span>
        </div>
    </div>
    <div class="bg-white border border-slate-150 p-6 rounded-2xl flex items-center justify-between shadow-sm">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Kategori Aktif</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">{{ $categoryCount }} Kategori</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
            <span class="material-icons">grid_view</span>
        </div>
    </div>
</div>

<!-- Products Table Card -->
<div class="bg-white border border-slate-150 rounded-2xl p-6 shadow-sm text-left">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
            <span class="material-icons text-emerald-600">list</span> Daftar Produk
        </h3>
        <a href="{{ route('admin.products.create') }}" class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/15">
            <span class="material-icons text-sm">add</span> Tambah Produk Baru
        </a>
    </div>
    
    @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase border-b border-slate-150">
                        <th class="pb-3 pl-2">Produk</th>
                        <th class="pb-3">Kategori</th>
                        <th class="pb-3">Harga</th>
                        <th class="pb-3">Stok</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right pr-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $product)
                        <tr class="hover:bg-slate-50/50">
                            <!-- Image & Name -->
                            <td class="py-4 pl-2 font-bold text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                        <!-- Dynamic uploaded image or sample fallback -->
                                        @if($product->image && file_exists(public_path('uploads/products/' . $product->image)))
                                            <img src="/uploads/products/{{ $product->image }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=AstridMart'">
                                        @endif
                                    </div>
                                    <span class="truncate max-w-48">{{ $product->name }}</span>
                                </div>
                            </td>
                            <!-- Category -->
                            <td class="py-4 text-slate-650 font-semibold">
                                {{ $product->category->name }}
                            </td>
                            <!-- Price -->
                            <td class="py-4 font-black text-slate-850">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <!-- Stock -->
                            <td class="py-4 font-bold">
                                <span class="{{ $product->stock < 5 ? 'text-rose-600' : 'text-slate-700' }}">{{ $product->stock }} unit</span>
                            </td>
                            <!-- Status -->
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded-full border text-[9px] font-bold 
                                    {{ $product->is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="py-4 text-right pr-2">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="Hapus">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="py-8 text-center text-slate-400">
            <span class="material-icons text-4xl">inventory_2</span>
            <p class="text-xs font-bold mt-2">Belum ada data produk.</p>
        </div>
    @endif
</div>
@endsection
