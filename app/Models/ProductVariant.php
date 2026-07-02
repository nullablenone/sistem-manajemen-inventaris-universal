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

    // Relasi ke StockMutations (Log Mutasi Stok)
    public function mutations()
    {
        return $this->hasMany(StockMutation::class);
    }

    // Accessor untuk penamaan varian di form mutasi
    public function getDisplayNameAttribute()
    {
        $productName = $this->product->name;
        $sku = $this->sku ? " ({$this->sku})" : "";
        
        if ($this->product->has_variant && $this->attributeValues->isNotEmpty()) {
            $variantDetails = $this->attributeValues->pluck('value')->implode(' / ');
            return "{$productName} - {$variantDetails}{$sku} [Stok: {$this->stock}]";
        }
        
        return "{$productName}{$sku} [Stok: {$this->stock}]";
    }
}

