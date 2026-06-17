<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOption extends Model
{
    protected $fillable = ['name', 'fee_type', 'fee_value', 'is_active'];
}
