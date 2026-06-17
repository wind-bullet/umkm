@extends('layouts.app')

@section('title', 'Profil Saya - UMKMART')

@section('content')
<h1 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-6 flex items-center gap-2 text-left">
    <span class="material-icons text-emerald-600 dark:text-emerald-400">person</span>
    Pengaturan Akun & Profil
</h1>

<div class="flex flex-col lg:flex-row gap-8 text-left">
    
    <!-- Profile Edit Form (Left/Main) -->
    <div class="flex-grow">
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 sm:p-8 rounded-3xl shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-6 pb-2 border-b border-slate-100 dark:border-slate-850">Informasi Pribadi</h3>
            
            <form action="{{ url('/user/profile') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white">
                        @error('name')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Telepon -->
                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Telepon</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white">
                        @error('phone_number')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white">
                        @error('email')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3" placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos)..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <h3 class="font-bold text-slate-800 dark:text-white text-sm mt-4 pb-2 border-b border-slate-100 dark:border-slate-850">Ubah Kata Sandi (Kosongkan jika tidak diubah)</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" placeholder="Min. 6 Karakter" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white">
                        @error('password')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-white">
                    </div>
                </div>
                
                <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl text-xs transition-colors shadow-lg shadow-emerald-600/15 mt-2 ml-auto">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
    
    <!-- Settings Sidebar (Right) -->
    <div class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-6">
        <!-- Theme Toggle Card -->
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-3xl shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 pb-2 border-b border-slate-100 dark:border-slate-850">Tema Tampilan</h3>
            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900 rounded-2xl">
                <span class="text-xs font-bold text-slate-650 dark:text-slate-350">Mode Gelap</span>
                
                <!-- Toggle switch slider -->
                <button onclick="toggleTheme(); updateToggleState()" id="theme-toggle-switch" class="w-12 h-6 rounded-full bg-slate-300 dark:bg-emerald-600 transition-colors relative flex items-center px-1">
                    <div id="theme-toggle-dot" class="w-4 h-4 rounded-full bg-white transition-transform transform translate-x-0 dark:translate-x-6"></div>
                </button>
            </div>
        </div>
        
        <!-- Recent Orders summary -->
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-6 rounded-3xl shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 pb-2 border-b border-slate-100 dark:border-slate-850">Pesanan Terakhir</h3>
            <div class="flex flex-col gap-3">
                @forelse($recentOrders as $order)
                    <div class="flex justify-between items-center text-xs py-1.5 border-b border-slate-50 dark:border-slate-900/20 last:border-0">
                        <div>
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $order->order_code }}</p>
                            <p class="text-[9px] text-slate-400 mt-0.5">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('order.status', $order->order_code) }}" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Detail</a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-2">Belum ada riwayat pesanan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateToggleState() {
        const switchBg = document.getElementById('theme-toggle-switch');
        const dot = document.getElementById('theme-toggle-dot');
        
        if (switchBg && dot) {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                switchBg.classList.replace('bg-slate-300', 'bg-emerald-600');
                dot.style.transform = 'translateX(24px)';
            } else {
                switchBg.classList.replace('bg-emerald-600', 'bg-slate-300');
                dot.style.transform = 'translateX(0)';
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        updateToggleState();
    });
</script>
@endsection
