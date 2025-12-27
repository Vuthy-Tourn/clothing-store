<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function set(Request $request)
{
    $request->validate([
        'locale' => 'required|in:en,km'
    ]);
    
    $locale = $request->locale;
    
    // Store in session
    session(['locale' => $locale]);
    session()->save();
    
    // Set cookie
    Cookie::queue('locale', $locale, 60 * 24 * 365);
    
    return redirect()->back();
}

public function ajax(Request $request)
{
    $request->validate([
        'locale' => 'required|in:en,km'
    ]);
    
    $locale = $request->locale;
    
    // Store in session
    session(['locale' => $locale]);
    session()->save();
    
    // Set cookie
    Cookie::queue('locale', $locale, 60 * 24 * 365);
    
    return response()->json([
        'success' => true,
        'locale' => $locale,
        'message' => 'Language changed successfully'
    ]);
}
}