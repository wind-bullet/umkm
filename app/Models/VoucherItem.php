<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherItem extends Model
{
    protected $fillable = ['product_id', 'voucher_type', 'voucher_label'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
