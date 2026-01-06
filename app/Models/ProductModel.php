<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'main_category_id',
        'sub_category_id',
        'brand_id',
        'image',
        'description',
        'price',
        'discount_type',
        'discount_rate',
        'discount_amount',
    ];
    
    public function mainCategory() {
        return $this->belongsTo(MainCategory::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
