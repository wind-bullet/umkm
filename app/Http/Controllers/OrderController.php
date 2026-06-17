<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingOption;
use App\Models\PaymentMethod;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();
        $shippingOptions = ShippingOption::where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->qty * $item->product->price;
        }

        return view('public.checkout', compact('cartItems', 'shippingOptions', 'paymentMethods', 'subtotal'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_option_id' => 'required|exists:shipping_options,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items()->count() == 0) {
            return redirect('/cart')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();
        $shippingOption = ShippingOption::findOrFail($request->shipping_option_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Check if shipping option requires delivery (not pickup/ambil)
        $isDelivery = !Str::contains(strtolower($shippingOption->name), 'ambil');
        if ($isDelivery) {
            $request->validate([
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:500',
            ]);
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            // Check stock before ordering
            if ($item->product->stock < $item->qty) {
                return redirect('/cart')->with('error', "Stok produk {$item->product->name} tidak mencukupi.");
            }
            $subtotal += $item->qty * $item->product->price;
        }

        $shippingCost = $shippingOption->fee_value;
        $total = $subtotal + $shippingCost;
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        DB::transaction(function () use ($user, $orderCode, $subtotal, $shippingCost, $total, $paymentMethod, $shippingOption, $cartItems, $cart, $request, $isDelivery) {
            // 1. Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => $orderCode,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $paymentMethod->name,
                'delivery_method' => $shippingOption->name,
                'shipping_address' => $isDelivery 
                    ? "Penerima: " . $request->recipient_name . "\nTelepon: " . $request->recipient_phone . "\nAlamat: " . $request->shipping_address 
                    : null,
                'order_status' => 'menunggu_pembayaran',
            ]);

            // Save/Update user profile address and info if changed
            if ($isDelivery) {
                $profileUpdated = false;
                if ($request->filled('recipient_name') && $user->name !== $request->recipient_name) {
                    $user->name = $request->recipient_name;
                    $profileUpdated = true;
                }
                if ($request->filled('recipient_phone') && $user->phone_number !== $request->recipient_phone) {
                    $user->phone_number = $request->recipient_phone;
                    $profileUpdated = true;
                }
                if ($request->filled('shipping_address') && $user->address !== $request->shipping_address) {
                    $user->address = $request->shipping_address;
                    $profileUpdated = true;
                }
                if ($profileUpdated) {
                    $user->save();
                }
            }

            // 2. Create Order Items & Decrement Stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price_snapshot' => $item->product->price,
                ]);

                // Decrement stock
                $product = $item->product;
                $product->decrement('stock', $item->qty);

                // Admin notification if stock is running low (< 5)
                if ($product->stock < 5) {
                    $adminUser = User::whereHas('orders', function() {}, '!=', 0)->first(); // fallback admin check
                    // Actually let's query all admin users
                    $adminEmails = \App\Models\AdminEmail::pluck('email')->toArray();
                    $admins = User::whereIn('email', $adminEmails)->get();
                    foreach ($admins as $admin) {
                        Notification::create([
                            'user_id' => $admin->id,
                            'title' => 'Stok Menipis!',
                            'message' => "Stok produk '{$product->name}' tersisa {$product->stock} unit. Segera lakukan restok.",
                        ]);
                    }
                }
            }

            // 3. Create Payment Record (Simulated)
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod->name,
                'payment_status' => 'pending',
            ]);

            // 4. Create Notification for User
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pesanan Berhasil Dibuat',
                'message' => "Pesanan Anda {$orderCode} telah berhasil dibuat. Silakan lakukan pembayaran senilai Rp " . number_format($total, 0, ',', '.') . " menggunakan {$paymentMethod->name}.",
            ]);

            // 5. Create Notification for Admins
            $adminEmails = \App\Models\AdminEmail::pluck('email')->toArray();
            $admins = User::whereIn('email', $adminEmails)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Pesanan Baru',
                    'message' => "Pesanan baru {$orderCode} dari customer {$user->name} dengan total Rp " . number_format($total, 0, ',', '.') . ".",
                ]);
            }

            // 6. Delete Cart Items
            CartItem::where('cart_id', $cart->id)->delete();
        });

        return redirect('/order/' . $orderCode)->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    public function status($code)
    {
        $order = Order::with(['items.product', 'payment'])->where('order_code', $code)->firstOrFail();
        
        // Ensure user can only view their own order
        if (Auth::user()->id !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        return view('public.order-status', compact('order'));
    }

    public function history()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }
}
