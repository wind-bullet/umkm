@extends('layouts.auth')

@section('title', 'Daftar Akun - UMKMART')

@section('content')
<div class="text-center lg:text-left mb-6">
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Daftar Akun Baru</h2>
    <p class="text-slate-500 mt-2 text-sm">Buat akun untuk berbelanja dan melacak pesanan di UMKMART.</p>
</div>

<form action="{{ route('register') }}" method="POST" class="flex flex-col gap-4">
    @csrf
    
    <!-- Nama Lengkap -->
    <div>
        <label for="name" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">person</span>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Budi Santoso" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
        @error('name')
            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Email -->
    <div>
        <label for="email" class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">mail</span>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="budi@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
        @error('email')
            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nomor Telepon -->
    <div>
        <label for="phone_number" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Telepon</label>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">phone</span>
            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required placeholder="0812xxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
        @error('phone_number')
            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Password -->
    <div>
        <label for="password" class="block text-xs font-bold text-slate-500 uppercase mb-2">Kata Sandi</label>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">lock</span>
            <input type="password" name="password" id="password" required placeholder="Min. 6 Karakter" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
        @error('password')
            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Konfirmasi Password -->
    <div>
        <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Kata Sandi</label>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">lock_reset</span>
            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ketik ulang kata sandi" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
    </div>
    
    <!-- Submit Button -->
    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/20 mt-2 flex items-center justify-center gap-2">
        <span>Daftar Akun</span>
        <span class="material-icons text-sm">person_add</span>
    </button>
</form>

<div class="mt-8 text-center text-xs font-semibold text-slate-400">
    Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Masuk disini</a>
</div>
@endsection
