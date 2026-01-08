<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display order history for logged-in user.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['items.variant.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show individual order details.
     */
      public function show($orderNumber)
{
    // $order = Order::with(['items.variant'])
    //     ->where('order_number', $orderNumber)
    //     ->firstOrFail();
    
    // // Only allow the order owner
    // if ($order->user_id !== Auth::id()) {
    //     abort(403);
    // }
    
    // // DEBUG: Show current state
    // $debug = [
    //     'order_status' => $order->order_status,
    //     'payment_status' => $order->payment_status,
    //     'payment_id' => $order->payment_id,
    //     'has_success_param' => request()->has('success'),
    //     'success_value' => request()->query('success'),
    // ];
    
    // // Log for debugging
    // Log::info('Order show method called', $debug);
    
    // // ALWAYS check Stripe payment status if order is pending
    // if ($order->order_status === 'pending') {
    //     $paymentConfirmed = false;
        
    //     // Method 1: Check if we just came from Stripe (look for session_id in URL)
    //     if (request()->has('session_id')) {
    //         $paymentConfirmed = true;
    //     }
        
    //     // Method 2: Check Stripe payment status directly
    //     if (!$paymentConfirmed && $order->payment_id) {
    //         try {
    //             \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //             $session = \Stripe\Checkout\Session::retrieve($order->payment_id);
                
    //             Log::info('Stripe session status', [
    //                 'session_id' => $session->id,
    //                 'payment_status' => $session->payment_status,
    //                 'status' => $session->status
    //             ]);
                
    //             if ($session->payment_status === 'paid') {
    //                 $paymentConfirmed = true;
    //             }
    //         } catch (\Exception $e) {
    //             Log::error('Stripe session check failed', [
    //                 'error' => $e->getMessage(),
    //                 'order_number' => $orderNumber
    //             ]);
    //         }
    //     }
        
    //     // Method 3: Check URL parameter (backward compatibility)
    //     if (!$paymentConfirmed && request()->has('success')) {
    //         $paymentConfirmed = true;
    //     }
        
    //     // If payment is confirmed, process the order
    //     if ($paymentConfirmed) {
    //         Log::info('Processing order payment', [
    //             'order_number' => $orderNumber,
    //             'reason' => 'payment_confirmed'
    //         ]);
            
    //         // Update order status
    //         $order->update([
    //             'order_status' => 'confirmed',
    //             'payment_status' => 'paid',
    //             'payment_date' => now(),
    //         ]);
            
    //         // Deduct stock from variants
    //         foreach ($order->items as $item) {
    //             $variant = \App\Models\ProductVariant::find($item->product_variant_id);
    //             if ($variant) {
    //                 $oldStock = $variant->stock;
    //                 $variant->decrement('stock', $item->quantity);
    //                 $variant->refresh();
                    
    //                 Log::info('Stock deducted', [
    //                     'variant_id' => $variant->id,
    //                     'sku' => $variant->sku,
    //                     'old_stock' => $oldStock,
    //                     'new_stock' => $variant->stock,
    //                     'quantity' => $item->quantity
    //                 ]);
    //             }
    //         }
            
    //         // Clear cart
    //         $deleted = CartItem::where('user_id', Auth::id())->delete();
    //         Log::info('Cart cleared', ['items_deleted' => $deleted]);
            
    //         session()->flash('success', 'Payment successful! Your order has been confirmed.');
            
    //         // Refresh the order to get updated status
    //         $order->refresh();
    //     }
    // }
    
    // $order->load(['items.variant.product', 'shippingAddress', 'billingAddress']);
    $order = Order::with(['items.variant.product', 'shippingAddress', 'billingAddress'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Only allow the order owner
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // If payment was successful, update order status and deduct stock
        if ($order->order_status === 'pending' && request()->has('success')) {
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
        }
    return view('orders.show', compact('order'));
}

    /**
     * Download invoice for an order.
     */
    public function downloadInvoice($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items.variant.product', 'shippingAddress', 'billingAddress'])
            ->firstOrFail();
        
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }
}