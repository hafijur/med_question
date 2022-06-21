<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant_1()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_one');
    }
    public function variant_2()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_two');
    }
    public function variant_3()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_three');
    }

}
