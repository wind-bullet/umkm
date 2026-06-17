<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\VoucherItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\AdminEmail;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Admin Dashboard
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();
        
        // Net Revenue from successful payments
        $netRevenue = Order::whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])->sum('total');

        // Recent Orders
        $recentOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Top Selling Products (grouped by product_id in order_items)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        // Monthly Sales for Chart (simulated based on orders in database)
        $salesData = Order::select(
                DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartMonths = [];
        $chartRevenue = [];
        
        // Initialize all months to 0
        for ($i = 1; $i <= 12; $i++) {
            $chartMonths[] = $months[$i - 1];
            $chartRevenue[$i] = 0;
        }

        foreach ($salesData as $data) {
            if ($data->month >= 1 && $data->month <= 12) {
                $chartRevenue[$data->month] = (float)$data->revenue;
            }
        }

        // Convert back to index array for JS
        $chartRevenueValues = array_values($chartRevenue);

        return view('admin.dashboard', compact(
            'totalProducts', 'totalUsers', 'totalOrders', 'netRevenue', 
            'recentOrders', 'topProducts', 'chartMonths', 'chartRevenueValues'
        ));
    }

    // --- PRODUCTS CRUD ---
    public function products()
    {
        $products = Product::with('category')->paginate(10);
        $totalCount = Product::count();
        $lowStockCount = Product::where('stock', '<', 5)->count();
        $categoryCount = Category::count();
        
        return view('admin.products.index', compact('products', 'totalCount', 'lowStockCount', 'categoryCount'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock']);
        $data['rating'] = 0.00;
        $data['review_count'] = 0;
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        } else {
            $data['image'] = 'default_product.png';
        }

        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'price', 'stock']);
        $data['is_active'] = $request->has('is_active') ? $request->is_active : $product->is_active;

        if ($request->hasFile('image')) {
            // Delete old image if not default
            if ($product->image && $product->image != 'default_product.png' && file_exists(public_path('uploads/products/' . $product->image))) {
                @unlink(public_path('uploads/products/' . $product->image));
            }

            $imageName = time() . '_' . Str::slug($request->name) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Delete image if exists
        if ($product->image && $product->image != 'default_product.png' && file_exists(public_path('uploads/products/' . $product->image))) {
            @unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    // --- ORDERS MANAGEMENT ---
    public function orders(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Order::with('user');
        
        if ($status != 'all') {
            $query->where('order_status', $status);
        }

        $orders = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.orders', compact('orders', 'status'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:menunggu_pembayaran,dibayar,diproses,dikirim,selesai',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->order_status;
        $order->order_status = $request->order_status;
        $order->save();

        // If paid, update payment status
        if ($request->order_status == 'dibayar') {
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment) {
                $payment->update([
                    'payment_status' => 'success',
                    'paid_at' => now(),
                ]);
            }
        }

        // Notify Customer
        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Update Status Pesanan',
            'message' => "Status pesanan Anda {$order->order_code} diubah dari '" . str_replace('_', ' ', $oldStatus) . "' menjadi '" . str_replace('_', ' ', $request->order_status) . "'.",
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // --- VOUCHERS MANAGEMENT ---
    public function vouchers()
    {
        $vouchers = VoucherItem::with('product')->paginate(10);
        $products = Product::where('category_id', function($q) {
            $q->select('id')->from('categories')->where('slug', 'voucher')->limit(1);
        })->get();

        return view('admin.vouchers', compact('vouchers', 'products'));
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:voucher_items,product_id',
            'voucher_type' => 'required|string',
            'voucher_label' => 'required|string',
        ]);

        VoucherItem::create($request->only(['product_id', 'voucher_type', 'voucher_label']));

        return back()->with('success', 'Voucher item berhasil ditambahkan.');
    }

    public function deleteVoucher($id)
    {
        $voucher = VoucherItem::findOrFail($id);
        $voucher->delete();

        return back()->with('success', 'Voucher item berhasil dihapus.');
    }

    // --- CUSTOMERS LIST ---
    public function customers()
    {
        $adminEmails = AdminEmail::pluck('email')->toArray();
        
        // Customers are users who are not admins
        $customers = User::whereNotIn('email', $adminEmails)
            ->withCount('orders')
            ->paginate(10);

        return view('admin.customers', compact('customers'));
    }

    // --- ANALYTICS PAGES ---
    public function sales()
    {
        // Top selling products list
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(8)
            ->get();

        // Order counts by status
        $statusCounts = Order::select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status')
            ->toArray();

        $allStatuses = ['menunggu_pembayaran', 'dibayar', 'diproses', 'dikirim', 'selesai'];
        $orderStats = [];
        foreach ($allStatuses as $st) {
            $orderStats[$st] = isset($statusCounts[$st]) ? $statusCounts[$st] : 0;
        }

        return view('admin.sales', compact('topProducts', 'orderStats'));
    }

    public function finance()
    {
        // Net revenue
        $totalRevenue = Order::whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])->sum('total');
        
        // Avg order value
        $avgOrder = Order::whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])->avg('total') ?: 0;

        // Breakdown by payment method
        $paymentBreakdown = Order::select('payment_method', DB::raw('SUM(total) as revenue'))
            ->whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->groupBy('payment_method')
            ->get();

        // Recent financial transactions (completed or paid orders)
        $transactions = Order::with('user')
            ->whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('admin.finance', compact('totalRevenue', 'avgOrder', 'paymentBreakdown', 'transactions'));
    }

    // --- ADMIN PROFILE ---
    public function profile()
    {
        $admin = Auth::user();
        $totalProducts = Product::count();
        $totalRevenue = Order::whereIn('order_status', ['dibayar', 'diproses', 'dikirim', 'selesai'])->sum('total');

        return view('admin.profile', compact('admin', 'totalProducts', 'totalRevenue'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $oldEmail = $admin->email;

        $admin->name = $request->name;
        $admin->phone_number = $request->phone_number;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        // Update admin_emails table if email changed
        if ($oldEmail !== $request->email) {
            AdminEmail::where('email', $oldEmail)->update(['email' => $request->email]);
        }

        return back()->with('success', 'Profil Admin berhasil diperbarui.');
    }
}
