<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Message;
use App\Models\User;
use App\Models\AdminEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        $categories = Category::where('is_active', true)->get();
        
        // Recommended products based on rating
        $recommendedProducts = Product::where('is_active', true)
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        // Active orders (not completed/selesai)
        $activeOrders = Order::where('user_id', $user->id)
            ->where('order_status', '!=', 'selesai')
            ->orderByDesc('created_at')
            ->get();

        // Get admin to chat
        $adminEmails = AdminEmail::pluck('email')->toArray();
        $admin = User::whereIn('email', $adminEmails)->first();

        // Count unread messages from admin
        $unreadMessagesCount = 0;
        if ($admin) {
            $unreadMessagesCount = Message::where('sender_id', $admin->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();
        }

        return view('user.dashboard', compact('categories', 'recommendedProducts', 'activeOrders', 'unreadMessagesCount', 'admin'));
    }

    public function profile()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.profile');
        }
        $recentOrders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('user.profile', compact('user', 'recentOrders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
