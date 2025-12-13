<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'address_name',
        'full_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for shipping addresses
    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    // Scope for billing addresses
    public function scopeBilling($query)
    {
        return $query->where('type', 'billing');
    }

    // Get full address formatted
    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
        if ($this->country && $this->country !== 'United States') {
            $address .= ', ' . $this->country;
        }
        return $address;
    }

    // Get display name
    public function getDisplayNameAttribute()
    {
        if ($this->address_name) {
            return $this->address_name;
        }
        return $this->full_name . "'s " . ucfirst($this->type) . ' Address';
    }
}