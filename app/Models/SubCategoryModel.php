<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoryModel extends Model
{
     protected $table = 'sub_categories';

     
    protected $fillable = [
        'image',
        'sub_category_name',
        'description',
        'main_category_id',
        'status',
        'main_categoryname',
    ];

     
    public function mainCategory()
    {
        return $this->belongsTo(MainCategoryModel::class, 'main_category_id'); // ✔ matches your model
    }
}
