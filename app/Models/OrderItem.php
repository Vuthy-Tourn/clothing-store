<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',
        'variant_details',
        'quantity',
        'unit_price',
        'total_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'variant_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the variant that owns the item.
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Get the formatted unit price.
     *
     * @return string
     */
    public function getFormattedUnitPriceAttribute()
    {
        return '$' . number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted total price.
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute()
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Get the variant size from details.
     *
     * @return string|null
     */
    public function getSizeAttribute()
    {
        return $this->variant_details['size'] ?? null;
    }

    /**
     * Get the variant color from details.
     *
     * @return string|null
     */
    public function getColorAttribute()
    {
        return $this->variant_details['color'] ?? null;
    }

    /**
     * Get the variant SKU from details.
     *
     * @return string|null
     */
    public function getSkuAttribute()
    {
        return $this->variant_details['sku'] ?? null;
    }
}