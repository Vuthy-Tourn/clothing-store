<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }


   public function dashboardStats()
{
    // Total revenue from completed orders
    $totalRevenue = Order::where('order_status', 'confirmed')
        ->where('payment_status', 'paid')
        ->sum('total_amount') ?? 0;

    // Active orders (confirmed, processing, shipped)
    $activeOrders = Order::whereIn('order_status', ['confirmed', 'processing', 'shipped'])
        ->count() ?? 0;

    // Orders today
    $ordersToday = Order::whereDate('created_at', today())
        ->count() ?? 0;

    // Total products
    $totalProducts = Product::count() ?? 0;

    // Low stock products (product variants with stock < 10 and > 0)
    $lowStock = DB::table('product_variants')
        ->where('stock', '<', 10)
        ->where('stock', '>', 0)
        ->distinct('product_id')
        ->count('product_id') ?? 0;

    // Total customers (users with account_type = 'customer')
    $totalCustomers = User::where('account_type', 'customer')->count() ?? 0;

    // New customers (from last week)
    $newCustomers = User::where('account_type', 'customer')
        ->where('created_at', '>=', now()->subWeek())
        ->count() ?? 0;

    // Current month revenue
    $currentMonthRevenue = Order::where('order_status', 'delivered')
        ->where('payment_status', 'paid')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_amount') ?? 0;

    // Last month revenue
    $lastMonthRevenue = Order::where('order_status', 'delivered')
        ->where('payment_status', 'paid')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->sum('total_amount') ?? 0;

    // Revenue growth calculation
    $revenueGrowth = 0;
    if ($lastMonthRevenue > 0) {
        $revenueGrowth = round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2);
    } elseif ($currentMonthRevenue > 0) {
        $revenueGrowth = 100;
    }

    // Inventory percentage
    $totalStock = DB::table('product_variants')->sum('stock') ?? 0;
    $maxStock = DB::table('product_variants')->count() * 100; // Assuming max 100 per variant
    $inventoryPercent = ($maxStock > 0) ? min(round(($totalStock / $maxStock) * 100), 100) : 0;

    // Pending shipments (orders that are shipped but not delivered)
    $pendingShipments = Order::where('order_status', 'shipped')
        ->where('delivered_at', null)
        ->count() ?? 0;

    // Additional useful stats for your dashboard:
    
    // Total pending orders (including pending payment)
    $pendingOrders = Order::whereIn('order_status', ['pending', 'confirmed'])
        ->orWhere('payment_status', 'pending')
        ->count() ?? 0;

    // Out of stock variants
    $outOfStock = DB::table('product_variants')
        ->where('stock', '=', 0)
        ->count() ?? 0;

    // Active users (logged in within last 30 days)
    $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
        ->count() ?? 0;

    // Total wishlist items
    $totalWishlistItems = DB::table('wishlists')->count() ?? 0;

    // Average order value
    $averageOrderValue = 0;
    $totalOrders = Order::count() ?? 0;
    if ($totalOrders > 0) {
        $averageOrderValue = round($totalRevenue / $totalOrders, 2);
    }

    return response()->json([
        'total_revenue' => (float) $totalRevenue,
        'revenue_growth' => (float) $revenueGrowth,
        'active_orders' => (int) $activeOrders,
        'orders_today' => (int) $ordersToday,
        'total_products' => (int) $totalProducts,
        'low_stock' => (int) $lowStock,
        'total_customers' => (int) $totalCustomers,
        'new_customers' => (int) $newCustomers,
        'inventory_percent' => (int) $inventoryPercent,
        'pending_shipments' => (int) $pendingShipments,
        // Additional stats for more comprehensive dashboard
        'pending_orders' => (int) $pendingOrders,
        'out_of_stock' => (int) $outOfStock,
        'active_users' => (int) $activeUsers,
        'total_wishlist_items' => (int) $totalWishlistItems,
        'average_order_value' => (float) $averageOrderValue,
        'current_month_revenue' => (float) $currentMonthRevenue,
        'last_month_revenue' => (float) $lastMonthRevenue
    ]);
}

   public function sidebarStats()
{
    // Pending orders (pending or confirmed with pending payment)
    $pendingOrders = Order::where(function($query) {
            $query->where('order_status', 'pending')
                  ->orWhere('order_status', 'confirmed')
                  ->orWhere('payment_status', 'pending');
        })
        ->count() ?? 0;

    // Out of stock variants
    $outOfStock = DB::table('product_variants')
        ->where('stock', 0)
        ->count() ?? 0;

    // Today's revenue (delivered & paid)
    $todayRevenue = Order::whereDate('created_at', today())
        ->where('order_status', 'delivered')
        ->where('payment_status', 'paid')
        ->sum('total_amount') ?? 0;

    return response()->json([
        'pending_orders' => (int) $pendingOrders,
        'out_of_stock' => (int) $outOfStock,
        'today_revenue' => (float) $todayRevenue
    ]);
}

public function recentActivity()
{
    try {
        $twoMonthsAgo = now()->subMonths(2);

        $recentOrders = Order::with('user:id,name,email')
            ->select('id', 'order_number', 'total_amount', 'order_status', 'payment_status', 'created_at', 'user_id')
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $twoMonthsAgo)
            ->latest()
            ->limit(3)
            ->get();

        $recentCustomers = User::select('id', 'name', 'email', 'created_at')
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $twoMonthsAgo)
            ->latest()
            ->limit(3)
            ->get();

        return response()->json([
            'recent_orders' => $recentOrders,
            'recent_customers' => $recentCustomers
        ]);

    } catch (\Exception $e) {
        Log::error('Recent Activity Error', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'recent_orders' => [],
            'recent_customers' => []
        ], 200);
    }
}


public function topProducts()
{
    try {
        // Join with order_items and product_variants to get accurate product sales
        $topProducts = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                DB::raw('(SELECT image_path FROM product_images WHERE product_id = products.id AND is_primary = 1 LIMIT 1) as image'),
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            )
            ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->where('orders.order_status', 'delivered')
                     ->where('orders.payment_status', 'paid');
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
        
        // If no sales data, get 5 random products
        if ($topProducts->isEmpty() || $topProducts->sum('total_sold') == 0) {
            $topProducts = Product::select('products.id', 'products.name')
                ->selectRaw('(SELECT image_path FROM product_images WHERE product_id = products.id AND is_primary = 1 LIMIT 1) as image')
                ->selectRaw('0 as total_sold')
                ->inRandomOrder()
                ->take(5)
                ->get();
        }
        
        $topProducts = $topProducts->map(function($product) {
            $product->total_sold = (int) $product->total_sold;
            return $product;
        });
        
        return response()->json([
            'top_products' => $topProducts
        ]);

    } catch (\Exception $e) {
        Log::error('Top Products Error: ' . $e->getMessage());
        
        return response()->json([
            'error' => 'Failed to fetch top products',
            'message' => $e->getMessage(),
            'top_products' => []
        ], 200);
    }
}

  public function orderStatusDistribution()
{
    try {
        // Your order statuses from the database
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        $distribution = [];
        
        foreach ($statuses as $status) {
            $distribution[] = Order::where('order_status', $status)->count() ?? 0;
        }

        // Also get payment status distribution
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $paymentDistribution = [];
        
        foreach ($paymentStatuses as $status) {
            $paymentDistribution[] = Order::where('payment_status', $status)->count() ?? 0;
        }

        return response()->json([
            'order_status_distribution' => $distribution,
            'order_status_labels' => $statuses,
            'payment_status_distribution' => $paymentDistribution,
            'payment_status_labels' => $paymentStatuses
        ]);

    } catch (\Exception $e) {
        Log::error('Order Status Error: ' . $e->getMessage());
        
        $statuses = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Refunded'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        
        return response()->json([
            'error' => 'Failed to fetch order distribution',
            'message' => $e->getMessage(),
            'order_status_distribution' => array_fill(0, count($statuses), 0),
            'order_status_labels' => $statuses,
            'payment_status_distribution' => array_fill(0, count($paymentStatuses), 0),
            'payment_status_labels' => $paymentStatuses
        ], 200);
    }
}

   public function performanceMetrics()
{
    try {
        // Completed and paid orders only
        $completedOrders = Order::where('order_status', 'delivered')
            ->where('payment_status', 'paid');
        
        $totalRevenue = $completedOrders->sum('total_amount') ?? 0;
        $orderCount = $completedOrders->count() ?? 0;
        $avgOrderValue = $orderCount > 0 ? round($totalRevenue / $orderCount, 2) : 0;

        // Total visitors (you might want to track this differently)
        $totalVisitors = 1000; // Placeholder - implement actual visitor tracking
        $totalOrders = Order::count() ?? 0;
        $conversionRate = $totalVisitors > 0 
            ? round(($totalOrders / $totalVisitors) * 100, 2) 
            : 0;

        // Fulfillment rate (orders that have been shipped or delivered)
        $fulfilledOrders = Order::whereIn('order_status', ['shipped', 'delivered'])
            ->count() ?? 0;
        $fulfillmentRate = $totalOrders > 0 
            ? round(($fulfilledOrders / $totalOrders) * 100, 2) 
            : 0;

        // Satisfaction rate from reviews (average rating as percentage)
        $averageRating = DB::table('product_reviews')
            ->where('is_approved', 1)
            ->avg('rating') ?? 0;
        $satisfactionRate = $averageRating > 0 
            ? round(($averageRating / 5) * 100, 2) 
            : 85; // Default fallback

        // New metric: Return/Refund rate
        $refundedOrders = Order::where('order_status', 'refunded')->count() ?? 0;
        $refundRate = $totalOrders > 0 
            ? round(($refundedOrders / $totalOrders) * 100, 2) 
            : 0;

        return response()->json([
            'avg_order_value' => (float) $avgOrderValue,
            'max_order_value' => 10000,
            'conversion_rate' => (float) $conversionRate,
            'fulfillment_rate' => (float) $fulfillmentRate,
            'satisfaction_rate' => (float) $satisfactionRate,
            'refund_rate' => (float) $refundRate
        ]);

    } catch (\Exception $e) {
        Log::error('Performance Metrics Error: ' . $e->getMessage());
        
        return response()->json([
            'error' => 'Failed to fetch performance metrics',
            'message' => $e->getMessage(),
            'avg_order_value' => 0,
            'max_order_value' => 10000,
            'conversion_rate' => 0,
            'fulfillment_rate' => 0,
            'satisfaction_rate' => 85,
            'refund_rate' => 0
        ], 200);
    }
}

public function salesChart(Request $request)
{
    $period = $request->get('period', 'week');
    
    try {
        $data = [];
        $labels = [];
        $debugInfo = []; // For debugging

        if ($period === 'week') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayName = $date->format('D');
                
                // Check if there are ANY orders on this date
                $totalOrders = Order::whereDate('created_at', $date->toDateString())
                    ->count();
                
                $sales = Order::where('order_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereDate('created_at', $date->toDateString())
                    ->sum('total_amount') ?? 0;
                
                $labels[] = $dayName;
                $data[] = (float) $sales;
                
                // Debug info
                $debugInfo[] = [
                    'date' => $date->toDateString(),
                    'day' => $dayName,
                    'total_orders' => $totalOrders,
                    'sales' => $sales
                ];
            }
            
        } elseif ($period === 'month') {
            // Last 4 weeks
            for ($i = 3; $i >= 0; $i--) {
                $startDate = now()->subWeeks($i + 1)->startOfWeek();
                $endDate = now()->subWeeks($i)->endOfWeek();
                
                $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                
                $sales = Order::where('order_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_amount') ?? 0;
                
                $weekNum = 4 - $i;
                $labels[] = 'Week ' . $weekNum;
                $data[] = (float) $sales;
                
                $debugInfo[] = [
                    'period' => $startDate->format('M d') . ' - ' . $endDate->format('M d'),
                    'week' => 'Week ' . $weekNum,
                    'total_orders' => $totalOrders,
                    'sales' => $sales
                ];
            }
            
        } else { // year
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthName = $month->format('M');
                
                $totalOrders = Order::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count();
                
                $sales = Order::where('order_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total_amount') ?? 0;
                
                $labels[] = $monthName;
                $data[] = (float) $sales;
                
                $debugInfo[] = [
                    'month' => $monthName,
                    'total_orders' => $totalOrders,
                    'sales' => $sales
                ];
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'period' => $period,
            'total_sales' => array_sum($data),
            'average_sales' => count($data) > 0 ? round(array_sum($data) / count($data), 2) : 0,
            'debug' => $debugInfo, // Add debug info
            'has_data' => array_sum($data) > 0
        ]);

    } catch (\Exception $e) {
        Log::error('Sales Chart Error: ' . $e->getMessage());
        
        // Return empty data structure
        if ($period === 'week') {
            $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $data = array_fill(0, 7, 0);
        } elseif ($period === 'month') {
            $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            $data = array_fill(0, 4, 0);
        } else {
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $data = array_fill(0, 12, 0);
        }
        
        return response()->json([
            'error' => 'Failed to fetch sales chart data',
            'message' => $e->getMessage(),
            'labels' => $labels,
            'data' => $data,
            'period' => $period,
            'total_sales' => 0,
            'average_sales' => 0,
            'has_data' => false
        ], 200);
    }
}
}