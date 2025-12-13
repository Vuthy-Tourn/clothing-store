<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch orders for the authenticated user with their items and addresses
        $orders = Order::with(['items.variant.product.images', 'shippingAddress', 'billingAddress'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show($orderNumber)
    {
        // Only fetch the order if it belongs to the logged-in user
        $order = Order::with(['items.variant.product.images', 'shippingAddress', 'billingAddress', 'user'])
            ->where('user_id', Auth::id())
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if this is a success redirect from Stripe
        if (request()->has('success')) {
            // Update order status if it's still pending
            if ($order->order_status === 'pending') {
                $order->update([
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                ]);

                // Deduct stock from variants
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->decrement('stock', $item->quantity);
                    }
                }

                // Clear cart
                CartItem::where('user_id', Auth::id())->delete();
                
                // Flash success message
                session()->flash('success', 'Payment successful! Your order has been confirmed.');
            }
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Download invoice for the specified order.
     */
    public function downloadInvoice($orderNumber)
    {
        $order = Order::with(['items.variant.product', 'shippingAddress', 'billingAddress', 'user'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        return $pdf->download('Invoice_' . $order->order_number . '.pdf');
    }

    /**
     * Cancel an order (if allowed).
     */
    public function cancel($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Only allow cancellation if order is pending or confirmed
        if (!in_array($order->order_status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'This order cannot be cancelled at this stage.');
        }

        // Update order status
        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => 'refunded', // Assuming refund for cancelled orders
        ]);

        // Restore stock to variants
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock', $item->quantity);
            }
        }

        return redirect()->route('orders.show', $orderNumber)
            ->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Track an order.
     */
    public function track($orderNumber)
    {
        $order = Order::with(['items.variant.product', 'shippingAddress'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.track', compact('order'));
    }

    /**
     * Reorder items from a previous order.
     */
    public function reorder($orderNumber)
    {
        $order = Order::with('items.variant')
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $addedCount = 0;

        foreach ($order->items as $item) {
            if ($item->variant) {
                // Check if variant is still available and in stock
                if ($item->variant->is_active && $item->variant->stock > 0) {
                    // Check if item already exists in cart
                    $cartItem = CartItem::where('user_id', Auth::id())
                        ->where('product_variant_id', $item->product_variant_id)
                        ->first();

                    if ($cartItem) {
                        // Update quantity, but don't exceed available stock
                        $maxQuantity = min($cartItem->quantity + $item->quantity, $item->variant->stock);
                        $cartItem->update(['quantity' => $maxQuantity]);
                    } else {
                        // Add new item to cart, but don't exceed available stock
                        $quantity = min($item->quantity, $item->variant->stock);
                        CartItem::create([
                            'user_id' => Auth::id(),
                            'product_variant_id' => $item->product_variant_id,
                            'quantity' => $quantity,
                        ]);
                    }
                    $addedCount++;
                }
            }
        }

        if ($addedCount > 0) {
            return redirect()->route('cart')
                ->with('success', $addedCount . ' item(s) have been added to your cart.');
        } else {
            return redirect()->route('orders.show', $orderNumber)
                ->with('error', 'Unable to add items to cart. They may be out of stock or no longer available.');
        }
    }

    /**
     * Show order history with filters.
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::with(['items.variant.product', 'shippingAddress'])
            ->where('user_id', $user->id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(15);

        // Calculate order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        $pendingOrders = Order::where('user_id', $user->id)
            ->whereIn('order_status', ['pending', 'confirmed', 'processing'])
            ->count();

        return view('orders.history', compact('orders', 'totalOrders', 'totalSpent', 'pendingOrders'));
    }

    /**
     * Get order status timeline.
     */
    public function statusTimeline($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $timeline = [];

        // Order placed
        $timeline[] = [
            'status' => 'Order Placed',
            'date' => $order->created_at,
            'description' => 'Your order has been placed successfully.',
            'icon' => 'fas fa-shopping-cart',
            'completed' => true,
        ];

        // Order confirmed
        if ($order->order_status !== 'pending') {
            $timeline[] = [
                'status' => 'Order Confirmed',
                'date' => $order->created_at,
                'description' => 'Your order has been confirmed and payment processed.',
                'icon' => 'fas fa-check-circle',
                'completed' => true,
            ];
        }

        // Processing
        if (in_array($order->order_status, ['processing', 'shipped', 'delivered'])) {
            $timeline[] = [
                'status' => 'Processing',
                'date' => $order->updated_at,
                'description' => 'Your order is being processed for shipping.',
                'icon' => 'fas fa-cog',
                'completed' => true,
            ];
        }

        // Shipped
        if (in_array($order->order_status, ['shipped', 'delivered'])) {
            $timeline[] = [
                'status' => 'Shipped',
                'date' => $order->updated_at,
                'description' => $order->tracking_number 
                    ? "Your order has been shipped. Tracking: {$order->tracking_number}"
                    : 'Your order has been shipped.',
                'icon' => 'fas fa-shipping-fast',
                'completed' => true,
            ];
        }

        // Delivered
        if ($order->order_status === 'delivered') {
            $timeline[] = [
                'status' => 'Delivered',
                'date' => $order->delivered_at,
                'description' => 'Your order has been delivered successfully.',
                'icon' => 'fas fa-home',
                'completed' => true,
            ];
        }

        // Current status
        $currentStatus = [
            'pending' => 'Order Placed',
            'confirmed' => 'Order Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
        ];

        return response()->json([
            'timeline' => $timeline,
            'current_status' => $currentStatus[$order->order_status] ?? $order->order_status,
            'estimated_delivery' => $order->estimated_delivery,
            'tracking_number' => $order->tracking_number,
        ]);
    }
}