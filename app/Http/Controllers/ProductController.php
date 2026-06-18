<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function category($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::where('is_active', true)->get();

        $query = Product::where('category_id', $category->id)->where('is_active', true);

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by Rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'rating_desc') {
            $query->orderByDesc('rating');
        } else {
            $query->orderByDesc('created_at');
        }

        $products = $query->paginate(8)->withQueryString();

        return view('public.category', compact('category', 'categories', 'products', 'sort'));
    }

    public function detail($id)
    {
        $product = Product::with(['category', 'reviews.user', 'voucherItems'])->findOrFail($id);
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('public.product-detail', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $categories = Category::where('is_active', true)->get();

        $query = Product::where('is_active', true);
        $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';

        if (!empty($q)) {
            $query->where(function($sub) use ($q, $like) {
                $sub->where('name', $like, "%{$q}%")
                   ->orWhere('description', $like, "%{$q}%");
            });
        }

        // Filter by Categories
        if ($request->filled('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by Rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'rating_desc') {
            $query->orderByDesc('rating');
        } else {
            $query->orderByDesc('created_at');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('public.search', compact('products', 'q', 'categories', 'sort'));
    }

    public function apiSearch(Request $request)
    {
        $q = $request->get('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';

        $products = Product::where('is_active', true)
            ->where('name', $like, "%{$q}%")
            ->take(5)
            ->get(['id', 'name', 'price', 'image']);

        return response()->json($products);
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();

        // Check if eligible (order has confirmation requested or received and contains the product)
        $eligible = \App\Models\Order::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('confirmation_requested', true)
                      ->orWhere('confirmed_received', true);
            })
            ->whereHas('items', function($q) use ($id) {
                $q->where('product_id', $id);
            })
            ->exists();

        if (!$eligible) {
            return back()->with('error', 'Anda belum diizinkan memberikan ulasan untuk produk ini.');
        }

        // Create review
        \App\Models\Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        // Recalculate average rating and count
        $avgRating = \App\Models\Review::where('product_id', $product->id)->avg('rating') ?: 0;
        $reviewCount = \App\Models\Review::where('product_id', $product->id)->count();

        $product->update([
            'rating' => $avgRating,
            'review_count' => $reviewCount,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
