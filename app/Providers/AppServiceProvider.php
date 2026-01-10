<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
    
        Route::aliasMiddleware('admin', AdminMiddleware::class);
          // Force locale specifically for the layout
    View::composer('layouts.front', function ($view) {
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['en', 'km'])) {
                app()->setLocale($locale);
                config(['app.locale' => $locale]);
            }
        }
    });
    
    // Also force it for all views to be safe
    View::composer('*', function ($view) {
        static $localeSet = false;
        
        if (!$localeSet && session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['en', 'km'])) {
                app()->setLocale($locale);
                config(['app.locale' => $locale]);
                $localeSet = true;
            }
        }
    });
    }
}
