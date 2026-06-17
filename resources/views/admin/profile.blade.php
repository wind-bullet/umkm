@extends('layouts.admin')

@section('title', 'Profil Admin - UMKMART')
@section('page_title', 'Profil & Pengaturan Admin')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
    
    <!-- Profile Edit Form (Left/Main) -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-slate-150 p-6 sm:p-8 rounded-2xl shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-6 pb-2 border-b border-slate-150">Edit Informasi Akun Administrator</h3>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        @error('name')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Telepon -->
                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Telepon</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $admin->phone_number) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        @error('phone_number')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        @error('email')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <h3 class="font-bold text-slate-800 text-sm mt-4 pb-2 border-b border-slate-150">Ubah Kata Sandi (Kosongkan jika tidak diubah)</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" placeholder="Min. 6 Karakter" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                        @error('password')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                    </div>
                </div>
                
                <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/15 mt-2 ml-auto">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
    
    <!-- Admin Info Stats (Right) -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-150 p-6 rounded-2xl shadow-sm flex flex-col gap-4">
            <h3 class="font-bold text-slate-800 text-sm pb-2 border-b border-slate-150">Rangkuman Aktivitas Toko</h3>
            <div class="flex flex-col gap-3 text-xs">
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-slate-500">Total Jenis Barang</span>
                    <span class="font-bold text-slate-800">{{ $totalProducts }} Item</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-500">Pendapatan Bersih</span>
                    <span class="font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
