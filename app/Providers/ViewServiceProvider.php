<?php
// app/Providers/ViewServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share categories with all views
        View::composer('*', function ($view) {
            $categories = Category::where('status', 'active')
                ->orderBy('sort_order', 'asc')
                ->get();
            
            $view->with('categories', $categories);
        });
    }
}
?>