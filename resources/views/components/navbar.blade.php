<nav class="bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 fixed top-0 w-full z-50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            
            <!-- Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <span class="material-icons text-emerald-600 dark:text-emerald-400 text-2xl md:text-3xl">shopping_basket</span>
                    <span class="font-extrabold text-xl md:text-2xl tracking-tight text-slate-800 dark:text-white">Astrid<span class="text-emerald-600 dark:text-emerald-400">Mart</span></span>
                </a>
                
                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 text-sm font-semibold">Beranda</a>
                    <a href="{{ route('search') }}" class="text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 text-sm font-semibold">Produk</a>
                </div>
            </div>
            
            <!-- Search Bar (Desktop) -->
            <div class="hidden md:block flex-grow max-w-md relative" id="search-container">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" name="q" id="search-input" autocomplete="off" placeholder="Cari kaos, keripik, jam tangan..." class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-full py-2 pl-4 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800 dark:text-slate-200">
                    <button type="submit" class="absolute right-3 top-2 text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400">
                        <span class="material-icons text-sm">search</span>
                    </button>
                </form>
                
                <!-- Live Search Overlay -->
                <div id="search-results-overlay" class="hidden absolute top-12 left-0 right-0 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl z-50 p-2 max-h-80 overflow-y-auto">
                    <!-- Results will be injected here via JS -->
                </div>
            </div>
            
            <!-- Navbar Action Buttons -->
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Theme Switcher -->
                <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors" title="Ubah Tema">
                    <span id="theme-toggle-icon" class="material-icons">dark_mode</span>
                </button>
                
                @auth
                    <!-- Notifications -->
                    <a href="{{ route('user.notifications') }}" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 relative transition-colors" title="Notifikasi">
                        <span class="material-icons">notifications</span>
                        <span class="notification-badge hidden absolute top-1 right-1 w-4 h-4 rounded-full bg-rose-500 text-[10px] text-white flex items-center justify-center font-bold">0</span>
                    </a>
                    
                    <!-- Chat -->
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.chat') : route('user.chat') }}" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 relative transition-colors" title="{{ Auth::user()->isAdmin() ? 'Chat Pelanggan' : 'Chat Admin' }}">
                        <span class="material-icons">chat</span>
                    </a>
                    
                    <!-- Cart -->
                    @if(!Auth::user()->isAdmin())
                        <a href="{{ route('cart') }}" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 relative transition-colors mr-2" title="Keranjang Belanja">
                            <span class="material-icons">shopping_cart</span>
                            @php
                                $cartCount = Auth::user()->cart ? Auth::user()->cart->items()->sum('qty') : 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-emerald-500 text-[10px] text-white flex items-center justify-center font-bold">{{ $cartCount }}</span>
                            @endif
                        </a>
                    @endif
                    
                    <!-- Profile Menu (Desktop) -->
                    <div class="hidden md:block relative" id="profile-menu-container">
                        <button onclick="document.getElementById('profile-dropdown').classList.toggle('hidden')" class="w-8 h-8 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center transition-colors shadow-sm" title="{{ Auth::user()->name }}">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown" class="hidden absolute right-0 top-10 w-48 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl z-50 p-2">
                            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center gap-2 p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold">
                                <span class="material-icons text-sm text-slate-400">dashboard</span> Dashboard
                            </a>
                            <a href="{{ Auth::user()->isAdmin() ? route('admin.profile') : route('user.profile') }}" class="flex items-center gap-2 p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold">
                                <span class="material-icons text-sm text-slate-400">person</span> Profil Saya
                            </a>
                            @if(!Auth::user()->isAdmin())
                            <a href="{{ route('user.orders') }}" class="flex items-center gap-2 p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold">
                                <span class="material-icons text-sm text-slate-400">shopping_bag</span> Riwayat Belanja
                            </a>
                            @endif
                            @if(Auth::user()->isAdmin())
                                <hr class="my-1 border-slate-100 dark:border-slate-800">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 p-2 rounded-xl text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 text-sm font-bold">
                                    <span class="material-icons text-sm">admin_panel_settings</span> Admin Panel
                                </a>
                            @endif
                            <hr class="my-1 border-slate-100 dark:border-slate-800">
                            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 p-2 rounded-xl text-rose-600 w-full hover:bg-rose-50 dark:hover:bg-rose-950/20 text-sm font-semibold text-left">
                                    <span class="material-icons text-sm">logout</span> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Authentication link buttons -->
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 px-3 py-2 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-block text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-full transition-colors shadow-lg shadow-emerald-600/20">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Live Search Overlay Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-input');
        const overlay = document.getElementById('search-results-overlay');
        
        if (searchInput && overlay) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.trim();
                if (query.length < 2) {
                    overlay.classList.add('hidden');
                    return;
                }
                
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(products => {
                        overlay.innerHTML = '';
                        if (products.length === 0) {
                            overlay.innerHTML = '<p class="text-slate-400 text-xs p-4 text-center">Tidak ada produk ditemukan</p>';
                        } else {
                            products.forEach(p => {
                                const el = document.createElement('a');
                                el.href = `/product/${p.id}`;
                                el.className = 'flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors';
                                el.innerHTML = `
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0">
                                        <img src="/desain_sample/screen1.png" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=AstridMart'">
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate">${p.name}</h4>
                                        <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">Rp ${Number(p.price).toLocaleString('id-ID')}</p>
                                    </div>
                                `;
                                overlay.appendChild(el);
                            });
                        }
                        overlay.classList.remove('hidden');
                    })
                    .catch(() => {
                        overlay.classList.add('hidden');
                    });
            });
            
            // Hide overlay when clicking outside
            document.addEventListener('click', (e) => {
                if (!document.getElementById('search-container').contains(e.target)) {
                    overlay.classList.add('hidden');
                }
            });
            
            // Dropdown profile toggle close
            const profileMenuContainer = document.getElementById('profile-menu-container');
            if (profileMenuContainer) {
                document.addEventListener('click', (e) => {
                    if (!profileMenuContainer.contains(e.target)) {
                        const dropdown = document.getElementById('profile-dropdown');
                        if (dropdown) dropdown.classList.add('hidden');
                    }
                });
            }
        }
    });
</script>
