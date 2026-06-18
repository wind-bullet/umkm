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
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($product->stock < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi. Stok saat ini: ' . $product->stock);
        }

        session(['buy_now' => [
            'product_id' => $request->product_id,
            'qty' => $request->qty,
        ]]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk melanjutkan pembelian.');
        }

        return redirect()->route('checkout');
    }

    public function checkout()
    {
        $user = Auth::user();
        
        if (session()->has('buy_now')) {
            $buyNowData = session('buy_now');
            $product = Product::findOrFail($buyNowData['product_id']);
            
            // Construct a mock CartItem object
            $mockItem = new \stdClass();
            $mockItem->product_id = $product->id;
            $mockItem->qty = $buyNowData['qty'];
            $mockItem->product = $product;
            
            $cartItems = collect([$mockItem]);
        } else {
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart || $cart->items()->count() == 0) {
                return redirect('/cart')->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();
        }

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
        
        $isBuyNow = session()->has('buy_now');
        $cart = null;
        if ($isBuyNow) {
            $buyNowData = session('buy_now');
            $product = Product::findOrFail($buyNowData['product_id']);
            
            $mockItem = new \stdClass();
            $mockItem->product_id = $product->id;
            $mockItem->qty = $buyNowData['qty'];
            $mockItem->product = $product;
            
            $cartItems = collect([$mockItem]);
        } else {
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart || $cart->items()->count() == 0) {
                return redirect('/cart')->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();
        }

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
                $redirectUrl = $isBuyNow ? "/product/{$item->product_id}" : "/cart";
                return redirect($redirectUrl)->with('error', "Stok produk {$item->product->name} tidak mencukupi.");
            }
            $subtotal += $item->qty * $item->product->price;
        }

        $shippingCost = $shippingOption->fee_value;
        $total = $subtotal + $shippingCost;
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $paymentMethodName = $paymentMethod->name;
        if ($paymentMethodName === 'Bayar di Toko') {
            if (!Str::contains(strtolower($shippingOption->name), 'ambil')) {
                $paymentMethodName = 'COD';
            }
        }

        DB::transaction(function () use ($user, $orderCode, $subtotal, $shippingCost, $total, $paymentMethodName, $shippingOption, $cartItems, $isBuyNow, $cart, $request, $isDelivery) {
            // 1. Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => $orderCode,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $paymentMethodName,
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
                    $adminEmails = \App\Models\AdminEmail::pluck('email')->toArray();
                    $admins = User::whereIn('email', $adminEmails)->get();
                    foreach ($admins as $admin) {
                        Notification::create([
                            'user_id' => $admin->id,
                            'title' => 'Stok Menipis!',
                            'message' => "Stok produk '{$product->name}' tersisa {$product->stock} unit. Segera lakukan restok.",
                            'related_url' => '/admin/products',
                        ]);
                    }
                }
            }

            // 3. Create Payment Record (Simulated)
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethodName,
                'payment_status' => 'pending',
            ]);

            // 4. Create Notification for User
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pesanan Berhasil Dibuat',
                'message' => "Pesanan Anda {$orderCode} telah berhasil dibuat. Silakan lakukan pembayaran senilai Rp " . number_format($total, 0, ',', '.') . " menggunakan {$paymentMethodName}.",
                'related_url' => "/order/{$orderCode}",
            ]);

            // 5. Create Notification for Admins
            $adminEmails = \App\Models\AdminEmail::pluck('email')->toArray();
            $admins = User::whereIn('email', $adminEmails)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Pesanan Baru',
                    'message' => "Pesanan baru {$orderCode} dari customer {$user->name} dengan total Rp " . number_format($total, 0, ',', '.') . ".",
                    'related_url' => "/admin/chat?contact_id={$user->id}",
                ]);
            }

            // 6. Delete Cart Items OR Forget Buy Now Session
            if ($isBuyNow) {
                session()->forget('buy_now');
            } else {
                if ($cart) {
                    CartItem::where('cart_id', $cart->id)->delete();
                }
            }

            // 7. Auto-generate chat message template
            $adminUser = User::whereIn('email', $adminEmails)->first();
            if ($adminUser) {
                $orderItemsText = '';
                foreach ($cartItems as $item) {
                    $categoryName = $item->product->category->name ?? '-';
                    $itemTotal = $item->qty * $item->product->price;
                    $orderItemsText .= "  • {$item->product->name} ({$categoryName}) x{$item->qty} - Rp " . number_format($itemTotal, 0, ',', '.') . "\n";
                }

                $templateMessage = "📦 Pesanan Baru\n"
                    . "━━━━━━━━━━━━━━━\n"
                    . "🔖 No. Pesanan: {$orderCode}\n"
                    . "📅 Waktu: " . now()->format('d M Y, H:i') . "\n\n"
                    . "🛍️ Daftar Barang:\n"
                    . $orderItemsText . "\n"
                    . "🚚 Pengiriman: {$shippingOption->name}\n"
                    . "💳 Pembayaran: {$paymentMethodName}\n"
                    . "💰 Total: Rp " . number_format($total, 0, ',', '.') . "\n"
                    . "━━━━━━━━━━━━━━━\n"
                    . "Mohon konfirmasi pesanan saya. Terima kasih! 🙏";

                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $adminUser->id,
                    'message_text' => $templateMessage,
                    'is_read' => false,
                ]);
            }
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'order_code' => $orderCode, 'message' => 'Pesanan berhasil dibuat!']);
        }
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
        $orders = Order::with('items.product')->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    public function confirmReceived($id)
    {
        $order = Order::with('user')->findOrFail($id);
        
        // Ensure only the owner can confirm
        if (Auth::user()->id !== $order->user_id) {
            abort(403);
        }
        
        // Check if admin has requested confirmation
        if (!$order->confirmation_requested) {
            return back()->with('error', 'Konfirmasi penerimaan belum diizinkan oleh admin.');
        }

        $order->confirmed_received = true;
        $order->save();

        // Notify Admins
        $adminEmails = \App\Models\AdminEmail::pluck('email')->toArray();
        $admins = User::whereIn('email', $adminEmails)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pesanan Diterima oleh Customer',
                'message' => "Customer {$order->user->name} telah mengonfirmasi bahwa pesanan {$order->order_code} sudah diterima.",
                'related_url' => "/admin/orders?status=selesai",
            ]);
        }

        return back()->with('success', 'Pesanan Anda telah berhasil dikonfirmasi sebagai "Diterima". Terima kasih!');
    }
}
