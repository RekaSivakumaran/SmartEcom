<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = [
        'product_id',
        'customer_id',
        'reviewer_name',
        'rating',
        'comment',
        'status',
    ];

    // Review → Product
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }

    // Review → Customer
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }
}