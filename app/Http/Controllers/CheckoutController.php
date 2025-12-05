<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = CartItem::with(['product.sizes'])->where('user_id', Auth::id())->get();

        // Attach correct price to each item
        foreach ($items as $item) {
            $sizeObj = $item->product->sizes->firstWhere('size', $item->size);
            $item->unit_price = $sizeObj ? $sizeObj->price : 0;
        }

        $grandTotal = $items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        return view('frontend.checkout', compact('items', 'grandTotal'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'min:2', 'max:100'],
            'email'          => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^(?:\+855|0)(10|11|12|15|16|17|18|19|20|23|24|25|26|27|28|29)\d{6}$/'],
            'city'           => ['required', 'string', 'max:50'],
            'state'          => ['required', 'string', 'max:50'],
            'zip'            => ['required', 'regex:/^\d{5,6}$/'],
            'address'        => ['required', 'string', 'min:10', 'max:255'],
            'payment_method' => ['required', 'in:online'],
        ]);

        $userId = Auth::id();
        $items = CartItem::with('product.sizes')->where('user_id', $userId)->get();

        if ($items->isEmpty()) {
            return redirect()->route('products.all')->withErrors(['msg' => 'Your cart is empty.']);
        }

        // Calculate grand total
        $grandTotal = $items->sum(function ($item) {
            $sizePrice = $item->product->sizes->firstWhere('size', $item->size)?->price ?? 0;
            return $sizePrice * $item->quantity;
        });

        // Create order
        $orderId = 'ORDER_' . uniqid();
        $order = Order::create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'total_amount' => $grandTotal,
            'status' => 'pending',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'address' => $validated['address'],
        ]);

        // Save order items
        foreach ($items as $item) {
            $sizePrice = $item->product->sizes->firstWhere('size', $item->size)?->price ?? 0;

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product_id,
                'product_name' => $item->product->name,
                'size'         => $item->size,
                'quantity'     => $item->quantity,
                'price'        => $sizePrice,
            ]);
        }

        // Stripe integration
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Exchange rate for USD → KHR (adjust as needed)
    $exchangeRate = 4000; // 1 USD = 4000 KHR

        $lineItems = [];
        foreach ($items as $item) {
            $sizePriceUSD = $item->product->sizes->firstWhere('size', $item->size)?->price ?? 0; // USD
        $sizePriceKHR = $sizePriceUSD * $exchangeRate; // riel

        // Optional: store both for frontend display
        $item->price_usd = $sizePriceUSD;
        $item->price_riel = $sizePriceKHR;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd', // or 'KHR' if you convert
                    'product_data' => [
                        'name' => $item->product->name . ' (Size: ' . $item->size . ')',
                        'description' => 'Price: $' . number_format($sizePriceUSD, 2) . ' ≈ ' . number_format($sizePriceKHR) . ' KHR',
                    ],
                    'unit_amount' => $sizePrice * 100, // Stripe expects cents
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('orders.show', ['orderId' => $orderId]) . '?success=1',
            // 'cancel_url' => route('checkout.cancel'),
        ]);

        // Redirect to Stripe checkout
        return redirect($session->url);
    }

    public function thankYou($orderId)
    {
        $order = Order::with(['items.product'])->where('order_id', $orderId)->firstOrFail();

        // Only allow the order owner
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'paid') {
            $order->status = 'paid';
            $order->save();

            // Deduct stock
            foreach ($order->items as $item) {
                $productSize = $item->product->sizes()->where('size', $item->size)->first();
                if ($productSize) {
                    $productSize->stock = max(0, $productSize->stock - $item->quantity);
                    $productSize->save();
                }
            }

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();
        }

        return view('frontend.thankyou', compact('order'));
    }

    public function show($orderId)
{
    $order = Order::where('order_id', $orderId)->firstOrFail();
    
    // Check if this is a success redirect
    if (request()->has('success')) {
        session()->flash('show_order_success', true);
    }
    
    return view('orders.show', compact('order'));
}

    public function downloadInvoice($orderId)
    {
        $order = Order::with(['items.product'])->where('order_id', $orderId)->firstOrFail();
        $pdf = Pdf::loadView('frontend.invoice', compact('order'));
        return $pdf->download('invoice_' . $orderId . '.pdf');
    }
}
