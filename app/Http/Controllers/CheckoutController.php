<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant; // Make sure to import this
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log; // Add Log facade
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = CartItem::with(['variant.product.images'])
            ->where('user_id', Auth::id())
            ->get();

        // Calculate totals - USE SAME LOGIC AS CART CONTROLLER
        $subtotal = 0;
        $totalSavings = 0;
        $originalSubtotal = 0;
        
        foreach ($items as $item) {
            if ($item->variant) {
                // Use final_price which already includes discounts - SAME AS CART CONTROLLER
                $item->unit_price = $item->variant->final_price;
                $item->total_price = $item->unit_price * $item->quantity;
                $subtotal += $item->total_price;
                
                // Store original price for comparison
                $item->original_price = $item->variant->price;
                
                // Check if item has discount - Compare final_price with original price
                $item->has_discount = $item->variant->final_price < $item->variant->price;
                
                // Calculate savings for this item
                if ($item->has_discount) {
                    $itemSavings = ($item->original_price - $item->unit_price) * $item->quantity;
                    $totalSavings += $itemSavings;
                }
                
                // Original subtotal (without discounts)
                $originalSubtotal += $item->original_price * $item->quantity;
                
                // Add product info for display
                $item->product_name = $item->variant->product->name ?? 'Product';
                $item->product_image = $item->variant->product->images->first()->image_path ?? 'products/placeholder.jpg';
                $item->size = $item->variant->size;
                $item->color = $item->variant->color;
                $item->sku = $item->variant->sku;
                $item->discount_percentage = $item->variant->discount_percentage;
            }
        }

        $shipping = 0; // Free shipping
        $tax = $subtotal * 0.08; // 8% tax
        $grandTotal = $subtotal + $shipping + $tax;

        // Get user's saved addresses
        $user = Auth::user();
        $savedAddresses = $user->addresses()
            ->shipping()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($address) {
                return $address->full_name . '|' . 
                       $address->phone . '|' . 
                       $address->address_line1 . '|' . 
                       $address->city . '|' . 
                       $address->state . '|' . 
                       $address->zip_code . '|' . 
                       $address->country;
            })
            ->values();
        $defaultAddress = $user->addresses()->shipping()->where('is_default', true)->first();
        
        return view('frontend.checkout', compact('items', 'subtotal', 'shipping', 'tax', 'grandTotal', 'savedAddresses', 'defaultAddress', 'user', 'totalSavings', 'originalSubtotal'));
    }

    public function process(Request $request)
    {
        $userId = Auth::id();
        $shippingAddressId = null;
        $billingAddressId = null;
        $customerEmail = null;

        // Validate based on address selection
        if ($request->address_option === 'new') {
            $validated = $request->validate([
                'name'           => ['required', 'string', 'min:2', 'max:100'],
                'email'          => ['required', 'email', 'max:255'],
                'phone'          => ['required', 'regex:/^(?:\+855|0)(10|11|12|15|16|17|18|19|20|23|24|25|26|27|28|29)\d{6}$/'],
                'city'           => ['required', 'string', 'max:50'],
                'state'          => ['required', 'string', 'max:50'],
                'zip'            => ['required', 'regex:/^\d{5,6}$/'],
                'address'        => ['required', 'string', 'min:10', 'max:255'],
                'address2'       => ['nullable', 'string', 'max:255'],
                'payment_method' => ['required', 'in:online'],
            ]);

            // Save shipping address
            $shippingAddress = UserAddress::create([
                'user_id' => $userId,
                'type' => 'shipping',
                'address_name' => $request->address_name ?? 'Home',
                'full_name' => $validated['name'],
                'phone' => $validated['phone'],
                'address_line1' => $validated['address'],
                'address_line2' => $validated['address2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip'],
                'country' => $request->country ?? 'United States',
                'is_default' => $request->make_default ?? false,
            ]);
            $shippingAddressId = $shippingAddress->id;

            // Check if separate billing address is needed
            if ($request->has('different_billing') && $request->different_billing === '1') {
                $billingAddress = UserAddress::create([
                    'user_id' => $userId,
                    'type' => 'billing',
                    'address_name' => $request->billing_address_name ?? 'Billing',
                    'full_name' => $request->billing_name ?? $validated['name'],
                    'phone' => $request->billing_phone ?? $validated['phone'],
                    'address_line1' => $request->billing_address ?? $validated['address'],
                    'address_line2' => $request->billing_address2 ?? $validated['address2'] ?? null,
                    'city' => $request->billing_city ?? $validated['city'],
                    'state' => $request->billing_state ?? $validated['state'],
                    'zip_code' => $request->billing_zip ?? $validated['zip'],
                    'country' => $request->billing_country ?? $request->country ?? 'United States',
                    'is_default' => false,
                ]);
                $billingAddressId = $billingAddress->id;
            } else {
                $billingAddressId = $shippingAddressId;
            }

            $customerEmail = $validated['email'];

        } else {
            // Using saved address
            $validated = $request->validate([
                'saved_address_id' => ['required', 'exists:user_addresses,id,user_id,' . $userId],
                'payment_method' => ['required', 'in:online'],
            ]);

            $savedAddress = UserAddress::where('id', $validated['saved_address_id'])
                ->where('user_id', $userId)
                ->firstOrFail();

            $shippingAddressId = $savedAddress->id;
            $billingAddressId = $shippingAddressId;
            $customerEmail = Auth::user()->email;
        }

        // Get cart items
        $items = CartItem::with(['variant.product'])
            ->where('user_id', $userId)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->withErrors(['msg' => 'Your cart is empty.']);
        }

        // Check stock availability BEFORE creating order
        foreach ($items as $item) {
            if ($item->variant && $item->variant->stock < $item->quantity) {
                return redirect()->route('cart')->withErrors([
                    'msg' => 'Insufficient stock for ' . $item->variant->product->name . 
                            ' (Size: ' . $item->variant->size . ', Color: ' . $item->variant->color . ').' .
                            ' Only ' . $item->variant->stock . ' items available.'
                ]);
            }
        }

        // Calculate grand total using final_price - SAME LOGIC AS CART
        $subtotal = 0;
        $totalSavings = 0;
        foreach ($items as $item) {
            if ($item->variant) {
                // Use final_price which already includes discounts
                $price = $item->variant->final_price;
                $originalPrice = $item->variant->price;
                $subtotal += $price * $item->quantity;
                $totalSavings += ($originalPrice - $price) * $item->quantity;
            }
        }
        
        $shipping = 0; // Free shipping
        $tax = $subtotal * 0.08;
        $grandTotal = $subtotal + $shipping + $tax;

        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        // Create order
        $order = Order::create([
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'order_status' => 'pending',
            'subtotal' => $subtotal,
            'shipping_amount' => $shipping,
            'tax_amount' => $tax,
            'total_amount' => $grandTotal,
            'payment_method' => 'stripe',
            'payment_status' => 'pending',
            'shipping_address_id' => $shippingAddressId,
            'billing_address_id' => $billingAddressId,
            'customer_notes' => $request->customer_notes ?? null,
        ]);

        // Save order items with variant info
        foreach ($items as $item) {
            if ($item->variant) {
                $price = $item->variant->final_price;
                $totalPrice = $price * $item->quantity;
                $originalPrice = $item->variant->price;
                
                // Store variant details as JSON
                $variantDetails = [
                    'size' => $item->variant->size,
                    'color' => $item->variant->color,
                    'sku' => $item->variant->sku,
                    'original_price' => $originalPrice,
                    'discounted_price' => $price,
                    'savings' => ($originalPrice - $price) * $item->quantity,
                    'has_discount' => $price < $originalPrice,
                    'discount_percentage' => $item->variant->discount_percentage,
                ];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->variant->id,
                    'product_name' => $item->variant->product->name ?? 'Product',
                    'variant_details' => json_encode($variantDetails),
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'total_price' => $totalPrice,
                ]);
            }
        }

        // Stripe integration
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Exchange rate for USD → KHR
        $exchangeRate = 4000;

        $lineItems = [];
        foreach ($items as $item) {
            if ($item->variant) {
                $price = $item->variant->final_price;
                $originalPrice = $item->variant->price;
                $priceKHR = $price * $exchangeRate;
                
                // Create description with discount info if applicable
                $description = 'SKU: ' . $item->variant->sku;
                if ($price < $originalPrice) {
                    $savings = $originalPrice - $price;
                    $discountPercentage = $item->variant->discount_percentage;
                    $description .= ' | SAVE $' . number_format($savings, 2);
                    if ($discountPercentage > 0) {
                        $description .= ' (' . $discountPercentage . '% OFF)';
                    }
                }
                $description .= ' | $' . number_format($price, 2) . 
                               ' ≈ ' . number_format($priceKHR) . ' KHR';
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->variant->product->name . 
                                     ' (Size: ' . $item->variant->size . 
                                     ', Color: ' . $item->variant->color . ')',
                            'description' => $description,
                        ],
                        'unit_amount' => round($price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }
        }

        // Get shipping info for metadata
        $shippingInfo = [];
        if ($request->address_option === 'new') {
            $shippingInfo = [
                'shipping_name' => $validated['name'] ?? '',
                'shipping_city' => $validated['city'] ?? '',
                'shipping_country' => $request->country ?? 'United States',
            ];
        } else {
            $shippingAddress = UserAddress::find($shippingAddressId);
            if ($shippingAddress) {
                $shippingInfo = [
                    'shipping_name' => $shippingAddress->full_name ?? '',
                    'shipping_city' => $shippingAddress->city ?? '',
                    'shipping_country' => $shippingAddress->country ?? 'United States',
                ];
            }
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('orders.show', ['orderNumber' => $orderNumber]) . '?success=1',            'cancel_url' => route('cart') . '?canceled=1',
            'metadata' => array_merge([
                'order_number' => $orderNumber,
                'user_id' => $userId,
            ], $shippingInfo),
            'customer_email' => $customerEmail,
        ]);

        // Save Stripe session ID to order
        $order->update([
            'payment_id' => $session->id,
        ]);

        // Redirect to Stripe checkout
        return redirect($session->url);
    }


    public function downloadInvoice($orderNumber)
    {
        $order = Order::with(['items.variant.product', 'shippingAddress', 'billingAddress'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
            
        $pdf = Pdf::loadView('frontend.invoice', compact('order'));
        return $pdf->download('invoice_' . $orderNumber . '.pdf');
    }

    // Webhook endpoint for Stripe
    public function stripeWebhook(Request $request)
    {
        Log::info('=== STRIPE WEBHOOK RECEIVED ===');
        
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
            Log::info('Webhook event constructed:', ['type' => $event->type]);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                Log::info('Stripe webhook: checkout.session.completed', [
                    'session_id' => $session->id,
                    'payment_status' => $session->payment_status,
                    'metadata' => $session->metadata
                ]);
                
                if ($session->payment_status === 'paid') {
                    // Update order status
                    $order = Order::where('payment_id', $session->id)->first();
                    if ($order) {
                        Log::info('Found order for webhook:', [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'current_status' => $order->order_status
                        ]);
                        
                        if ($order->order_status === 'pending') {
                            // Update order status
                            $order->update([
                                'order_status' => 'confirmed',
                                'payment_status' => 'paid',
                                'payment_date' => now(),
                            ]);
                            
                            Log::info('Order status updated via webhook');
                            
                            // Deduct stock
                            foreach ($order->items as $item) {
                                $variant = ProductVariant::find($item->product_variant_id);
                                if ($variant) {
                                    Log::info('Webhook stock deduction:', [
                                        'variant_id' => $variant->id,
                                        'sku' => $variant->sku,
                                        'old_stock' => $variant->stock,
                                        'quantity' => $item->quantity
                                    ]);
                                    
                                    $variant->decrement('stock', $item->quantity);
                                    $variant->refresh();
                                    
                                    Log::info('Webhook stock after deduction:', [
                                        'variant_id' => $variant->id,
                                        'new_stock' => $variant->stock
                                    ]);
                                }
                            }
                            
                            // Clear cart
                            CartItem::where('user_id', $order->user_id)->delete();
                            Log::info('Cart cleared via webhook for user:', ['user_id' => $order->user_id]);
                        }
                    }
                }
                break;
                
            case 'checkout.session.expired':
                $session = $event->data->object;
                Log::info('Stripe webhook: checkout.session.expired', ['session_id' => $session->id]);
                $order = Order::where('payment_id', $session->id)->first();
                if ($order && $order->order_status === 'pending') {
                    $order->update([
                        'order_status' => 'cancelled',
                        'payment_status' => 'failed',
                    ]);
                }
                break;
        }

        return response()->json(['status' => 'success']);
    }

    // Add new address via AJAX
    public function addAddress(Request $request)
    {
        $validated = $request->validate([
            'address_name' => ['required', 'string', 'max:100'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line1' => ['required', 'string'],
            'address_line2' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:shipping,billing'],
            'is_default' => ['boolean'],
        ]);

        $address = UserAddress::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        return response()->json([
            'success' => true,
            'address' => $address,
            'message' => 'Address saved successfully'
        ]);
    }

    // Set default address
    public function setDefaultAddress(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Remove default from all addresses of this type
        UserAddress::where('user_id', Auth::id())
            ->where('type', $address->type)
            ->update(['is_default' => false]);

        // Set this as default
        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default address updated'
        ]);
    }
}