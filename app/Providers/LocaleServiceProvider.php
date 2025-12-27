<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // This runs AFTER all middleware
        $this->app->booted(function () {
            $this->setLocale();
        });
        
        // Also set it early for good measure
        $this->setLocale();
    }
    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Set the application locale.
     */
    private function setLocale(): void
    {
        // 1. Check URL parameter (highest priority)
        if (request()->has('lang')) {
            $locale = request('lang');
            if (in_array($locale, ['en', 'km'])) {
                App::setLocale($locale);
                Session::put('locale', $locale);
                Session::save();
                return;
            }
        }
        
        // 2. Check session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if (in_array($locale, ['en', 'km'])) {
                App::setLocale($locale);
                return;
            }
        }
        
        // 3. Default
        App::setLocale('en');
    }
}