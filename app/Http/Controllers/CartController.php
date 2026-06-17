<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        session()->forget('buy_now');
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->qty * $item->product->price;
        }

        return view('public.cart', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk menambah barang ke keranjang.');
        }

        $product = Product::findOrFail($request->product_id);
        if ($product->stock < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi. Stok saat ini: ' . $product->stock);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->qty + $request->input('qty', 1);
            if ($product->stock < $newQty) {
                return back()->with('error', 'Stok tidak mencukupi untuk jumlah ini.');
            }
            $cartItem->update([
                'qty' => $newQty,
                'price_snapshot' => $product->price,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'qty' => $request->input('qty', 1),
                'price_snapshot' => $product->price,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
            'qty' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::with('product')->findOrFail($request->id);
        
        if ($cartItem->product->stock < $request->qty) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok saat ini: ' . $cartItem->product->stock
            ], 400);
        }

        $cartItem->update(['qty' => $request->qty]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->qty * $item->product->price;
        }

        return response()->json([
            'success' => true,
            'item_total' => number_format($cartItem->qty * $cartItem->product->price, 0, ',', '.'),
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'total' => number_format($subtotal, 0, ',', '.'), // shipping calculated on checkout
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->id);
        $cartItem->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
