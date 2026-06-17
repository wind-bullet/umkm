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

        if (!empty($q)) {
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'LIKE', "%{$q}%")
                   ->orWhere('description', 'LIKE', "%{$q}%");
            });
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

        $products = Product::where('is_active', true)
            ->where('name', 'LIKE', "%{$q}%")
            ->take(5)
            ->get(['id', 'name', 'price', 'image']);

        return response()->json($products);
    }
}
