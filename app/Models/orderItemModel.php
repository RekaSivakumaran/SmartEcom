<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orderItemModel extends Model
{
     protected $table = 'order_items';

     protected $fillable = [
        'order_id',
        'product_id',
        'image_path',
        'quantity',
        'price',
        'total'
    ];

    // public function order()
    // {
    //     return $this->belongsTo(OrderModel::class, 'order_id');
    // }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}
