<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'is_approved',
        'helpful_count'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeWithHighRating($query, $threshold = 4)
    {
        return $query->where('rating', '>=', $threshold);
    }

    public function scopeWithLowRating($query, $threshold = 2)
    {
        return $query->where('rating', '<=', $threshold);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_approved ? 'Approved' : 'Pending';
    }

    public function getStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }

    // Methods
    public function approve()
    {
        $this->update(['is_approved' => true]);
        $this->product->updateRating($this->rating);
        return $this;
    }

    public function reject()
    {
        $this->delete();
        return true;
    }

    public function incrementHelpful()
    {
        $this->increment('helpful_count');
        return $this;
    }

    public function decrementHelpful()
    {
        $this->decrement('helpful_count');
        return $this;
    }
}