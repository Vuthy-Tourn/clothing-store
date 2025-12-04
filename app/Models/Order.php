<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'total_amount',
        'status',
        'name',
        'email',
        'phone',
        'city',
        'state',
        'zip',
        'address',
        'created_at', // Add this
        'updated_at', // Add this
    ];

    // Make sure timestamps are enabled
    public $timestamps = true;

    // If your created_at column has a different name, add this:
    // const CREATED_AT = 'your_column_name';
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add scope for completed orders (if your completed status is different)
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Add scope for active orders
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'processing', 'shipped']);
    }

    // Add scope for today's orders
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}