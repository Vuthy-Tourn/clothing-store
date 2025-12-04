<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard.index');
    }

    public function sidebarStats()
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $outOfStock = Product::where('stock', 0)->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        return response()->json([
            'pending_orders' => $pendingOrders,
            'out_of_stock' => $outOfStock,
            'today_revenue' => $todayRevenue
        ]);
    }

    public function dashboardStats()
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount') ?? 0;
        $activeOrders = Order::whereIn('status', ['pending', 'processing', 'shipped'])->count() ?? 0;
        $ordersToday = Order::whereDate('created_at', today())->count() ?? 0;
        $totalProducts = Product::count() ?? 0;
        
        $lowStock = DB::table('product_sizes')
            ->where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->distinct('product_id')
            ->count('product_id') ?? 0;
        
        $totalCustomers = User::count() ?? 0;
        $newCustomers = User::where('created_at', '>=', now()->subWeek())->count() ?? 0;
        
        $currentMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount') ?? 0;
            
        $lastMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount') ?? 0;
            
        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2);
        } elseif ($currentMonthRevenue > 0) {
            $revenueGrowth = 100;
        }
        
        $totalStock = DB::table('product_sizes')->sum('stock') ?? 0;
        $maxStock = DB::table('product_sizes')->count() * 100;
        $inventoryPercent = ($maxStock > 0) ? min(round(($totalStock / $maxStock) * 100), 100) : 0;
        
        $pendingShipments = Order::where('status', 'shipped')->count() ?? 0;

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
            'pending_shipments' => (int) $pendingShipments
        ]);
    }
public function recentActivity()
{
    try {
        // Get orders from the last 2 months
        $twoMonthsAgo = now()->subMonths(2);
        
        $recentOrders = Order::with(['user:id,name,email'])
            ->select('id', 'order_id', 'total_amount', 'status', 'created_at', 'user_id')
            ->where('created_at', '>=', $twoMonthsAgo)
            ->latest()
            ->take(10)
            ->get();
        
        // Get recent customers - removed role check since column doesn't exist
        $recentCustomers = User::select('id', 'name', 'email', 'created_at')
            ->where('created_at', '>=', $twoMonthsAgo)
            ->latest()
            ->take(10)
            ->get();
        
        return response()->json([
            'recent_orders' => $recentOrders,
            'recent_customers' => $recentCustomers
        ]);

    } catch (\Exception $e) {
        
        return response()->json([
            'error' => 'Failed to fetch recent activity',
            'message' => $e->getMessage(),
            'recent_orders' => [],
            'recent_customers' => []
        ], 200);
    }
}

    public function topProducts()
    {
        try {
            $topProducts = DB::table('products')
                ->select('products.id', 'products.name', 'products.image')
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                ->selectRaw('COALESCE(SUM(CASE WHEN orders.status = "completed" THEN order_items.quantity ELSE 0 END), 0) as total_sold')
                ->groupBy('products.id', 'products.name', 'products.image')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();
            
            if ($topProducts->isEmpty() || $topProducts->sum('total_sold') == 0) {
                $topProducts = Product::select('id', 'name', 'image')
                    ->selectRaw('0 as total_sold')
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
            \Log::error('Top Products Error: ' . $e->getMessage());
            
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
            $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
            $distribution = [];
            
            foreach ($statuses as $status) {
                $distribution[] = Order::where('status', $status)->count() ?? 0;
            }

            return response()->json([
                'distribution' => $distribution,
                'labels' => $statuses
            ]);

        } catch (\Exception $e) {
            \Log::error('Order Status Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch order distribution',
                'message' => $e->getMessage(),
                'distribution' => [0, 0, 0, 0, 0],
                'labels' => ['pending', 'processing', 'shipped', 'completed', 'cancelled']
            ], 200);
        }
    }

    public function performanceMetrics()
    {
        try {
            $completedOrders = Order::where('status', 'completed');
            $totalRevenue = $completedOrders->sum('total_amount') ?? 0;
            $orderCount = $completedOrders->count() ?? 0;
            $avgOrderValue = $orderCount > 0 ? round($totalRevenue / $orderCount, 2) : 0;

            $totalVisitors = 1000;
            $totalOrders = Order::count() ?? 0;
            $conversionRate = $totalVisitors > 0 
                ? round(($totalOrders / $totalVisitors) * 100, 2) 
                : 0;

            $fulfilledOrders = Order::whereIn('status', ['completed', 'shipped'])->count() ?? 0;
            $fulfillmentRate = $totalOrders > 0 
                ? round(($fulfilledOrders / $totalOrders) * 100, 2) 
                : 0;

            $satisfactionRate = 85;

            return response()->json(data: [
                'avg_order_value' => (float) $avgOrderValue,
                'max_order_value' => 10000,
                'conversion_rate' => (float) $conversionRate,
                'fulfillment_rate' => (float) $fulfillmentRate,
                'satisfaction_rate' => (int) $satisfactionRate
            ]);

        } catch (\Exception $e) {
            \Log::error('Performance Metrics Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch performance metrics',
                'message' => $e->getMessage(),
                'avg_order_value' => 0,
                'max_order_value' => 10000,
                'conversion_rate' => 0,
                'fulfillment_rate' => 0,
                'satisfaction_rate' => 0
            ], 200);
        }
    }

   public function salesChart(Request $request)
{
    $period = $request->get('period', 'week');
    
    try {
        $data = [];
        $labels = [];
        
        if ($period === 'week') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayName = $date->format('D');
                
                $sales = Order::whereIn('status', ['completed', 'paid'])
                    ->whereDate('created_at', $date->toDateString())
                    ->sum('total_amount') ?? 0;
                
                $labels[] = $dayName;
                $data[] = (float) $sales;
            }
            
        } elseif ($period === 'month') {
            // Last 4 weeks
            for ($i = 3; $i >= 0; $i--) {
                $startDate = now()->subWeeks($i + 1)->startOfWeek();
                $endDate = now()->subWeeks($i)->endOfWeek();
                
                $sales = Order::whereIn('status', ['completed', 'paid'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_amount') ?? 0;
                
                $labels[] = 'Week ' . (4 - $i);
                $data[] = (float) $sales;
            }
            
        } else { // year
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthName = $month->format('M');
                
                $sales = Order::whereIn('status', ['completed', 'paid'])
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total_amount') ?? 0;
                
                $labels[] = $monthName;
                $data[] = (float) $sales;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'period' => $period
        ]);

    } catch (\Exception $e) {
        \Log::error('Sales Chart Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        $labels = $period === 'week' 
            ? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            : ($period === 'month' ? ['Week 1', 'Week 2', 'Week 3', 'Week 4'] : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        
        return response()->json([
            
        ], 200);
    }
}
}