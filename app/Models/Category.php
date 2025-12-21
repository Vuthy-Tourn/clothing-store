<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = [
        'name',
        'gender', // Add this
        'slug',
        'image',
        'description',
        'parent_id',
        'status',
        'sort_order'
    ];

    // Relationship: Category belongs to a parent category
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship: Category has many children
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    // Relationship: Category has many products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for main categories (no parent)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for subcategories (has parent)
     */
    public function scopeSub($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Get the full path (breadcrumb) for the category
     */
    public function getPathAttribute()
    {
        $path = [];
        $category = $this;

        while ($category) {
            $path[] = $category->name;
            $category = $category->parent;
        }

        return implode(' > ', array_reverse($path));
    }
}