<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'order_status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_id',
        'payment_date',
        'shipping_method',
        'tracking_number',
        'estimated_delivery',
        'delivered_at',
        'shipping_address_id',
        'billing_address_id',
        'customer_notes',
        'admin_notes',
        'stripe_session_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'estimated_delivery' => 'date',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<string>
     */
    protected $hidden = [
        'admin_notes',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'billing_address_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    /**
     * Scope a query to only include confirmed orders.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 'confirmed');
    }

    /**
     * Scope a query to only include processing orders.
     */
    public function scopeProcessing($query)
    {
        return $query->where('order_status', 'processing');
    }

    /**
     * Scope a query to only include shipped orders.
     */
    public function scopeShipped($query)
    {
        return $query->where('order_status', 'shipped');
    }

    /**
     * Scope a query to only include delivered orders.
     */
    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    /**
     * Scope a query to only include cancelled orders.
     */
    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope a query to only include failed orders.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Check if the order is pending.
     *
     * @return bool
     */
    public function getIsPendingAttribute()
    {
        return $this->order_status === 'pending';
    }

    /**
     * Check if the order is confirmed.
     *
     * @return bool
     */
    public function getIsConfirmedAttribute()
    {
        return $this->order_status === 'confirmed';
    }

    /**
     * Check if the order is processing.
     *
     * @return bool
     */
    public function getIsProcessingAttribute()
    {
        return $this->order_status === 'processing';
    }

    /**
     * Check if the order is shipped.
     *
     * @return bool
     */
    public function getIsShippedAttribute()
    {
        return $this->order_status === 'shipped';
    }

    /**
     * Check if the order is delivered.
     *
     * @return bool
     */
    public function getIsDeliveredAttribute()
    {
        return $this->order_status === 'delivered';
    }

    /**
     * Check if the order is cancelled.
     *
     * @return bool
     */
    public function getIsCancelledAttribute()
    {
        return $this->order_status === 'cancelled';
    }

    /**
     * Check if the order is paid.
     *
     * @return bool
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if the order payment failed.
     *
     * @return bool
     */
    public function getIsFailedAttribute()
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Get the order status with badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-indigo-100 text-indigo-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
        ];

        $class = $badges[$this->order_status] ?? 'bg-gray-100 text-gray-800';
        $status = ucfirst($this->order_status);

        return "<span class='px-3 py-1 rounded-full text-xs font-medium {$class}'>{$status}</span>";
    }

    /**
     * Get the payment status with badge HTML.
     *
     * @return string
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
        ];

        $class = $badges[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
        $status = ucfirst($this->payment_status);

        return "<span class='px-3 py-1 rounded-full text-xs font-medium {$class}'>{$status}</span>";
    }

    /**
     * Get the formatted total amount.
     *
     * @return string
     */
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
    }

    /**
     * Get the formatted subtotal amount.
     *
     * @return string
     */
    public function getFormattedSubtotalAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    /**
     * Get the formatted tax amount.
     *
     * @return string
     */
    public function getFormattedTaxAttribute()
    {
        return '$' . number_format($this->tax_amount, 2);
    }

    /**
     * Get the formatted shipping amount.
     *
     * @return string
     */
    public function getFormattedShippingAttribute()
    {
        return '$' . number_format($this->shipping_amount, 2);
    }

    /**
     * Get the formatted discount amount.
     *
     * @return string
     */
    public function getFormattedDiscountAttribute()
    {
        return '$' . number_format($this->discount_amount, 2);
    }

    /**
     * Get the order items count.
     *
     * @return int
     */
    public function getItemsCountAttribute()
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Mark the order as shipped.
     *
     * @param string $trackingNumber
     * @param string $shippingMethod
     * @return bool
     */
    public function markAsShipped($trackingNumber = null, $shippingMethod = null)
    {
        return $this->update([
            'order_status' => 'shipped',
            'tracking_number' => $trackingNumber,
            'shipping_method' => $shippingMethod,
        ]);
    }

    /**
     * Mark the order as delivered.
     *
     * @return bool
     */
    public function markAsDelivered()
    {
        return $this->update([
            'order_status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Cancel the order.
     *
     * @return bool
     */
    public function cancel()
    {
        return $this->update([
            'order_status' => 'cancelled',
        ]);
    }

    /**
     * Process refund for the order.
     *
     * @return bool
     */
    public function refund()
    {
        return $this->update([
            'payment_status' => 'refunded',
        ]);
    }
}