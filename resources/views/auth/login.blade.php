@extends('layouts.auth')

@section('title', 'Masuk - UMKMART')

@section('content')
<div class="text-center lg:text-left mb-6">
    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h2>
    <p class="text-slate-500 mt-2 text-sm">Masuk untuk mulai berbelanja di UMKMART.</p>
</div>

@if(session('error'))
    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
        <span class="material-icons text-sm">error</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session('success'))
    <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
        <span class="material-icons text-sm">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
    @csrf
    
    <!-- Email -->
    <div>
        <label for="email" class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">mail</span>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="contoh@domain.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
        @error('email')
            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Password -->
    <div>
        <div class="flex justify-between items-baseline mb-2">
            <label for="password" class="block text-xs font-bold text-slate-500 uppercase">Kata Sandi</label>
        </div>
        <div class="relative">
            <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">lock</span>
            <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800">
        </div>
    </div>
    
    <!-- Remember Me -->
    <div class="flex items-center">
        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
        <label for="remember" class="ml-2 text-xs font-semibold text-slate-500 select-none cursor-pointer">Ingat saya di perangkat ini</label>
    </div>
    
    <!-- Submit Button -->
    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/20 mt-2 flex items-center justify-center gap-2">
        <span>Masuk</span>
        <span class="material-icons text-sm">login</span>
    </button>
</form>

<div class="mt-8 text-center text-xs font-semibold text-slate-400">
    Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">Daftar sekarang</a>
</div>
@endsection
