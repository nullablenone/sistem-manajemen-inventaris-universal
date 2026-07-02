<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'price', 'stock'];

    // Relasi balik ke Produk Induk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Attribute Values (Tabel Pivot)
    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class, 
            'product_variant_values', // Nama tabel pivot
            'product_variant_id',     // Foreign key di pivot untuk model ini
            'attribute_value_id'      // Foreign key di pivot untuk model target
        );
    }
}
