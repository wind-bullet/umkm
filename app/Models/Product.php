<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 
        'stock', 'rating', 'review_count', 'image', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function voucherItems()
    {
        return $this->hasMany(VoucherItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
