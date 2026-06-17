<aside class="w-full md:w-64 bg-white dark:bg-slate-900 border-r border-slate-150 dark:border-slate-800 flex flex-col md:min-h-screen z-10 flex-shrink-0 transition-colors duration-300">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-150 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex justify-between items-center transition-colors duration-300">
        <div class="flex items-center gap-2">
            <span class="material-icons text-emerald-600 dark:text-emerald-400">admin_panel_settings</span>
            <span class="font-extrabold text-lg tracking-wider text-slate-800 dark:text-white">UMK<span class="text-emerald-600 dark:text-emerald-400">MART</span></span>
        </div>
        <div class="md:hidden">
            <button onclick="document.getElementById('mobile-admin-menu').classList.toggle('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                <span class="material-icons">menu</span>
            </button>
        </div>
    </div>
    
    <!-- Sidebar Menu Items -->
    <nav id="mobile-admin-menu" class="hidden md:flex flex-col flex-grow p-4 gap-1.5 overflow-y-auto bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">Utama</div>
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.dashboard') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">dashboard</span>
            <span>Dashboard</span>
        </a>
        
        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 px-3 mt-4 mb-2">Manajemen Toko</div>
        
        <a href="{{ route('admin.products') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.products') || Route::is('admin.products.create') || Route::is('admin.products.edit') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">inventory_2</span>
            <span>Kelola Produk</span>
        </a>
        
        <a href="{{ route('admin.vouchers') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.vouchers') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">confirmation_number</span>
            <span>Kelola Voucher</span>
        </a>
        
        <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.orders') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">shopping_bag</span>
            <span>Kelola Pesanan</span>
        </a>
        
        <a href="{{ route('admin.customers') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.customers') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">people</span>
            <span>Kelola Customer</span>
        </a>

        <a href="{{ route('admin.chat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.chat') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">forum</span>
            <span>Chat Customer</span>
        </a>
        
        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 px-3 mt-4 mb-2">Analitik & Laporan</div>
        
        <a href="{{ route('admin.sales') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.sales') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">trending_up</span>
            <span>Penjualan</span>
        </a>
        
        <a href="{{ route('admin.finance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.finance') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">payments</span>
            <span>Laporan Keuangan</span>
        </a>
        
        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 px-3 mt-4 mb-2">Pengaturan</div>
        
        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
            {{ Route::is('admin.profile') 
                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/10' 
                : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
            <span class="material-icons text-lg">person</span>
            <span>Profil Admin</span>
        </a>

        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all mt-auto
            hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
            <span class="material-icons text-lg">storefront</span>
            <span>Lihat Toko</span>
        </a>
    </nav>
</aside>
