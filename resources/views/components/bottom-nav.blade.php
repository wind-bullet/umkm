<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-950 border-t border-slate-100 dark:border-slate-850 h-16 flex items-center justify-around z-50 transition-colors duration-300">
    <!-- Home -->
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
        <span class="material-icons text-xl {{ Route::is('home') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">home</span>
        <span class="text-[10px] font-semibold mt-0.5 {{ Route::is('home') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">Beranda</span>
    </a>
    
    <!-- Catalog / Search -->
    <a href="{{ route('search') }}" class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
        <span class="material-icons text-xl {{ Route::is('search') || Route::is('category') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">search</span>
        <span class="text-[10px] font-semibold mt-0.5 {{ Route::is('search') || Route::is('category') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">Katalog</span>
    </a>
    
    <!-- Chat -->
    @auth
        <a href="{{ Auth::user()->isAdmin() ? route('admin.chat') : route('user.chat') }}" class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            <span class="material-icons text-xl {{ Route::is('user.chat') || Route::is('admin.chat') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">chat</span>
            <span class="text-[10px] font-semibold mt-0.5 {{ Route::is('user.chat') || Route::is('admin.chat') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">Chat</span>
        </a>
        
        <!-- Notifications -->
        <a href="{{ route('user.notifications') }}" class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 relative transition-colors">
            <span class="material-icons text-xl {{ Route::is('user.notifications') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">notifications</span>
            <span class="notification-badge hidden absolute top-0 right-2 w-3.5 h-3.5 rounded-full bg-rose-500 text-[8px] text-white flex items-center justify-center font-bold">0</span>
            <span class="text-[10px] font-semibold mt-0.5 {{ Route::is('user.notifications') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">Notifikasi</span>
        </a>
        
        <!-- Profile / Account -->
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            <span class="material-icons text-xl {{ Route::is('user.dashboard') || Route::is('user.profile') || Route::is('admin.dashboard') || Route::is('admin.profile') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">person</span>
            <span class="text-[10px] font-semibold mt-0.5 {{ Route::is('user.dashboard') || Route::is('user.profile') || Route::is('admin.dashboard') || Route::is('admin.profile') ? 'text-emerald-600 dark:text-emerald-400' : '' }}">Profil</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
            <span class="material-icons text-xl">login</span>
            <span class="text-[10px] font-semibold mt-0.5">Masuk</span>
        </a>
    @endauth
</div>
