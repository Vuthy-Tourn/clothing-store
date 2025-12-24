<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items.variant.product', 'shippingAddress', 'billingAddress'])
            ->firstOrFail();
        
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