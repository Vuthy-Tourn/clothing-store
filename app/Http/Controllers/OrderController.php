<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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