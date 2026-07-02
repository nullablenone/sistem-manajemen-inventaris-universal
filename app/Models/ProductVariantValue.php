<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    // Mematikan timestamps karena tabel pivot ini tidak butuh created_at/updated_at
    public $timestamps = false; 
    
    protected $fillable = ['product_variant_id', 'attribute_value_id'];
}
