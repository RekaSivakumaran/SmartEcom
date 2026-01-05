<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductModel extends Model
{
     use HasFactory;
     protected $table = 'products';

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
        return $this->belongsTo(MainCategoryModel::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategoryModel::class);
    }

    public function brand() {
        return $this->belongsTo(BrandModel::class);
    }
}
