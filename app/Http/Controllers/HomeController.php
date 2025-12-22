<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::where('is_active', true)->latest()->get();
        $categories = Category::where('status', 'active')->latest()->take(6)->get();
        
        // Get new arrivals from products table (using is_new flag)
        $arrivals = Product::with(['variants', 'images'])
            ->where('status', 'active')
            ->where('is_new', true)
            ->latest()
            ->take(6)
            ->get();
        
        // Get featured product of the day from products table (using is_featured flag)
        $featured = Product::with(['variants', 'images'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->first();

        return view('frontend.index', compact('carousels', 'arrivals', 'categories', 'featured'));
    }
}