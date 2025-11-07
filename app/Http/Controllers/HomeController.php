<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\NewArrival;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::where('is_active', true)->latest()->get();
        $arrivals = NewArrival::where('status', 'active')->latest()->take(6)->get();
        $categories = Category::where('status', 'active')->latest()->take(6)->get();
        $arrivals = Product::where('status', 'active')
        ->latest()
        ->take(8) // Show 8 newest products
        ->get();

        return view('frontend.index', compact('carousels', 'arrivals', 'categories', 'arrivals'));
    }
}
