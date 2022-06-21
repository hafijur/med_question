<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variants', 'product_id', 'variant_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
