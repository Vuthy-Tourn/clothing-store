<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Priority 1: Check URL parameter (from links)
        if ($request->has('lang') || $request->has('language')) {
            $locale = $request->query('lang') ?? $request->query('language');
            
            if (in_array($locale, ['en', 'km'])) {
                // Store in session
                Session::put('locale', $locale);
                Session::save();
                
                // Set application locale
                App::setLocale($locale);
                
                // Also set in config for this request
                config(['app.locale' => $locale]);
                
                return $next($request);
            }
        }
        
        // Priority 2: Check session for existing locale
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            
            if (in_array($locale, ['en', 'km'])) {
                // Set application locale
                App::setLocale($locale);
                
                // Also set in config for this request
                config(['app.locale' => $locale]);
                
                return $next($request);
            } else {
                // If invalid locale in session, remove it
                Session::forget('locale');
            }
        }
        
        // Priority 3: Default from config
        $defaultLocale = config('app.locale', 'en');
        
        // Store default in session
        Session::put('locale', $defaultLocale);
        Session::save();
        
        // Set application locale
        App::setLocale($defaultLocale);
        
        // Also set in config for this request
        config(['app.locale' => $defaultLocale]);
        
        return $next($request);
    }
}