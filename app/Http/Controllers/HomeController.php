<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        
        // Products sorted by rating and review count as featured
        $featuredProducts = Product::where('is_active', true)
            ->orderByDesc('rating')
            ->orderByDesc('review_count')
            ->take(6)
            ->get();

        // Latest products
        $latestProducts = Product::where('is_active', true)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        return view('public.home', compact('categories', 'featuredProducts', 'latestProducts'));
    }
}
