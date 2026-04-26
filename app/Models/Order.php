<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_phone', 'customer_social', 'customer_address',
        'subtotal', 'discount', 'delivery_charge', 'total', 'coupon_code',
        'payment_method', 'status', 'notes'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
