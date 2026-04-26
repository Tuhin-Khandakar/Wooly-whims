<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->take(6)->get();
        $featured_products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featured_products'));
    }
}
