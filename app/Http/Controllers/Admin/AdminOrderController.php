<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
     /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user'])
            ->latest();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        
        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Date filter
        if ($request->filled('date_range')) {
            $now = now();
            
            switch($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $now->subDay()->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', $now->subYear());
                    break;
            }
        }
        
        // Paginate
        $orders = $query->paginate($request->get('per_page', 15))
            ->appends($request->except('page'));
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Get order details for modal.
     */
    public function getOrderDetails($id)
    {
        try {
            $order = Order::with([
                'user', 
                'shippingAddress', 
                'billingAddress',
                'items.variant.product'
            ])->findOrFail($id);
            
            $html = view('admin.orders.partials.details', compact('order'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'order_number' => $order->order_number
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load order details'
            ], 404);
        }
    }

    /**
     * Update order status.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
                'admin_notes' => 'nullable|string',
            ]);
            
            $order = Order::findOrFail($id);
            
            // Store old status for comparison
            $oldStatus = $order->order_status;
            
            // Update order
            $order->update([
                'order_status' => $request->order_status,
                'admin_notes' => $request->admin_notes,
            ]);
            
            // If order is delivered, set delivered_at
            if ($request->order_status === 'delivered' && !$order->delivered_at) {
                $order->update(['delivered_at' => now()]);
            }
            
            // Handle stock management
            if ($request->order_status === 'cancelled' && $oldStatus !== 'cancelled') {
                // Restore stock to variants
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
            }
            
            // If uncancelling a cancelled order, deduct stock
            if ($oldStatus === 'cancelled' && $request->order_status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->variant && $item->variant->stock >= $item->quantity) {
                        $item->variant->decrement('stock', $item->quantity);
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order_status' => $order->order_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update order status'
            ], 500);
        }
    }

    /**
     * Update payment status.
     */
    public function updatePayment(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:pending,paid,failed,refunded',
                'payment_id' => 'nullable|string|max:100',
                'payment_date' => 'nullable|date',
            ]);
            
            $order = Order::findOrFail($id);
            
            $updateData = [
                'payment_status' => $request->payment_status,
            ];
            
            if ($request->filled('payment_id')) {
                $updateData['payment_id'] = $request->payment_id;
            }
            
            if ($request->filled('payment_date')) {
                $updateData['payment_date'] = $request->payment_date;
            } elseif ($request->payment_status === 'paid' && !$order->payment_date) {
                $updateData['payment_date'] = now();
            }
            
            $order->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully.',
                'payment_status' => $order->payment_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update payment status'
            ], 500);
        }
    }

    /**
     * Update tracking information.
     */
    public function updateTracking(Request $request, $id)
    {
        try {
            $request->validate([
                'tracking_number' => 'required|string|max:100',
                'shipping_method' => 'nullable|string|max:50',
                'estimated_delivery' => 'nullable|date',
            ]);
            
            $order = Order::findOrFail($id);
            
            $updateData = [
                'tracking_number' => $request->tracking_number,
            ];
            
            if ($request->filled('shipping_method')) {
                $updateData['shipping_method'] = $request->shipping_method;
            }
            
            if ($request->filled('estimated_delivery')) {
                $updateData['estimated_delivery'] = $request->estimated_delivery;
            }
            
            // Auto update status to shipped if not already
            if ($order->order_status !== 'shipped' && $order->order_status !== 'delivered') {
                $updateData['order_status'] = 'shipped';
            }
            
            $order->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Tracking information updated successfully.',
                'tracking_number' => $order->tracking_number
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to add tracking information'
            ], 500);
        }
    }

    /**
     * Delete order.
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $orderNumber = $order->order_number;
            
            // Restore stock if order was not cancelled
            if ($order->order_status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
            }
            
            $order->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Order ' . $orderNumber . ' deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete order'
            ], 500);
        }
    }

    /**
     * Generate invoice PDF.
     */
    public function invoice($id)
    {
        try {
            $order = Order::with([
                'user', 
                'shippingAddress', 
                'billingAddress',
                'items.variant.product'
            ])->findOrFail($id);
            
            $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
            
            return $pdf->download('Invoice_' . $order->order_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate invoice.');
        }
    }

   
    /**
     * Export orders.
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,pdf',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
        
        $orders = Order::whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ])
            ->with(['user', 'shippingAddress', 'items'])
            ->latest()
            ->get();
        
        if ($orders->isEmpty()) {
            return back()->with('error', 'No orders found in the selected date range.');
        }
        
        if ($request->format === 'csv') {
            return $this->exportToCsv($orders, $request->from_date, $request->to_date);
        } else {
            return $this->exportToPdf($orders, $request->from_date, $request->to_date);
        }
    }
    
    private function exportToCsv($orders, $fromDate, $toDate)
    {
        $filename = 'orders_export_' . $fromDate . '_to_' . $toDate . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Total Amount',
                'Order Status',
                'Payment Status',
                'Payment Method',
                'Order Date',
                'Items Count',
                'Shipping City',
                'Shipping Country',
                'Tracking Number'
            ]);
            
            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? 'N/A',
                    '$' . number_format($order->total_amount, 2),
                    ucfirst($order->order_status),
                    ucfirst($order->payment_status),
                    $order->payment_method ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->items->count(),
                    $order->shippingAddress->city ?? 'N/A',
                    $order->shippingAddress->country ?? 'N/A',
                    $order->tracking_number ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportToPdf($orders, $fromDate, $toDate)
    {
        $data = [
            'orders' => $orders,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalAmount' => $orders->sum('total_amount'),
            'orderCount' => $orders->count()
        ];
        
        $pdf = Pdf::loadView('admin.orders.export-pdf', $data);
        
        $filename = 'orders_export_' . $fromDate . '_to_' . $toDate . '.pdf';
        
        return $pdf->download($filename);
    }
}
