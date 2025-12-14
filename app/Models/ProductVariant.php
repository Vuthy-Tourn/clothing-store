<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'size',
        'color',
        'color_code',
        'price',
        'sale_price',
        'cost_price',
        'stock',
        'stock_alert',
        'weight',
        'dimensions',
        'is_active',
         // DISCOUNT FIELDS
        'discount_type',
        'discount_value',
        'discount_start',
        'discount_end',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'stock_alert' => 'integer',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
        'discount_value' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}