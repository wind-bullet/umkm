@extends('layouts.app')

@section('title', 'Notifikasi - AstridMart')

@section('content')
<div class="flex justify-between items-center mb-6 text-left">
    <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
        <span class="material-icons text-emerald-600 dark:text-emerald-400">notifications</span>
        Notifikasi Anda
    </h1>
    
    <form action="{{ route('notifications.mark_read') }}" method="POST">
        @csrf
        <button type="submit" class="flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
            <span class="material-icons text-xs">done_all</span> Tandai Semua Dibaca
        </button>
    </form>
</div>

<div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 shadow-sm text-left">
    @if($notifications->count() > 0)
        <div class="flex flex-col gap-4">
            @foreach($notifications as $notif)
                <div class="p-4 rounded-2xl border transition-colors flex items-start gap-4 
                    {{ $notif->is_read 
                        ? 'bg-slate-50/20 border-slate-50 dark:bg-slate-900/10 dark:border-slate-900/40 text-slate-500' 
                        : 'bg-emerald-50/30 border-emerald-100 dark:bg-emerald-950/10 dark:border-emerald-950/30 text-slate-800 dark:text-slate-200' }}">
                    
                    <!-- Icon -->
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $notif->is_read 
                            ? 'bg-slate-100 dark:bg-slate-800 text-slate-400' 
                            : 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400' }}">
                        <span class="material-icons text-sm">
                            {{ Str::contains($notif->title, 'Pesanan') ? 'shopping_bag' : (Str::contains($notif->title, 'Pesan') ? 'chat' : 'info') }}
                        </span>
                    </div>
                    
                    <!-- Content -->
                    <div class="min-w-0 flex-grow text-left">
                        <div class="flex items-center justify-between gap-4">
                            <h4 class="font-bold text-xs">{{ $notif->title }}</h4>
                            <span class="text-[9px] text-slate-400 flex-shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs mt-1 leading-relaxed">{{ $notif->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="py-12 text-center">
            <span class="material-icons text-slate-350 dark:text-slate-700 text-5xl">notifications_off</span>
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mt-2">Tidak Ada Notifikasi</h3>
            <p class="text-[10px] text-slate-400 mt-1">Kotak masuk notifikasi Anda saat ini masih kosong.</p>
        </div>
    @endif
</div>
@endsection
