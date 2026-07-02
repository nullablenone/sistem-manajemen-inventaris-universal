<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransactionItem extends Model
{
    protected $fillable = [
        'stock_transaction_id',
        'product_variant_id',
        'quantity'
    ];

    public function transaction()
    {
        return $this->belongsTo(StockTransaction::class, 'stock_transaction_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
