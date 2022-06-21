<?php

namespace App\Models;
use \App\Models\Variant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function variants(){
        return $this->belongsToMany(Variant::class,'product_variants','product_id', 'variant_id');
    }  
    public function producs_variant_price(){
        return $this->hasMany(ProductVariantPrice::class);
    }   

}
