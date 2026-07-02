<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $fillable = [
        'product_variant_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_id',
        'reference_type',
        'note',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
