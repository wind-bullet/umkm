<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_code', 'subtotal', 'shipping_cost', 
        'total', 'payment_method', 'delivery_method', 'shipping_address', 'order_status',
        'confirmation_requested', 'confirmed_received'
    ];

    protected $casts = [
        'confirmation_requested' => 'boolean',
        'confirmed_received' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
