<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'discount_type',
        'discount_value',
        'discount_start',
        'discount_end',
        'has_discount',
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
        'discount_value' => 'decimal:2',
        'has_discount' => 'boolean',
    ];

    protected $appends = ['final_price', 'has_active_discount', 'discount_percentage', 'is_discounted'];

    /**
     * Get the product that owns the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the final price with hybrid discount logic
     * SIMPLIFIED VERSION - Try sale_price first, then discount logic
     */
    public function getFinalPriceAttribute()
    {
        // If sale_price is set and lower than regular price, use it
        if ($this->sale_price && $this->sale_price < $this->price && $this->sale_price > 0) {
            return round($this->sale_price, 2);
        }
        
        // Check if variant has its own active discount
        if ($this->has_active_discount) {
            return $this->calculateDiscountedPrice();
        }
        
        // Check if product has active discount
        if ($this->product && $this->product->isDiscountActive()) {
            return $this->calculateProductDiscountedPrice();
        }
        
        // No discount, return regular price
        return round($this->price, 2);
    }

    /**
     * Check if variant has active discount
     * SIMPLIFIED VERSION - Check multiple conditions
     */
    public function getHasActiveDiscountAttribute()
    {
        // Option 1: Check if sale_price is set and valid
        if ($this->sale_price && $this->sale_price < $this->price && $this->sale_price > 0) {
            return true;
        }
        
        // Option 2: Check variant-level discount
        if ($this->has_discount && $this->discount_type && $this->discount_value > 0) {
            return $this->isDiscountDateValid();
        }
        
        // Option 3: Check product-level discount
        if ($this->product && $this->product->isDiscountActive()) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if discount dates are valid
     */
    public function isDiscountDateValid()
    {
        if (!$this->discount_start && !$this->discount_end) {
            return true; // No date restrictions
        }
        
        $now = Carbon::now();
        
        if ($this->discount_start && $now->lt($this->discount_start)) {
            return false;
        }
        
        if ($this->discount_end && $now->gt($this->discount_end)) {
            return false;
        }
        
        return true;
    }

    /**
     * Calculate variant-level discounted price
     */
    protected function calculateDiscountedPrice()
    {
        if (!$this->discount_type || !$this->discount_value) {
            return round($this->price, 2);
        }
        
        if ($this->discount_type === 'percentage') {
            $discountAmount = $this->price * ($this->discount_value / 100);
            return round($this->price - $discountAmount, 2);
        }
        
        if ($this->discount_type === 'fixed') {
            return round(max(0.01, $this->price - $this->discount_value), 2);
        }
        
        return round($this->price, 2);
    }

    /**
     * Calculate product-level discounted price applied to variant
     */
    protected function calculateProductDiscountedPrice()
    {
        $product = $this->product;
        
        if (!$product || !$product->discount_type || !$product->discount_value) {
            return round($this->price, 2);
        }
        
        if ($product->discount_type === 'percentage') {
            $discountAmount = $this->price * ($product->discount_value / 100);
            return round($this->price - $discountAmount, 2);
        }
        
        if ($product->discount_type === 'fixed') {
            return round(max(0.01, $this->price - $product->discount_value), 2);
        }
        
        return round($this->price, 2);
    }

    /**
     * Get discount percentage (shows highest applicable discount)
     * SIMPLIFIED VERSION
     */
    public function getDiscountPercentageAttribute()
    {
        $finalPrice = $this->final_price;
        
        // If final price is less than regular price, calculate percentage
        if ($finalPrice < $this->price && $this->price > 0) {
            $percentage = (($this->price - $finalPrice) / $this->price) * 100;
            return round($percentage, 1);
        }
        
        return 0;
    }

    /**
     * Simple check if item is discounted
     */
    public function getIsDiscountedAttribute()
    {
        return $this->final_price < $this->price;
    }

    /**
     * Check if any discount applies (variant OR product)
     */
    public function getHasAnyDiscountAttribute()
    {
        return $this->has_active_discount;
    }
}