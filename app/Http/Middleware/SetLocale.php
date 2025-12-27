<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority 1: Session (from POST/AJAX requests)
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['en', 'km'])) {
                App::setLocale($locale);
                return $next($request);
            }
        }
        
        // Priority 2: Cookie (fallback)
        if ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            if (in_array($locale, ['en', 'km'])) {
                session(['locale' => $locale]);
                App::setLocale($locale);
                return $next($request);
            }
        }
        
        // Priority 3: Default
        App::setLocale('en');
        
        return $next($request);
    }
}