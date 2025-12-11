<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'image',
        'image_2',
        'image_3',
        'image_4',
        'status',
        'stock',      // Add this for inventory tracking
        'price',      // Add this for pricing
        'sku',        // Optional: Stock Keeping Unit
        'created_at', // Make sure this is included
        'updated_at', // Make sure this is included
    ];

    // Make sure timestamps are enabled
    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    // Add this relationship for order tracking
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for low stock products
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<', $threshold)
                     ->where('stock', '>', 0);
    }

    // Scope for out of stock products
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', 0);
    }

    // Scope for in stock products
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Calculate total quantity sold
    public function getTotalSoldAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    // Calculate total revenue from this product
    public function getTotalRevenueAttribute()
    {
        return $this->orderItems()->sum(DB::raw('quantity * price'));
    }
}