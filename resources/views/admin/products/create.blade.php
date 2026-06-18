@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Admin UMKMART')
@section('page_title', 'Tambah Produk Baru')

@section('content')
<div class="bg-white border border-slate-150 rounded-2xl p-6 sm:p-8 max-w-2xl mx-auto shadow-sm text-left">
    <h3 class="font-bold text-slate-800 text-sm mb-6 pb-2 border-b border-slate-150">Formulir Tambah Produk Baru</h3>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
        @csrf
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nama Produk -->
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Kaos Oversize UMKMART" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                @error('name')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori</label>
                <select name="category_id" id="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Harga -->
            <div>
                <label for="price" class="block text-xs font-bold text-slate-500 uppercase mb-2">Harga (Rupiah)</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" required placeholder="85000" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                @error('price')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Stok -->
            <div>
                <label for="stock" class="block text-xs font-bold text-slate-500 uppercase mb-2">Stok Awal</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" required placeholder="50" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                @error('stock')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Upload Gambar -->
            <div>
                <label for="image" class="block text-xs font-bold text-slate-500 uppercase mb-2">Gambar Produk</label>
                <input type="file" name="image" id="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                @error('image')
                    <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Voucher settings (hidden by default) -->
            <div class="sm:col-span-2 hidden" id="voucher-fields">
                <div class="bg-amber-50/20 border border-dashed border-amber-200 p-4 rounded-2xl flex flex-col gap-4">
                    <h4 class="text-xs font-bold text-amber-700 uppercase flex items-center gap-1">
                        <span class="material-icons text-sm">confirmation_number</span> Pengaturan Voucher
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="voucher_type" class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipe Potongan</label>
                            <select name="voucher_type" id="voucher_type" class="w-full bg-white border border-slate-200 rounded-xl py-2 px-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                                <option value="discount_fixed" {{ old('voucher_type') == 'discount_fixed' ? 'selected' : '' }}>Nominal Tetap (Rupiah)</option>
                                <option value="discount_percentage" {{ old('voucher_type') == 'discount_percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            </select>
                        </div>
                        <div>
                            <label for="voucher_label" class="block text-xs font-bold text-slate-500 uppercase mb-2">Label Voucher</label>
                            <input type="text" name="voucher_label" id="voucher_label" value="{{ old('voucher_label') }}" placeholder="Contoh: 10% OFF atau Rp 10.000 OFF" class="w-full bg-white border border-slate-200 rounded-xl py-2 px-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div class="sm:col-span-2">
                <label for="description" class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Produk</label>
                <textarea name="description" id="description" rows="4" required placeholder="Tuliskan spesifikasi lengkap produk Anda..." class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800"></textarea>
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
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const categorySelect = document.getElementById('category_id');
        const voucherFields = document.getElementById('voucher-fields');
        
        function toggleVoucherFields() {
            if (!categorySelect || !voucherFields) return;
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const slug = selectedOption ? selectedOption.getAttribute('data-slug') : '';
            
            if (slug === 'voucher') {
                voucherFields.classList.remove('hidden');
                document.getElementById('voucher_type').setAttribute('required', 'required');
                document.getElementById('voucher_label').setAttribute('required', 'required');
            } else {
                voucherFields.classList.add('hidden');
                document.getElementById('voucher_type').removeAttribute('required');
                document.getElementById('voucher_label').removeAttribute('required');
            }
        }
        
        categorySelect.addEventListener('change', toggleVoucherFields);
        toggleVoucherFields();
    });
</script>
@endsection
