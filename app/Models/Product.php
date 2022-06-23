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
        return $this->belongsToMany(Variant::class,'product_variants','product_id', 'variant_id')->withPivot('variant');
    }  

    public function product_variant(){
        return $this->hasMany(ProductVariant::class,'product_id');
    } 
    public function producs_variant_price(){
        return $this->hasMany(ProductVariantPrice::class);
    }   

}
