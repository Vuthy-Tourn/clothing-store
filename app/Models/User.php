<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp_code',
        'account_type',
        'user_type',
        'customer_id',
        'loyalty_points',
        'newsletter_opt_in',
        'phone',
        'profile_picture',
        'dob',
        'gender',
        'is_verified',
        'is_active',
        'last_login_at',
        'default_address_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'newsletter_opt_in' => 'boolean',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'loyalty_points' => 'integer',
        ];
    }

    /**
     * Relationships
     */
    
    /**
     * Get all addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * Get the default shipping address.
     */
   public function defaultAddress()
{
    return $this->hasOne(UserAddress::class)
                ->where('is_default', true);
}


    /**
     * Get the cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // /**
    //  * Get the wishlist items for the user.
    //  */
    // public function wishlists()
    // {
    //     return $this->hasMany(Wishlist::class);
    // }

    // /**
    //  * Get the reviews written by the user.
    //  */
    // public function reviews()
    // {
    //     return $this->hasMany(ProductReview::class);
    // }

    /**
     * Get the newsletter subscription for the user.
     */
    public function newsletterSubscription()
    {
        return $this->hasOne(NewsletterSubscription::class);
    }

    /**
     * Scopes
     */
    
    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include customers.
     */
    public function scopeCustomers($query)
    {
        return $query->where('account_type', 'customer');
    }

    /**
     * Scope a query to only include admins.
     */
    public function scopeAdmins($query)
    {
        return $query->where('account_type', 'admin');
    }

    /**
     * Scope a query to only include staff.
     */
    public function scopeStaff($query)
    {
        return $query->where('account_type', 'staff');
    }

    /**
     * Scope a query to only include vendors.
     */
    public function scopeVendors($query)
    {
        return $query->where('account_type', 'vendor');
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Accessors & Mutators
     */
    
    /**
     * Get the user's full name with account type.
     *
     * @return string
     */
    public function getFullNameWithTypeAttribute()
    {
        return "{$this->name} ({$this->account_type})";
    }

    /**
     * Get the user's initials for avatar.
     *
     * @return string
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper($name[0]);
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get the user's age from date of birth.
     *
     * @return int|null
     */
    public function getAgeAttribute()
    {
        if (!$this->dob) {
            return null;
        }
        
        return now()->diffInYears($this->dob);
    }

    /**
     * Check if the user has admin privileges.
     *
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return in_array($this->account_type, ['admin', 'staff']);
    }

    /**
     * Check if the user has vendor privileges.
     *
     * @return bool
     */
    public function getIsVendorAttribute()
    {
        return $this->account_type === 'vendor';
    }

    /**
     * Check if the user is a customer.
     *
     * @return bool
     */
    public function getIsCustomerAttribute()
    {
        return $this->account_type === 'customer';
    }

    /**
     * Get the user's default shipping address.
     *
     * @return UserAddress|null
     */
    public function getShippingAddressAttribute()
    {
        if ($this->default_address_id) {
            return $this->defaultAddress;
        }
        
        return $this->addresses()->shipping()->where('is_default', true)->first();
    }

    /**
     * Get the user's default billing address.
     *
     * @return UserAddress|null
     */
    public function getBillingAddressAttribute()
    {
        return $this->addresses()->billing()->where('is_default', true)->first();
    }

    /**
     * Check if the user has a default shipping address.
     *
     * @return bool
     */
    public function hasShippingAddress()
    {
        return $this->shipping_address !== null;
    }

    /**
     * Check if the user has a default billing address.
     *
     * @return bool
     */
    public function hasBillingAddress()
    {
        return $this->billing_address !== null;
    }

    /**
     * Get the user's cart item count.
     *
     * @return int
     */
    public function getCartCountAttribute()
    {
        return $this->cartItems()->sum('quantity');
    }

    /**
     * Get the user's cart total amount.
     *
     * @return float
     */
    public function getCartTotalAttribute()
    {
        $total = 0;
        
        foreach ($this->cartItems as $item) {
            if ($item->variant) {
                $price = $item->variant->sale_price ?? $item->variant->price;
                $total += $price * $item->quantity;
            }
        }
        
        return $total;
    }

    /**
     * Get the user's total orders count.
     *
     * @return int
     */
    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    /**
     * Get the user's total spent amount.
     *
     * @return float
     */
    public function getTotalSpentAttribute()
    {
        return $this->orders()->where('payment_status', 'paid')->sum('total_amount');
    }

    /**
     * Methods
     */
    
    /**
     * Update the last login timestamp.
     *
     * @return bool
     */
    public function updateLastLogin()
    {
        return $this->update(['last_login_at' => now()]);
    }

    /**
     * Add loyalty points to the user.
     *
     * @param int $points
     * @return bool
     */
    public function addLoyaltyPoints($points)
    {
        $this->increment('loyalty_points', $points);
        return true;
    }

    /**
     * Deduct loyalty points from the user.
     *
     * @param int $points
     * @return bool
     */
    public function deductLoyaltyPoints($points)
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        
        return false;
    }

    /**
     * Check if the user has subscribed to newsletter.
     *
     * @return bool
     */
    public function isSubscribedToNewsletter()
    {
        return $this->newsletter_opt_in === true;
    }

    /**
     * Toggle newsletter subscription.
     *
     * @return bool
     */
    public function toggleNewsletterSubscription()
    {
        $this->newsletter_opt_in = !$this->newsletter_opt_in;
        return $this->save();
    }

    /**
     * Check if user can review a product.
     *
     * @param Product $product
     * @return bool
     */
    public function canReviewProduct($product)
    {
        // User must have purchased the product
        $hasPurchased = $this->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->whereHas('variant', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                });
            })
            ->where('payment_status', 'paid')
            ->exists();

        // Check if user has already reviewed
        $hasReviewed = $this->reviews()
            ->where('product_id', $product->id)
            ->exists();

        return $hasPurchased && !$hasReviewed;
    }

    /**
     * Get the user's recent orders.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentOrders($limit = 5)
    {
        return $this->orders()
            ->with(['items.variant.product'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get the user's pending cart items count.
     *
     * @return int
     */
    public function pendingCartCount()
    {
        return $this->cartItems()
            ->whereHas('variant', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->sum('quantity');
    }

    /**
     * Clear user's cart.
     *
     * @return bool
     */
    public function clearCart()
    {
        return $this->cartItems()->delete() > 0;
    }

    /**
     * Get user's order statistics.
     *
     * @return array
     */
    public function getOrderStats()
    {
        return [
            'total_orders' => $this->total_orders,
            'total_spent' => $this->total_spent,
            'pending_orders' => $this->orders()->where('order_status', 'pending')->count(),
            'completed_orders' => $this->orders()->where('order_status', 'delivered')->count(),
            'cancelled_orders' => $this->orders()->where('order_status', 'cancelled')->count(),
        ];
    }
}