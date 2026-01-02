<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:add,buy_now',
        ]);

        $user = Auth::user();

        // Get the variant and verify it belongs to the product
        $variant = ProductVariant::where('id', $request->variant_id)
            ->where('product_id', $request->product_id)
            ->where('is_active', true)
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Selected variant is not available.');
        }

        // Check stock availability
        if ($variant->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stock not available for the selected variant. Only ' . $variant->stock . ' items left.');
        }

        // Check if item already exists in cart (using product_variant_id)
        $existing = CartItem::where('user_id', $user->id)
            ->where('product_variant_id', $request->variant_id)
            ->first();

        if ($existing) {
            // Check if adding more exceeds stock
            $newTotalQuantity = $existing->quantity + $request->quantity;
            if ($newTotalQuantity > $variant->stock) {
                return redirect()->back()->with('error', 'Cannot add more items. Only ' . $variant->stock . ' items left in stock.');
            }
            
            $existing->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        // Handle buy now action
        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout')->with('status', 'added_to_cart');
        }

        // Redirect back to product with success message
        $product = Product::find($request->product_id);
        return redirect()->route('product.view', ['slug' => $product->slug])->with('status', 'added_to_cart');
    }

    public function remove($id)
    {
        $item = CartItem::where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

        if ($item) {
            $item->delete();
            
            // Return JSON for AJAX requests
            if (request()->expectsJson()) {
                $cartTotal = $this->calculateCartTotal();
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'cart_total' => $cartTotal
                ]);
            }
            
            return redirect()->route('cart')->with('success', 'Item removed from cart.');
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found or unauthorized.'
            ], 404);
        }

        return redirect()->route('cart')->with('error', 'Item not found or unauthorized.');
    }

    public function view()
    {
        $items = CartItem::with(['variant.product.images'])
            ->where('user_id', Auth::id())
            ->get();

        // Calculate totals
        $subtotal = 0;
        foreach ($items as $item) {
            if ($item->variant) {
                // Get final price with discount already applied from variant
                $item->unit_price = $item->variant->final_price;
                $item->total_price = $item->unit_price * $item->quantity;
                $subtotal += $item->total_price;
                
                // Add product info to item for display
                $item->product_name = $item->variant->product->name ?? 'Product';
                $item->product_image = $item->variant->product->images->first()->image_path ?? 'products/placeholder.jpg';
                $item->size = $item->variant->size;
                $item->color = $item->variant->color;
                $item->color_code = $item->variant->color_code;
                $item->sku = $item->variant->sku;
                
                // Discount information (already applied in final_price)
                $item->has_discount = $item->variant->has_discount;
                $item->discount_value = $item->variant->discount_value;
            }
        }

        return view('frontend.cart', compact('items', 'subtotal'));
    }

    // AJAX quantity updates
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        
        $cartItem = CartItem::where('id', $request->item_id)
            ->where('user_id', $user->id)
            ->with('variant')
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        // Check stock availability using variant
        if ($cartItem->variant && $cartItem->variant->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not available for the selected quantity. Only ' . $cartItem->variant->stock . ' items left.'
            ], 400);
        }

        // Update quantity
        $cartItem->update(['quantity' => $request->quantity]);

        // Calculate item total with discount already applied
        $itemTotal = 0;
        if ($cartItem->variant) {
            // final_price already includes discounts
            $itemTotal = $cartItem->variant->final_price * $cartItem->quantity;
        }

        // Calculate updated cart total
        $cartTotal = $this->calculateCartTotal();

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully.',
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal,
            'grand_total' => $cartTotal + ($cartTotal * 0.08) // Include tax
        ]);
    }

    private function calculateCartTotal()
    {
        $items = CartItem::with(['variant'])
            ->where('user_id', Auth::id())
            ->get();
        
        $total = 0;
        foreach ($items as $item) {
            if ($item->variant) {
                // final_price already includes discounts
                $price = $item->variant->final_price;
                $total += $price * $item->quantity;
            }
        }
        
        return $total;
    }

    // Clear entire cart
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully.'
            ]);
        }
        
        return redirect()->route('cart')->with('success', 'Cart cleared successfully.');
    }

    // Get cart count for navbar
    public function count()
    {
        $count = CartItem::where('user_id', Auth::id())->sum('quantity');
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}