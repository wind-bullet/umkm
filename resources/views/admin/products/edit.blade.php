@extends('layouts.admin')

@section('title', 'Edit Produk - Admin AstridMart')
@section('page_title', 'Ubah Informasi Produk')

@section('content')
<div class="bg-white border border-slate-150 rounded-2xl p-6 sm:p-8 max-w-2xl mx-auto shadow-sm text-left">
    <h3 class="font-bold text-slate-800 text-sm mb-6 pb-2 border-b border-slate-150">Formulir Ubah Informasi Produk</h3>
    
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
        @csrf
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nama Produk -->
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                @error('name')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori</label>
                <select name="category_id" id="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Harga -->
            <div>
                <label for="price" class="block text-xs font-bold text-slate-500 uppercase mb-2">Harga (Rupiah)</label>
                <input type="number" name="price" id="price" value="{{ old('price', (int)$product->price) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                @error('price')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Stok -->
            <div>
                <label for="stock" class="block text-xs font-bold text-slate-500 uppercase mb-2">Stok</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                @error('stock')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status Aktif -->
            <div>
                <label for="is_active" class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Penjualan</label>
                <select name="is_active" id="is_active" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                    <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
                    <option value="0" {{ !old('is_active', $product->is_active) ? 'selected' : '' }}>Non-aktif (Disembunyikan)</option>
                </select>
            </div>
            
            <!-- Gambar Produk Saat Ini -->
            <div class="sm:col-span-2 flex items-center gap-4 py-2 border-y border-slate-100">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                    @if($product->image && file_exists(public_path('uploads/products/' . $product->image)))
                        <img src="/uploads/products/{{ $product->image }}" class="w-full h-full object-cover">
                    @else
                        <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=AstridMart'">
                    @endif
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-650">Gambar Saat Ini</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Unggah berkas baru di bawah jika ingin mengganti gambar produk.</p>
                </div>
            </div>
            
            <!-- Upload Gambar Baru -->
            <div class="sm:col-span-2">
                <label for="image" class="block text-xs font-bold text-slate-500 uppercase mb-2">Unggah Gambar Baru (Opsional)</label>
                <input type="file" name="image" id="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                @error('image')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Deskripsi -->
            <div class="sm:col-span-2">
                <label for="description" class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Produk</label>
                <textarea name="description" id="description" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-4 border-t border-slate-100 pt-5">
            <a href="{{ route('admin.products') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-6 rounded-xl text-xs transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/15">
                Perbarui Produk
            </button>
        </div>
    </form>
</div>
@endsection
