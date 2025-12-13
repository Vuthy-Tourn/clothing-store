<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'short_description',
        'sku',
        'brand',
        'status',
        'is_featured',
        'is_new',
        'rating_cache',
        'review_count',
        'view_count'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'rating_cache' => 'decimal:2',
        'review_count' => 'integer',
        'view_count' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Generate slug if not provided
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Make slug unique
                $originalSlug = $product->slug;
                $counter = 1;
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
            
            // Generate SKU if not provided
            if (empty($product->sku)) {
                $product->sku = 'PROD-' . strtoupper(Str::random(8));
            }
            
            // Set default values
            if (empty($product->rating_cache)) $product->rating_cache = 0.00;
            if (empty($product->review_count)) $product->review_count = 0;
            if (empty($product->view_count)) $product->view_count = 0;
            if (empty($product->status)) $product->status = 'active';
            if (empty($product->is_featured)) $product->is_featured = false;
            if (empty($product->is_new)) $product->is_new = false;
        });

        static::updating(function ($product) {
            // Update slug if name changed
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
                
                // Make slug unique (excluding current product)
                $originalSlug = $product->slug;
                $counter = 1;
                while (static::where('slug', $product->slug)
                        ->where('id', '!=', $product->id)
                        ->exists()) {
                    $product->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    // Accessors (Computed Properties)
    public function getMinPriceAttribute()
    {
        if ($this->variants->count() > 0) {
            return $this->variants->min('price');
        }
        return 0;
    }

    public function getMaxPriceAttribute()
    {
        if ($this->variants->count() > 0) {
            return $this->variants->max('price');
        }
        return 0;
    }

    public function getSalePriceAttribute()
    {
        if ($this->variants->count() > 0) {
            $variantWithSale = $this->variants->whereNotNull('sale_price')->first();
            return $variantWithSale ? $variantWithSale->sale_price : null;
        }
        return null;
    }

    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }

    public function getAvailableSizesAttribute()
    {
        return $this->variants->where('stock', '>', 0)
                            ->pluck('size')
                            ->unique()
                            ->sort()
                            ->values();
    }

    public function getAvailableColorsAttribute()
    {
        return $this->variants->where('stock', '>', 0)
                            ->pluck('color')
                            ->unique()
                            ->sort()
                            ->values();
    }

    public function getMainImageAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return $primaryImage->image_path;
        }
        
        $firstImage = $this->images->first();
        return $firstImage ? $firstImage->image_path : null;
    }

    // Methods for inventory
    public function isInStock()
    {
        return $this->total_stock > 0;
    }

    public function isLowStock($threshold = 10)
    {
        return $this->total_stock > 0 && $this->total_stock <= $threshold;
    }

    public function isOutOfStock()
    {
        return $this->total_stock <= 0;
    }

    public function getLowStockVariants($threshold = 10)
    {
        return $this->variants->where('stock', '>', 0)
                            ->where('stock', '<=', $threshold);
    }

    public function getOutOfStockVariants()
    {
        return $this->variants->where('stock', '<=', 0);
    }

    // Methods for sales tracking
    public function getTotalSoldAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orderItems()->sum(DB::raw('quantity * price'));
    }

    public function getAverageRatingAttribute()
    {
        if ($this->review_count > 0) {
            return number_format($this->rating_cache, 1);
        }
        return 0;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    // Helper methods
    public function hasVariants()
    {
        return $this->variants->count() > 0;
    }

    public function hasMultipleImages()
    {
        return $this->images->count() > 1;
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'draft' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusIcon()
    {
        return match($this->status) {
            'active' => 'fa-check-circle',
            'inactive' => 'fa-times-circle',
            'draft' => 'fa-edit',
            default => 'fa-question-circle',
        };
    }

    // Method to update rating
    public function updateRating($newRating)
    {
        $totalRating = ($this->rating_cache * $this->review_count) + $newRating;
        $this->review_count++;
        $this->rating_cache = $totalRating / $this->review_count;
        $this->save();
    }

    // Method to get variant by size and color
    public function getVariant($size, $color = null)
    {
        $query = $this->variants()->where('size', $size);
        
        if ($color) {
            $query->where('color', $color);
        }
        
        return $query->where('is_active', true)->first();
    }

    // Method to check if variant exists
    public function variantExists($size, $color = null)
    {
        return (bool) $this->getVariant($size, $color);
    }

    // Method to get first available variant
    public function getFirstAvailableVariant()
    {
        return $this->variants()
                    ->where('stock', '>', 0)
                    ->where('is_active', true)
                    ->first();
    }

    // In App\Models\Product

/**
 * Get available sizes with stock
 */
public function getAvailableSizesWithStockAttribute()
{
    return $this->variants()
        ->where('stock', '>', 0)
        ->where('is_active', true)
        ->select('size', DB::raw('SUM(stock) as total_stock'))
        ->groupBy('size')
        ->orderBy('size')
        ->get()
        ->map(function($item) {
            return [
                'size' => $item->size,
                'stock' => $item->total_stock
            ];
        });
}

/**
 * Get available colors with stock
 */
public function getAvailableColorsWithStockAttribute()
{
    return $this->variants()
        ->where('stock', '>', 0)
        ->where('is_active', true)
        ->select('color', 'color_code', DB::raw('SUM(stock) as total_stock'))
        ->groupBy('color', 'color_code')
        ->orderBy('color')
        ->get()
        ->map(function($item) {
            return [
                'color' => $item->color,
                'color_code' => $item->color_code,
                'stock' => $item->total_stock
            ];
        });
}

/**
 * Get the first available variant
 */
public function getFirstAvailableVariantAttribute()
{
    return $this->variants()
        ->where('stock', '>', 0)
        ->where('is_active', true)
        ->orderBy('price')
        ->first();
}

/**
 * Check if product is on sale
 */
public function IsOnSale()
{
    return $this->variants()
        ->whereNotNull('sale_price')
        ->where('sale_price', '<', DB::raw('price'))
        ->where('stock', '>', 0)
        ->exists();
}

/**
 * Get the best discount percentage
 */
public function getBestDiscountAttribute()
{
    $variant = $this->variants()
        ->whereNotNull('sale_price')
        ->where('sale_price', '<', DB::raw('price'))
        ->where('stock', '>', 0)
        ->orderByRaw('(price - sale_price) / price DESC')
        ->first();
    
    return $variant ? round((($variant->price - $variant->sale_price) / $variant->price) * 100) : 0;
}

public function getRouteKeyName()
    {
        return 'slug';
    }
}
