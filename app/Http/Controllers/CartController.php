<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // 👉 Check if stock is available
        $sizeData = ProductSize::where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if (!$sizeData || $sizeData->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stock not available for the selected size.');
        }

        // Check if item already exists
        $existing = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'size' => $request->size,
                'quantity' => $request->quantity,
            ]);
        }

         if ($request->input('action') === 'buy_now') {
        return redirect()->route('cart')->with('status', 'added_to_cart');
    }

    // Get the current product ID to redirect back to the same product
    $productId = $request->product_id;
    return redirect()->route('product.view', ['id' => $productId, 'added' => 'true']);
    }

    public function remove($id)
    {
        $item = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($item) {
            $item->delete();
            
            // Return JSON for AJAX requests
            if (request()->expectsJson()) {
                $grandTotal = $this->calculateGrandTotal();
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'grand_total' => $grandTotal
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
        $items = CartItem::with(['product.sizes'])->where('user_id', Auth::id())->get();

        // Inject correct price for each item based on size
        foreach ($items as $item) {
            $sizeObj = $item->product->sizes->firstWhere('size', $item->size);
            $item->unit_price = $sizeObj ? $sizeObj->price : 0;
        }

        return view('frontend.cart', compact('items'));
    }

    // New method for AJAX quantity updates
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        
        $cartItem = CartItem::where('id', $request->item_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        // Check stock availability
        $sizeData = ProductSize::where('product_id', $cartItem->product_id)
            ->where('size', $cartItem->size)
            ->first();

        if (!$sizeData || $sizeData->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not available for the selected quantity.'
            ], 400);
        }

        // Update quantity
        $cartItem->update(['quantity' => $request->quantity]);

        // Calculate updated totals
        $sizeObj = $cartItem->product->sizes->firstWhere('size', $cartItem->size);
        $itemTotal = $sizeObj ? $sizeObj->price * $cartItem->quantity : 0;
        $grandTotal = $this->calculateGrandTotal();

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully.',
            'item_total' => $itemTotal,
            'grand_total' => $grandTotal
        ]);
    }

    // Helper method to calculate grand total
    private function calculateGrandTotal()
    {
        $items = CartItem::with(['product.sizes'])->where('user_id', Auth::id())->get();
        
        $grandTotal = 0;
        foreach ($items as $item) {
            $sizeObj = $item->product->sizes->firstWhere('size', $item->size);
            $price = $sizeObj ? $sizeObj->price : 0;
            $grandTotal += $price * $item->quantity;
        }
        
        return $grandTotal;
    }
}