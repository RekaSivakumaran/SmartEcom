<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategoryModel extends Model
{
    protected $table = 'main_categories';

    protected $fillable = [
        'Maincategoryname',
        'description',
        'status',
        'imagepath',
    ];
}
