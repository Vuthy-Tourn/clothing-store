<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_variant_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // Accessor to get product through variant
    public function getProductAttribute()
    {
        return $this->variant?->product;
    }

    // Accessor to get unit price
    public function getUnitPriceAttribute()
    {
        return $this->variant ? ($this->variant->sale_price ?? $this->variant->price) : 0;
    }

    // Accessor to get total price
    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}