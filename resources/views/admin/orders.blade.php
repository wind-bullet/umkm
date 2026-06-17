@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Admin UMKMART')
@section('page_title', 'Kelola Pesanan Masuk')

@section('content')
<!-- Status Tabs -->
<div class="mb-6 overflow-x-auto pb-2 flex gap-2 text-left">
    @php
        $statuses = [
            'all' => 'Semua',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'dibayar' => 'Sudah Dibayar',
            'diproses' => 'Sedang Diproses',
            'dikirim' => 'Sedang Dikirim',
            'selesai' => 'Selesai',
        ];
    @endphp
    @foreach($statuses as $key => $lbl)
        <a href="{{ route('admin.orders', ['status' => $key]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm
            {{ $status === $key 
                ? 'bg-emerald-600 text-white border-none' 
                : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50' }}">
            {{ $lbl }}
        </a>
    @endforeach
</div>

<div class="bg-white border border-slate-150 rounded-2xl p-6 shadow-sm text-left">
    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase border-b border-slate-150">
                        <th class="pb-3 pl-2">Kode Order</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Pengiriman</th>
                        <th class="pb-3">Pembayaran</th>
                        <th class="pb-3">Total Belanja</th>
                        <th class="pb-3">Status Pesanan</th>
                        <th class="pb-3 text-right pr-2">Aksi Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $order)
                        @php
                            $colors = [
                                'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'dibayar' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'diproses' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'dikirim' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            ];
                            $labels = [
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'dibayar' => 'Sudah Dibayar',
                                'diproses' => 'Sedang Diproses',
                                'dikirim' => 'Sedang Dikirim',
                                'selesai' => 'Selesai',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 pl-2 font-bold text-slate-800">
                                <button onclick="showOrderModal({{ json_encode($order->load('items.product')) }})" class="hover:text-emerald-600 hover:underline">
                                    {{ $order->order_code }}
                                </button>
                            </td>
                            <td class="py-4 font-semibold text-slate-700">
                                <p>{{ $order->user->name }}</p>
                                <p class="text-[9px] text-slate-400 mt-0.5">{{ $order->user->phone_number }}</p>
                            </td>
                            <td class="py-4 text-slate-600 font-semibold">{{ $order->delivery_method }}</td>
                            <td class="py-4 text-slate-500">{{ $order->payment_method }}</td>
                            <td class="py-4 font-bold text-slate-850">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-0.5 rounded-full border text-[9px] font-bold {{ $colors[$order->order_status] ?? 'bg-slate-100' }}">
                                    {{ $labels[$order->order_status] ?? $order->order_status }}
                                </span>
                            </td>
                            <td class="py-4 text-right pr-2">
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="order_status" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-lg py-1.5 px-2 text-[10px] focus:outline-none text-slate-650 font-bold cursor-pointer">
                                        <option value="menunggu_pembayaran" {{ $order->order_status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="dibayar" {{ $order->order_status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                        <option value="diproses" {{ $order->order_status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="dikirim" {{ $order->order_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="selesai" {{ $order->order_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="py-12 text-center text-slate-400">
            <span class="material-icons text-5xl">receipt_long</span>
            <p class="text-xs font-bold mt-2">Tidak ada pesanan.</p>
        </div>
    @endif
</div>

<!-- Order Detail Modal -->
<div id="order-detail-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in text-left">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                <span class="material-icons text-emerald-600 text-lg">receipt_long</span>
                Rincian Pesanan <span id="modal-order-code"></span>
            </h3>
            <button onclick="closeOrderModal()" class="p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50">
                <span class="material-icons text-lg">close</span>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[380px] flex flex-col gap-6">
            <!-- Customer info -->
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Customer</h4>
                <p class="text-xs font-bold text-slate-800" id="modal-customer-name"></p>
                <p class="text-[10px] text-slate-400 mt-0.5" id="modal-customer-phone"></p>
            </div>
            
            <!-- Shipping address -->
            <div id="modal-shipping-address-container" class="hidden">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Pengiriman</h4>
                <p class="text-xs text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100 leading-relaxed whitespace-pre-line" id="modal-shipping-address"></p>
            </div>
            
            <!-- Items bought -->
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Barang</h4>
                <div id="modal-items-container" class="flex flex-col gap-3">
                    <!-- Dynamic items will be added here -->
                </div>
            </div>
            
            <!-- Summary cost -->
            <div class="bg-slate-50 p-4 rounded-2xl text-xs flex flex-col gap-2">
                <div class="flex justify-between">
                    <span class="text-slate-500">Subtotal Belanja</span>
                    <span class="font-bold text-slate-800" id="modal-subtotal"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Ongkos Kirim</span>
                    <span class="font-bold text-slate-800" id="modal-shipping"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode Pengiriman</span>
                    <span class="font-bold text-slate-850" id="modal-delivery"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode Pembayaran</span>
                    <span class="font-bold text-slate-850" id="modal-payment"></span>
                </div>
                <hr class="border-slate-150 my-1">
                <div class="flex justify-between text-sm font-bold">
                    <span class="text-slate-800">Total Tagihan</span>
                    <span class="text-emerald-600" id="modal-total"></span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button onclick="closeOrderModal()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-6 rounded-xl text-xs transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showOrderModal(order) {
        document.getElementById('modal-order-code').textContent = order.order_code;
        document.getElementById('modal-customer-name').textContent = order.user.name;
        document.getElementById('modal-customer-phone').textContent = order.user.email + ' | ' + order.user.phone_number;
        
        const addressContainer = document.getElementById('modal-shipping-address-container');
        const addressText = document.getElementById('modal-shipping-address');
        if (order.shipping_address) {
            addressText.textContent = order.shipping_address;
            addressContainer.classList.remove('hidden');
        } else {
            addressContainer.classList.add('hidden');
        }
        
        document.getElementById('modal-subtotal').textContent = 'Rp ' + Number(order.subtotal).toLocaleString('id-ID');
        document.getElementById('modal-shipping').textContent = 'Rp ' + Number(order.shipping_cost).toLocaleString('id-ID');
        document.getElementById('modal-delivery').textContent = order.delivery_method;
        document.getElementById('modal-payment').textContent = order.payment_method;
        document.getElementById('modal-total').textContent = 'Rp ' + Number(order.total).toLocaleString('id-ID');
        
        const itemsContainer = document.getElementById('modal-items-container');
        itemsContainer.innerHTML = '';
        
        order.items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 justify-between';
            div.innerHTML = `
                <div class="min-w-0 pr-4">
                    <p class="font-bold text-slate-800 truncate text-[11px]">${item.product ? item.product.name : 'Produk Dihapus'}</p>
                    <p class="text-[9px] text-slate-400 mt-0.5">${item.qty}x @ Rp ${Number(item.price_snapshot).toLocaleString('id-ID')}</p>
                </div>
                <span class="text-[11px] font-bold text-slate-700 flex-shrink-0">Rp ${Number(item.qty * item.price_snapshot).toLocaleString('id-ID')}</span>
            `;
            itemsContainer.appendChild(div);
        });
        
        document.getElementById('order-detail-modal').classList.remove('hidden');
    }
    
    function closeOrderModal() {
        document.getElementById('order-detail-modal').classList.add('hidden');
    }
</script>
@endsection
